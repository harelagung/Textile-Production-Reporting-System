<?php

namespace App\Traits;

trait HasRolePermissionAccess
{
    /**
     * Check if user has access to manage roles and permissions
     */
    public static function hasRolePermissionAccess($user = null): bool
    {
        $user = $user ?? auth()->user();

        if (!$user) {
            return false;
        }

        // Load position relation jika belum di-load untuk menghindari N+1 query
        if (!$user->relationLoaded("position")) {
            $user->load("position");
        }

        // Cek apakah user memiliki role super admin ATAU position manager
        return $user->hasRole("Super Admin") && ($user->position && strtolower($user->position->name) === "manager");
    }
}
