<?php

namespace App\Providers;

use App\Policies\RolePolicy;
use Filament\Support\Assets\Js;
use Filament\Support\Assets\Css;
use App\Policies\PermissionPolicy;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use App\Http\Responses\LogoutResponse;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use Filament\Support\Facades\FilamentAsset;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LogoutResponseContract::class, LogoutResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Permission::class, PermissionPolicy::class);
    }
}
