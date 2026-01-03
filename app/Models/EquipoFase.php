<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipoFase extends Model
{
    protected $table = 'equipos_fase';

    protected $fillable = [
        'fase_id',
        'equipo_id',
        'clasifico_desde_fase_id',
        'posicion_origen',
    ];

    public function fase(): BelongsTo
    {
        return $this->belongsTo(FaseCampeonato::class, 'fase_id');
    }

    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class);
    }

    public function clasificoDesdeFase(): BelongsTo
    {
        return $this->belongsTo(FaseCampeonato::class, 'clasifico_desde_fase_id');
    }
}
