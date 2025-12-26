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
    //use SoftDeletes;

    protected $normalizable = ['nombre', 'ciudad', 'provincia', 'descripcion'];
    protected $fillable = [
        'nombre',
        'ciudad',
        'provincia',
        'cod_pos',
        'descripcion',
        'logo',

    ];
    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function jugadores()
    {
        return $this->hasMany(Jugador::class);
    }
    public function partidosLocal()
    {
        return $this->hasMany(Encuentro::class, 'local_id');
    }

    public function partidosVisitante()
    {
        return $this->hasMany(Encuentro::class, 'visitante_id');
    }


    public function campeonatos()
    {
        return $this->belongsToMany(Campeonato::class, 'campeonato_equipo', 'equipo_id', 'campeonato_id')
            ->withPivot('grupo_id')
            ->withTimestamps();
    }

    public function jugadoresDelCampeonato($campeonatoId)
    {
        return $this->belongsToMany(Jugador::class, 'campeonato_jugador_equipo')
            ->wherePivot('campeonato_id', $campeonatoId)
            ->whereNull('fecha_baja')  // opcional, si querés solo los activos
            ->withPivot('fecha_alta', 'fecha_baja');
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    //esta relacion es para registrar que fecha se da de alta o baja un jugador
    public function equipoJugador()
    {
        return $this->belongsToMany(Jugador::class, 'jugador_equipo')
            ->withPivot('fecha_alta', 'fecha_baja')
            ->withTimestamps();
    }

    // Relación con encuentros donde fue local para partidos de eliminatorias
    public function encuentrosLocal()
    {
        return $this->hasMany(Encuentro::class, 'equipo_local_id');
    }

    // Relación con encuentros donde fue visitante para partidos de eliminatorias
    public function encuentrosVisitante()
    {
        return $this->hasMany(Encuentro::class, 'equipo_visitante_id');
    }

    protected function nombre(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn($value) => strtoupper($value),
        );
    }
}
