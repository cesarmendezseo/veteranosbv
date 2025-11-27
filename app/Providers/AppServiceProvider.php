<?php

namespace App\Providers;

use App\Models\PwaConfig;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
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

        // Carga la configuración desde la DB para la configuracion de pwa
        if (Schema::hasTable('pwa_configs')) {
            $config = PwaConfig::getSingletonConfig();

            if ($config) {
                // Sobrescribe los valores del manifest de la PWA
                Config::set('pwa.manifest.name', $config->name);
                Config::set('pwa.manifest.short_name', $config->short_name);
                Config::set('pwa.manifest.theme_color', $config->theme_color);
                Config::set('pwa.manifest.background_color', $config->background_color);
                // 🆕 Actualiza el ícono: Sobrescribe la propiedad 'src' del primer ícono [0]
                Config::set('pwa.manifest.icons.0.src', $config->icon);

                // Opcional: Si quieres que el campo 'description' también sea dinámico, añádelo:
                Config::set('pwa.manifest.description', $config->description);
            }
        }
    }
}
