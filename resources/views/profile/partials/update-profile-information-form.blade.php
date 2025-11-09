<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full bg-gray-100"
                :value="old('name', $user->name)" disabled readonly />
            <p class="mt-1 text-xs text-gray-500">{{ __('Nama tidak dapat diubah') }}</p>
        </div>

        {{-- Field NIP disabled --}}
        <div>
            <x-input-label for="nip" :value="__('Nomor Induk Pegawai')" />
            <x-text-input id="nip" name="nip" type="text" class="mt-1 block w-full bg-gray-100"
                :value="old('nip', $user->nip)" disabled readonly />
            <p class="mt-1 text-xs text-gray-500">{{ __('NIP tidak dapat diubah') }}</p>
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            {{-- Email verification section tetap sama --}}
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                {{-- ... kode verification sama seperti sebelumnya --}}
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
