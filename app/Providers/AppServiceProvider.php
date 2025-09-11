<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::if('adminOrCan', function ($permissions = null) {
            $user = auth()->user();
            if (!$user) return false;

            // Si no se pasan permisos → solo admin
            if (!$permissions) {
                return $user->hasRole('administrador');
            }

            // Permite múltiples permisos separados por | o coma
            $list = is_array($permissions) ? $permissions : preg_split('/[|,]/', $permissions);
            return $user->hasRole('administrador') || $user->hasAnyPermission($list);
        });
    }
}
