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

    /* =====================================================
     | LÓGICA DE AVANCE DE FASE (Liguilla Superior/Inferior)
     ===================================================== */
    public function avanzarFase(): bool
    {
        $faseActual = $this->faseActual;

        // 1. Finalizar fase actual
        $faseActual->update(['estado' => 'finalizada']);

        // 2. BUSQUEDA FLEXIBLE: Buscar las fases que siguen (cualquiera con orden superior)
        // En lugar de orden + 1, buscamos el siguiente número de orden que exista
        $siguientesFases = $this->fases()
            ->where('orden', '>', $faseActual->orden)
            ->get();

        if ($siguientesFases->isEmpty()) {
            // Si no hay más fases, el campeonato terminó
            return false;
        }

        // 3. Generar cruces para cada fase encontrada (Oro y Plata)
        foreach ($siguientesFases as $faseDestino) {
            $this->generarCrucesLiguilla($faseActual, $faseDestino);
            $faseDestino->update(['estado' => 'activa']);
        }

        // 4. Actualizar el campeonato a la primera de las nuevas fases
        $this->update(['fase_actual_id' => $siguientesFases->first()->id]);

        return true;
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
     | CÁLCULO DE TABLA DE POSICIONES
     ===================================================== */

    public function calcularTablaFase(FaseCampeonato $fase): array
    {
        $tabla = [];

        // Buscamos los encuentros basándonos en el campeonato y el nombre de la fase
        // Usamos trim y case-insensitive para asegurar el match
        $partidos = Encuentro::where('campeonato_id', $this->id)
            ->where('fase', 'like', trim($fase->nombre))
            ->whereIn('estado', ['Jugado', 'jugado', 'JUGADO'])
            ->get();

        // Si no encuentra por nombre, intentamos buscar por los que NO tienen fase de liguilla (Fase Regular)
        if ($partidos->isEmpty() && $fase->orden == 1) {
            $partidos = Encuentro::where('campeonato_id', $this->id)
                ->whereNotIn('fase', ['oro', 'plata', 'superior', 'inferior'])
                ->whereIn('estado', ['Jugado', 'jugado'])
                ->get();
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
                    'dg' => 0
                ];

                $tabla[$equipoId]['pj']++;
                $tabla[$equipoId]['gf'] += $gf;
                $tabla[$equipoId]['gc'] += $gc;
                $tabla[$equipoId]['dg'] = $tabla[$equipoId]['gf'] - $tabla[$equipoId]['gc'];

                if ($gf > $gc) $tabla[$equipoId]['pts'] += $this->puntos_ganado;
                elseif ($gf === $gc) $tabla[$equipoId]['pts'] += $this->puntos_empatado;
                else $tabla[$equipoId]['pts'] += $this->puntos_perdido;
            }
        }

        return collect($tabla)
            ->sortByDesc(fn($e) => [$e['pts'], $e['dg'], $e['gf']])
            ->values()
            ->map(fn($e, $i) => array_merge($e, ['posicion' => $i + 1]))
            ->toArray();
    }

    private function generarCrucesLiguilla($faseOrigen, $faseDestino)
    {
        $tabla = $this->calcularTablaFase($faseOrigen);
        $equiposRankeados = collect($tabla);

        if ($equiposRankeados->isEmpty()) {
            throw new \Exception("No hay datos de partidos jugados para procesar la tabla de " . $faseOrigen->nombre);
        }

        $rango = $faseDestino->reglas_clasificacion['rango'] ?? 'superior';
        $cantidad = (int)($faseDestino->reglas_clasificacion['cantidad'] ?? 4);

        if ($rango === 'superior') {
            // Los mejores N
            $clasificados = $equiposRankeados->take($cantidad);
        } else {
            // Los siguientes N (Saltamos los que fueron a Oro)
            $faseOro = $this->fases()->where('reglas_clasificacion->rango', 'superior')->first();
            $salto = $faseOro ? (int)($faseOro->reglas_clasificacion['cantidad'] ?? 4) : 4;
            $clasificados = $equiposRankeados->slice($salto, $cantidad);
        }

        // GRABACIÓN DIRECTA EN BD
        DB::table('equipos_fase')->where('fase_id', $faseDestino->id)->delete();

        foreach ($clasificados as $item) {
            DB::table('equipos_fase')->insert([
                'fase_id' => $faseDestino->id,
                'equipo_id' => $item['equipo_id'],
                'fase_origen_id' => $faseOrigen->id,
                'posicion_origen' => $item['posicion'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
