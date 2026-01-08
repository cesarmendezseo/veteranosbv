<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'gol_local',
        'gol_visitante',
        'estado',
        'fase_id',
        'fase',
    ];
    protected $table = 'encuentros';

    public function estadisticaJugadores()
    {
        return $this->morphMany(EstadisticaJugadorEncuentro::class, 'estadisticable');
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

    public function campeonato()
    {
        return $this->belongsTo(Campeonato::class);
    }
    //relacion polimorfica
    public function estadisticas()
    {
        return $this->morphMany(EstadisticaJugadorEncuentro::class, 'estadisticable');
    }

    public function sanciones()
    {
        return $this->morphMany(Sanciones::class, 'sancionable');
    }

    public function fase(): BelongsTo
    {
        return $this->belongsTo(FaseCampeonato::class, 'fase_id');
    }
}
