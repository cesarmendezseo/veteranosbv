<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampeonatoJugadorEquipo extends Model
{
    protected $table = 'campeonato_jugador_equipo';

    protected $fillable = [
        'campeonato_id',
        'equipo_id',
        'jugador_id',
        'categoria_id',
        'fecha_alta',
        'fecha_baja',
    ];

    public function jugador()
    {
        return $this->belongsTo(Jugador::class, 'jugador_id');
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    public function campeonato()
    {
        return $this->belongsTo(Campeonato::class);
    }

    public function sanciones()
    {
        return $this->hasMany(Sanciones::class, 'jugador_id');
    }
}
