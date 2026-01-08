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

    /* ================= RELACIONES ================= */

    public function campeonato(): BelongsTo
    {
        return $this->belongsTo(Campeonato::class);
    }

    public function encuentros(): HasMany
    {
        return $this->hasMany(Encuentro::class, 'fase_id');
    }

    public function equipos()
    {
        // El segundo parámetro DEBE ser el nombre exacto de tu tabla pivot
        return $this->belongsToMany(Equipo::class, 'equipos_fase', 'fase_id', 'equipo_id')
            ->withPivot('posicion_origen')
            ->withTimestamps();
    }

    public function faseOrigen()
    {
        return $this->belongsTo(FaseCampeonato::class, 'fase_origen_id');
    }
    /* ================= LÓGICA ================= */

    public function estaCompletada(): bool
    {
        return $this->estado === 'completada';
    }

    public function activar(): void
    {
        $this->update(['estado' => 'activa']);
        $this->campeonato->update(['fase_actual_id' => $this->id]);
    }

    public function completar(): void
    {
        $this->update(['estado' => 'completada']);
    }

    // ✅ RELACIÓN CORRECTA
    public function partidos(): HasMany
    {
        return $this->hasMany(Encuentro::class, 'fase_id');
    }

    /**
     * Fase terminada = no hay encuentros sin resultado
     */
    public function faseTerminada(): bool
    {
        return ! $this->partidos()
            ->where(function ($q) {
                $q->whereNull('gol_local')
                    ->orWhereNull('gol_visitante');
            })
            ->exists();
    }

    public function esEliminatoria(): bool
    {
        return in_array($this->tipo_fase, [
            'eliminacion_simple',
            'eliminacion_doble',
        ]);
    }
}
