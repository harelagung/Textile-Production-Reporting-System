<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    protected $user_type;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "user_cred" => ["required", "string"],
            "password" => ["required", "string"],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->user_type = filter_var($this->input("user_cred"), FILTER_VALIDATE_EMAIL) ? "email" : "nip";
        $this->merge([
            $this->user_type => $this->input("user_cred"),
        ]);
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // TAMBAHKAN KODE INI DI SINI:
        $user = User::where("email", $this->user_cred)->orWhere("nip", $this->user_cred)->first();

        if (!$user) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                "user_cred" => "NIP atau Email tidak ditemukan!",
            ]);
        }

        if (!Hash::check($this->password, $user->password)) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                "user_cred" => "Password salah!",
            ]);
        }

        // Login user secara manual jika validasi berhasil
        Auth::login($user, $this->boolean("remember"));

        // if (!Auth::attempt($this->only($this->user_type, "password"), $this->boolean("remember"))) {
        //     RateLimiter::hit($this->throttleKey());

        //     throw ValidationException::withMessages([
        //         // "user_cred" => trans("auth.failed"),
        //         "user_cred" => "NIP atau Email dan Password tidak cocok!",
        //     ]);
        // }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            "email" => trans("auth.throttle", [
                "seconds" => $seconds,
                "minutes" => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string("email")) . "|" . $this->ip());
    }
}
