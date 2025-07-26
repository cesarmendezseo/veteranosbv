<?php

namespace App\Models;

use App\NormalizesAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cancha extends Model
{
    use HasFactory;
    use NormalizesAttributes;

    protected $normalizable = ['nombre', 'direccion', 'ciudad'];
    protected $fillable = [
        'nombre',
        'direccion',
        'ciudad',
    ];
}
