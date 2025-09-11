<?php

namespace App\Models;

use App\NormalizesAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estadio extends Model
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
}
