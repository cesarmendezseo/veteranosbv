<?php

namespace App\Models;

use App\NormalizesAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Canchas extends Model
{
    use HasFactory;
    use NormalizesAttributes;

    protected $normalizable = ['nombre', 'direccion', 'ciudad', 'provincia', 'cod_pos', 'otros'];
    protected $fillable = [
        'nombre',
        'direccion',
        'ciudad',
        'provincia',
        'cod_pos',
        'otros',
    ];
    // Relación: una cancha tiene muchos encuentros
    public function encuentros()
    {
        return $this->hasMany(Encuentro::class, 'cancha_id');
    }
}
