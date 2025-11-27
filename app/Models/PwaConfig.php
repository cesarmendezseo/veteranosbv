<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PwaConfig extends Model
{
    use HasFactory;

    /**
     * El nombre de la tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'pwa_configs';

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'short_name',
        'background_color',
        'theme_color',
        'description',
        'icon',
    ];

    /**
     * Obtiene la única configuración PWA.
     * Se usa para obtener el registro en cualquier parte de la aplicación.
     *
     * @return \App\Models\PwaConfig
     */
    public static function getSingletonConfig()
    {
        // Usamos firstOrCreate para garantizar que siempre exista al menos un registro.
        return self::firstOrCreate(
            [], // Condiciones de búsqueda (vacías para obtener el primero o crear)
            [
                'name' => 'APP futbol',
                'short_name' => 'Futbol',
                'background_color' => '#6777ef',
                'theme_color' => '#6777ef',
                'description' => 'App webFull.',
                'icon' => 'logo.png',
            ] // Valores por defecto si se crea
        );
    }
}
