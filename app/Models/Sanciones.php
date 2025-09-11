<?php

namespace App\Models;

use App\NormalizesAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sanciones extends Model
{
    use HasFactory;
    use NormalizesAttributes;

    protected $normalizable = ['motivo', 'observacion'];


    protected $fillable = [
        'jugador_id',
        'campeonato_id',
        'fecha_sancion',
        'motivo',
        'partidos_sancionados',
        'partidos_cumplidos',
        'observacion',
        'cumplida',
    ];

    public function jugador()
    {
        return $this->belongsTo(Jugador::class);
    }

    public function campeonato()
    {
        return $this->belongsTo(Campeonato::class);
    }
}
