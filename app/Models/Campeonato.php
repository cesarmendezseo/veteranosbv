<?php

namespace App\Models;

use App\NormalizesAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert as FacadesLivewireAlert;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Campeonato extends Model
{
    use HasFactory, NormalizesAttributes;

    protected $fillable = [
        'nombre',
        'categoria_id',
        'formato', // Usaremos este en lugar de status para la lógica
        'puntos_ganado',
        'puntos_empatado',
        'puntos_perdido',
        'puntos_tarjeta_amarilla',
        'puntos_doble_amarilla',
        'puntos_tarjeta_roja',
        'cantidad_equipos_grupo',
        'cantidad_grupos',
        'total_equipos',
        'status', // Mantenemos por compatibilidad con tu DB (ENUM)
        'config_sancion',
        'fase_actual_id',
        'config_liguilla', // Asegúrate de tener esto como cast 'array'
        'equipos_fase_arriba',
        'equipos_fase_abajo',
    ];

    protected $casts = [
        'config_liguilla' => 'array',
        'config_sancion' => 'array',
    ];

    /* =====================================================
     | MUTATORS (Para evitar el error de "Data Truncated")
     ===================================================== */

    // Sincroniza formato con la columna status de la DB automáticamente
    public function setFormatoAttribute($value)
    {
        $this->attributes['formato'] = $value;
        $this->attributes['status'] = $value;
    }

    /* =====================================================
     | RELACIONES
     ===================================================== */

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function grupos()
    {
        return $this->hasMany(Grupo::class)->with('equipos');
    }

    public function equipos()
    {
        return $this->belongsToMany(Equipo::class, 'campeonato_equipo')
            ->withPivot('grupo_id')
            ->withTimestamps();
    }

    public function fases(): HasMany
    {
        return $this->hasMany(FaseCampeonato::class)->orderBy('orden');
    }

    public function faseActual()
    {
        return $this->belongsTo(FaseCampeonato::class, 'fase_actual_id');
    }

    public function criterioDesempate()
    {
        return $this->belongsTo(Criterios_desempate::class);
    }

    private function crearPartidosEliminatorios(array $ids, $faseModel, $subTipo)
    {
        $total = count($ids);
        if ($total < 2) return;

        // Emparejamiento: 1º vs Último, 2º vs Penúltimo...
        for ($i = 0; $i < $total / 2; $i++) {
            Encuentro::create([
                'campeonato_id'      => $this->id,
                'equipo_local_id'    => $ids[$i],
                'equipo_visitante_id' => $ids[$total - 1 - $i],
                'fase'               => $subTipo, // superior / inferior
                'fase_campeonato_id' => $faseModel->id,
                'estado'             => 'programado',
                'fecha_encuentro'    => 1, // Jornada 1 de eliminación
            ]);
        }
    }

    /* =====================================================
     | LÓGICA DE AVANCE DE FASE (Liguilla Superior/Inferior)
     ===================================================== */
    public function avanzarFase(): bool
    {
        if (!$this->fase_actual_id) {
            return false;
        }

        $faseActual = $this->faseActual;

        if (!$faseActual) {
            return false;
        }

        // ✅ PRIMERO buscar fases siguientes
        $siguientesFases = $this->fases()
            ->where('orden', '>', $faseActual->orden)
            ->orderBy('orden')
            ->get();

        // ✅ Validar ANTES de finalizar
        if ($siguientesFases->isEmpty()) {
            return false; // No hace cambios si no hay fases siguientes
        }

        // ✅ AHORA SÍ finalizar (solo si hay fases siguientes)
        $faseActual->update(['estado' => 'finalizada']);

        // 3️⃣ Procesar cada fase
        foreach ($siguientesFases as $faseDestino) {
            $cantidad = $this->generarCrucesLiguilla($faseActual, $faseDestino);

            if ($cantidad === 0) {
                return false;
            }

            $faseDestino->update(['estado' => 'activa']);
        }

        // 4️⃣ Avanzar fase actual
        $this->update([
            'fase_actual_id' => $siguientesFases->first()->id
        ]);

        return true;
    }





    /* =====================================================
     | CÁLCULO DE TABLA DE POSICIONES
     ===================================================== */

    public function calcularTablaFase(FaseCampeonato $fase): array
    {
        $tabla = [];

        $partidos = Encuentro::where('campeonato_id', $this->id)
            ->where('fase_id', $fase->id) // ✅ CLAVE
            ->whereIn('estado', ['Jugado', 'jugado', 'JUGADO'])
            ->get();

        if ($partidos->isEmpty()) {
            return [];
        }

        foreach ($partidos as $p) {
            foreach (
                [
                    [$p->equipo_local_id, $p->gol_local, $p->gol_visitante],
                    [$p->equipo_visitante_id, $p->gol_visitante, $p->gol_local]
                ] as [$equipoId, $gf, $gc]
            ) {

                $tabla[$equipoId] ??= [
                    'equipo_id' => $equipoId,
                    'pj' => 0,
                    'pts' => 0,
                    'gf' => 0,
                    'gc' => 0,
                    'dg' => 0,
                ];

                $tabla[$equipoId]['pj']++;
                $tabla[$equipoId]['gf'] += $gf;
                $tabla[$equipoId]['gc'] += $gc;
                $tabla[$equipoId]['dg'] = $tabla[$equipoId]['gf'] - $tabla[$equipoId]['gc'];

                if ($gf > $gc) {
                    $tabla[$equipoId]['pts'] += $this->puntos_ganado;
                } elseif ($gf === $gc) {
                    $tabla[$equipoId]['pts'] += $this->puntos_empatado;
                } else {
                    $tabla[$equipoId]['pts'] += $this->puntos_perdido;
                }
            }
        }

        return collect($tabla)
            ->sortByDesc(fn($e) => [$e['pts'], $e['dg'], $e['gf']])
            ->values()
            ->map(fn($e, $i) => array_merge($e, ['posicion' => $i + 1]))
            ->toArray();
    }

    /*======================================================== 
| GENERAR CRUCES DE LIGUILLA    
=======================================================*/

    private function generarCrucesLiguilla($faseOrigen, $faseDestino): int
    {
        $tabla = $this->calcularTablaFase($faseOrigen);
        $equiposRankeados = collect($tabla);

        if ($equiposRankeados->isEmpty()) {
            return 0;
        }

        $rango = $faseDestino->reglas_clasificacion['rango'] ?? 'superior';
        $cantidad = (int) ($faseDestino->reglas_clasificacion['cantidad'] ?? 0);

        if ($cantidad <= 0) {
            return 0;
        }

        if ($rango === 'superior') {
            $clasificados = $equiposRankeados->take($cantidad);
        } else {
            $faseOro = $this->fases()
                ->where('reglas_clasificacion->rango', 'superior')
                ->first();

            $salto = $faseOro
                ? (int) ($faseOro->reglas_clasificacion['cantidad'] ?? 0)
                : 0;

            $clasificados = $equiposRankeados->slice($salto, $cantidad);
        }

        // 🔥 ELIMINAR ESTA LÍNEA - NO BORRAR
        // DB::table('equipos_fase')
        //     ->where('fase_id', $faseDestino->id)
        //     ->delete();

        // ✅ SOLO INSERTAR SI NO EXISTE
        foreach ($clasificados as $item) {
            DB::table('equipos_fase')->insertOrIgnore([
                'fase_id' => $faseDestino->id,
                'equipo_id' => $item['equipo_id'],
                'fase_origen_id' => $faseOrigen->id,
                'posicion_origen' => $item['posicion'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $clasificados->count();
    }

    /* ==============================================
        | CREAR FINAL VACÍA
    ====================================================== */
    public function crearFinalVacia($faseActualId, $faseNombre)
    {
        return Encuentro::create([
            'campeonato_id' => $this->id,
            'fase_id' => $faseActualId,
            'fase' => $faseNombre,
            'equipo_local_id' => null, // Se llenará por sorteo o ganador
            'equipo_visitante_id' => null, // Se llenará por ganador
            'estado' => 'pendiente',
            'fecha' => now()->addDays(7), // Fecha tentativa
            'hora' => '20:00',

        ]);
    }
}
