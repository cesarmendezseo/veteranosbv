<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Puntaje extends Model
{
    use HasFactory;

    protected $fillable = ['equipo_id', 'puntos', 'dif_goles', 'goles_favor', 'fair_play'];

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }
}
