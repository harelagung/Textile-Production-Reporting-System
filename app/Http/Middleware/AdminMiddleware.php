<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect("/login")->with("error", "Silakan login terlebih dahulu.");
        }

        $user = Auth::user();

        // untuk Spatie Permission
        if (!$user->hasAnyRole(["Admin", "Super Admin"])) {
            return redirect(route("report.index"))->with("error", "Anda tidak memiliki akses.");
        }

        return $next($request);
    }
}
