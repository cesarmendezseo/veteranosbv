<?php

namespace App\Models;

use App\NormalizesAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable; // <--- ESTA es la interfaz correcta
use OwenIt\Auditing\Auditable as AuditableTrait; // El trait para la lógica

class Sanciones extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
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
        'etapa_sancion',
        'sancionable_id',
        'sancionable_type',
        'fecha_inicio',
        'fecha_fin',
        'medida',
        'encuentro_id',
    ];

    public function sancionable()
    {
        return $this->morphTo();
    }

    public function jugador()
    {
        return $this->belongsTo(Jugador::class);
    }

    public function campeonato()
    {
        return $this->belongsTo(Campeonato::class);
    }

    //para imprimir las sanciones en el export del listado de buena fe
    public function scopeActivas($q)
    {
        return $q->where(function ($q) {
            $q->where('cumplida', false)
                ->orWhereColumn('partidos_cumplidos', '<', 'partidos_sancionados');
        });
    }
}
