<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eliminatoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'campeonato_id',
        'equipo_local_id',
        'equipo_visitante_id',
        'fase',
        'partido_numero',
        'fecha',
        'hora',
        'cancha',
        'goles_local',
        'goles_visitante',
        'definido_por_penales',
        'penales_local',
        'penales_visitante',
        'estado',
    ];

    /* -------------------------
       RELACIONES
    ------------------------- */

    // Un encuentro pertenece a un campeonato
    public function campeonato()
    {
        return $this->belongsTo(Campeonato::class);
    }

    // Relación con equipo local
    public function equipoLocal()
    {
        return $this->belongsTo(Equipo::class, 'equipo_local_id');
    }

    // Relación con equipo visitante
    public function equipoVisitante()
    {
        return $this->belongsTo(Equipo::class, 'equipo_visitante_id');
    }
    public function canchas()
    {
        return $this->belongsTo(Canchas::class, 'cancha');
    }
    public function eventos()
    {
        return $this->hasMany(Encuentro::class);
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }
    public function estadisticas()
    {
        return $this->hasMany(\App\Models\EstadisticaJugadorEncuentro::class, 'encuentro_id');
    }

    public function ganador()
    {
        if (is_null($this->goles_local) || is_null($this->goles_visitante)) {
            return null;
        }

        if ($this->goles_local > $this->goles_visitante) {
            return $this->equipoLocal;
        }

        if ($this->goles_visitante > $this->goles_local) {
            return $this->equipoVisitante;
        }

        // Empate — se define por penales si están cargados
        if (!is_null($this->penales_local) && !is_null($this->penales_visitante)) {
            if ($this->penales_local > $this->penales_visitante) {
                return $this->equipoLocal;
            } elseif ($this->penales_visitante > $this->penales_local) {
                return $this->equipoVisitante;
            }
        }

        return null;
    }
}
