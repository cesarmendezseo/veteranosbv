<?php

namespace App\Livewire\TablaPosicion;

use App\Models\Campeonato;
use App\Models\Criterios_desempate;
use App\Models\Encuentro;
use App\Models\EstadisticaJugadorEncuentro;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PosicionesExport;


class TablaPosiciones extends Component
{
    public $campeonato_id;
    public $campeonatos;
    public \Illuminate\Support\Collection $posiciones;

    public function mount($campeonatoId)
    {
        $this->campeonato_id = $campeonatoId;
        $this->posiciones = collect();
        $this->generarTablaPosiciones($campeonatoId);
    }

    public function updatedCampeonatoId()
    {
        $this->generarTablaPosiciones();
    }

    public function generarTablaPosiciones(int $campeonatoId = null): void
    {
        $campeonatoId = $campeonatoId ?? $this->campeonato_id;
        $campeonato = Campeonato::find($campeonatoId);

        if (!$campeonato) {
            $this->posiciones = collect();
            return;
        }

        if ($campeonato->formato === 'grupos') {
            $grupos = \App\Models\Grupo::where('campeonato_id', $campeonato->id)->get();
            $resultadosPorGrupo = [];

            foreach ($grupos as $grupo) {
                $resultadosPorGrupo[$grupo->nombre] = $this->calcularPosicionesPorGrupo($campeonato, $grupo->id);
            }

            $this->posiciones = collect($resultadosPorGrupo);
        } else {
            $this->posiciones = collect([
                'General' => $this->calcularPosicionesPorGrupo($campeonato, null)
            ]);
        }
    }

    private function calcularPosicionesPorGrupo($campeonato, $grupoId = null)
    {
        $encuentrosQuery = Encuentro::where('campeonato_id', $campeonato->id)
            ->where('estado', 'Jugado');

        if ($grupoId) {
            $encuentrosQuery->where('grupo_id', $grupoId);
        }

        $encuentros = $encuentrosQuery->get();
        $tabla = [];

        foreach ($encuentros as $encuentro) {
            $equipos = [
                'local' => $encuentro->equipo_local_id,
                'visitante' => $encuentro->equipo_visitante_id
            ];

            foreach ($equipos as $tipo => $equipo_id) {
                $nombreEquipo = \App\Models\Equipo::find($equipo_id)?->nombre ?? 'Desconocido';
                if (in_array(strtolower($nombreEquipo), ['sin equipo', 'desconocido'])) {
                    continue;
                }
                if (!isset($tabla[$equipo_id])) {
                    $tabla[$equipo_id] = [
                        'equipo_id' => $equipo_id,
                        'equipo' => $nombreEquipo,
                        'jugados' => 0,
                        'ganados' => 0,
                        'empatados' => 0,
                        'perdidos' => 0,
                        'goles_favor' => 0,
                        'goles_contra' => 0,
                        'diferencia_goles' => 0,
                        'fair_play' => 0,
                        'puntos' => 0,
                    ];
                }

                $tabla[$equipo_id]['jugados']++;

                $golesFavor = $tipo === 'local' ? $encuentro->gol_local : $encuentro->gol_visitante;
                $golesContra = $tipo === 'local' ? $encuentro->gol_visitante : $encuentro->gol_local;

                $tabla[$equipo_id]['goles_favor'] += $golesFavor;
                $tabla[$equipo_id]['goles_contra'] += $golesContra;

                if ($golesFavor > $golesContra) {
                    $tabla[$equipo_id]['ganados']++;
                    $tabla[$equipo_id]['puntos'] += $campeonato->puntos_ganado;
                } elseif ($golesFavor == $golesContra) {
                    $tabla[$equipo_id]['empatados']++;
                    $tabla[$equipo_id]['puntos'] += $campeonato->puntos_empatado;
                } else {
                    $tabla[$equipo_id]['perdidos']++;
                    $tabla[$equipo_id]['puntos'] += $campeonato->puntos_perdido;
                }
            }
        }

        // Fair Play acumulado por equipo en todo el campeonato
        foreach ($tabla as $equipo_id => &$datosEquipo) {
            $statsEquipo = EstadisticaJugadorEncuentro::where('campeonato_id', $campeonato->id)
                ->where('equipo_id', $equipo_id)
                ->where('estadisticable_type', Encuentro::class)
                ->get();

            $tarjetasAmarillas     = $statsEquipo->sum('tarjeta_amarilla');
            $tarjetasDobleAmarilla = $statsEquipo->sum('tarjeta_doble_amarilla');
            $tarjetasRojas         = $statsEquipo->sum('tarjeta_roja');

            $datosEquipo['fair_play'] =
                ($tarjetasAmarillas * ($campeonato->puntos_tarjeta_amarilla ?? -1)) +
                ($tarjetasDobleAmarilla * ($campeonato->puntos_doble_amarilla ?? -3)) +
                ($tarjetasRojas * ($campeonato->puntos_tarjeta_roja ?? -5));

            $datosEquipo['diferencia_goles'] = $datosEquipo['goles_favor'] - $datosEquipo['goles_contra'];
        }

        $criterios = Criterios_desempate::orderBy('orden')->pluck('criterio')->map(function ($criterio) {
            return match ($criterio) {
                'fairplay' => 'fair_play',
                'goles_diferencia' => 'diferencia_goles',
                default => $criterio
            };
        })->toArray();

        usort($tabla, function ($a, $b) use ($criterios) {
            foreach ($criterios as $criterio) {
                if ($a[$criterio] != $b[$criterio]) {
                    return $b[$criterio] <=> $a[$criterio];
                }
            }
            return 0;
        });

        return collect($tabla);
    }

    public function generarTablaPosicionesPDF(Campeonato $campeonato)
    {
        $this->generarTablaPosiciones($campeonato->id);
        $posiciones = $this->posiciones;
        $title = "Tabla de Posiciones - " . $campeonato->nombre;

        if ($campeonato->formato === 'grupos') {
            return Pdf::loadView('admin.pdf.posiciones_por_grupo', compact('campeonato', 'posiciones', 'title'))
                ->stream('tabla_posiciones_' . str_replace(' ', '_', $campeonato->nombre) . '.pdf');
        }

        return Pdf::loadView('admin.pdf.posiciones', compact('campeonato', 'posiciones', 'title'))
            ->stream('tabla_posiciones_' . str_replace(' ', '_', $campeonato->nombre) . '.pdf');
    }


    public function exportarPosiciones()
    {
        $campeonato = Campeonato::find($this->campeonato_id);

        if (!$campeonato) {
            return;
        }

        $nombreCampeonato = $campeonato->nombre;

        if ($campeonato->formato === 'grupos') {
            $grupos = \App\Models\Grupo::where('campeonato_id', $campeonato->id)->get();
            $posicionesPorGrupo = [];

            foreach ($grupos as $grupo) {
                $posicionesPorGrupo[$grupo->nombre] = $this->calcularPosicionesPorGrupo($campeonato, $grupo->id);
            }

            return Excel::download(
                new \App\Exports\GruposPosicionesExport($posicionesPorGrupo, $nombreCampeonato),
                'tabla_posiciones_por_grupo.xlsx'
            );
        }

        $posiciones = $this->calcularPosicionesPorGrupo($campeonato, null);

        return Excel::download(
            new \App\Exports\PosicionesExport($posiciones, $nombreCampeonato, 'General'),
            'tabla_posiciones_general.xlsx'
        );
    }

    public function render()
    {
        return view('livewire.tabla-posicion.tabla-posiciones');
    }
}
