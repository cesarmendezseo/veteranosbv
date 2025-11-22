<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuracions'; // 👈 IMPORTANTE
    public $timestamps = false;          // 👈 Si tu tabla no tiene created_at/updated_at
    protected $fillable = ['key', 'value'];

    public static function get($key, $default = null)
    { // Busca la configuración y devuelve el valor. Si no existe, devuelve null o un valor por defecto.
        return static::where('key', $key)->value('value') ?? $default;
    }

    public static function set($key, $value)
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
