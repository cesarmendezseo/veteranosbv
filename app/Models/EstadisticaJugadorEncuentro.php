<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadisticaJugadorEncuentro extends Model
{
    use HasFactory;


    protected $fillable = [
        'jugador_id',
        'encuentro_id',
        'equipo_id',
        'campeonato_id',
        'goles',
        'tarjeta_amarilla',
        'tarjeta_roja',
        'tarjeta_doble_amarilla',
        'estadisticable_id',
        'estadisticable_type'
    ];

    public function jugador()
    {
        return $this->belongsTo(Jugador::class);
    }
    public function encuentro()
    {
        return $this->belongsTo(Encuentro::class);
    }
    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }
    public function eliminatoria()
    {
        return $this->belongsTo(Eliminatoria::class, 'eliminatoria_id');
    }

    //relacion polimorfica
    public function estadisticable()
    {
        return $this->morphTo();
    }
}
