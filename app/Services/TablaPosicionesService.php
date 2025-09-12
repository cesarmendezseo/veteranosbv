<?php

namespace App\Services;

use App\Models\Campeonato;
use App\Models\Encuentro;
use App\Models\Criterios_desempate;
use App\Models\Equipo;
use App\Models\EstadisticaJugadorEncuentro;

class TablaPosicionesService
{
    public function generar($campeonatoId)
    {
        $campeonato = Campeonato::find($campeonatoId);
        if (!$campeonato) {
            return collect(); // Vacío si no hay campeonato
        }

        $encuentros = Encuentro::where('campeonato_id', $campeonato->id)
            ->where('estado', 'Jugado')
            ->get();

        $tabla = [];

        foreach ($encuentros as $encuentro) {
            $equipos = [
                'local' => $encuentro->equipo_local_id,
                'visitante' => $encuentro->equipo_visitante_id
            ];

            foreach ($equipos as $tipo => $equipo_id) {
                if (!isset($tabla[$equipo_id])) {
                    $tabla[$equipo_id] = [
                        'equipo_id' => $equipo_id,
                        'equipo' => Equipo::find($equipo_id)?->nombre ?? 'Desconocido',
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

                $estadisticas = EstadisticaJugadorEncuentro::where('encuentro_id', $encuentro->id)
                    ->where('equipo_id', $equipo_id)
                    ->selectRaw('
                        SUM(tarjeta_amarilla) as amarillas,
                        SUM(tarjeta_doble_amarilla) as dobles_amarillas,
                        SUM(tarjeta_roja) as rojas
                    ')
                    ->first();

                if ($estadisticas) {
                    $tabla[$equipo_id]['fair_play'] += ($estadisticas->amarillas * $campeonato->puntos_tarjeta_amarilla);
                    $tabla[$equipo_id]['fair_play'] += ($estadisticas->dobles_amarillas * $campeonato->puntos_doble_amarilla);
                    $tabla[$equipo_id]['fair_play'] += ($estadisticas->rojas * $campeonato->puntos_tarjeta_roja);
                }
            }
        }

        foreach ($tabla as $id => &$dato) {
            $dato['diferencia_goles'] = $dato['goles_favor'] - $dato['goles_contra'];
        }

        $criterios = Criterios_desempate::orderBy('orden')->pluck('criterio')->map(function ($criterio) {
            // Mapear nombres incorrectos a los correctos
            return match ($criterio) {
                'fairplay' => 'fair_play',
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

        return [
            'posiciones' => collect($tabla),
            'nombre_campeonato' => $campeonato->nombre
        ];
    }
}
