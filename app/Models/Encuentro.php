<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encuentro extends Model
{
    use HasFactory;



    protected $fillable = [
        'fecha',
        'hora',
        'campeonato_id',
        'grupo_id',
        'cancha_id',
        'fecha_encuentro',
        'equipo_local_id',
        'equipo_visitante_id',
        'goles_local',
        'goles_visitante',
        'estado',

    ];


    public function estadisticaJugadores()
    {
        return $this->hasMany(EstadisticaJugadorEncuentro::class);
    }

    public function equipoLocal()
    {
        return $this->belongsTo(Equipo::class, 'equipo_local_id');
    }
    public function equipoVisitante()
    {
        return $this->belongsTo(Equipo::class, 'equipo_visitante_id');
    }
    public function eventos()
    {
        return $this->hasMany(Encuentro::class);
    }
    public function cancha()
    {
        return $this->belongsTo(Canchas::class);
    }
    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }
    public function estadisticas()
    {
        return $this->hasMany(\App\Models\EstadisticaJugadorEncuentro::class, 'encuentro_id');
    }
    public function campeonato()
    {
        return $this->belongsTo(Campeonato::class);
    }
}
