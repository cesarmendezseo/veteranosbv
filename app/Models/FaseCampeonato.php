<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FaseCampeonato extends Model
{
    protected $table = 'fases_campeonato';

    protected $fillable = [
        'campeonato_id',
        'nombre',
        'tipo_fase',
        'orden',
        'reglas_clasificacion',
        'estado',
    ];

    protected $casts = [
        'reglas_clasificacion' => 'array',
    ];

    public function campeonato(): BelongsTo
    {
        return $this->belongsTo(Campeonato::class);
    }

    public function partidos(): HasMany
    {
        return $this->hasMany(Encuentro::class, 'fase_id');
    }

    public function equipos(): BelongsToMany
    {
        return $this->belongsToMany(Equipo::class, 'equipos_fase', 'fase_id', 'equipo_id')
            ->withPivot('clasifico_desde_fase_id', 'posicion_origen')
            ->withTimestamps();
    }

    public function equiposFase(): HasMany
    {
        return $this->hasMany(EquipoFase::class, 'fase_id');
    }

    public function estaCompletada(): bool
    {
        return $this->estado === 'completada';
    }

    public function activar()
    {
        $this->update(['estado' => 'activa']);
        $this->campeonato->update(['fase_actual_id' => $this->id]);
    }

    public function completar()
    {
        $this->update(['estado' => 'completada']);
    }
}
