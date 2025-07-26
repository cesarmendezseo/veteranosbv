<?php

namespace App\Models;

use App\NormalizesAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class Equipo extends Model
{
    use HasFactory;
    use NormalizesAttributes;

    protected $normalizable = ['nombre', 'ciudad', 'provincia', 'descripcion'];
    protected $fillable = [
        'nombre',
        'ciudad',
        'provincia',
        'cod_pos',
        'descripcion',
        'logo',

    ];
}
