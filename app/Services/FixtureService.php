<?php

namespace App\Services;

use App\Models\Encuentro;
use Carbon\Carbon;

class FixtureService
{
    public function generarRoundRobin($campeonatoId, $faseId, $grupoId, $equiposIds)
    {
        $equipos = $equiposIds;

        // Si es impar, añadimos un 'null' para representar el descanso
        if (count($equipos) % 2 != 0) {
            $equipos[] = null;
        }

        $cantidadEquipos = count($equipos);
        $cantidadFechas = $cantidadEquipos - 1;
        $partidosPorFecha = $cantidadEquipos / 2;
        $encuentros = [];

        for ($fecha = 0; $fecha < $cantidadFechas; $fecha++) {
            for ($partido = 0; $partido < $partidosPorFecha; $partido++) {
                $local = ($fecha + $partido) % ($cantidadEquipos - 1);
                $visitante = ($cantidadEquipos - 1 - $partido + $fecha) % ($cantidadEquipos - 1);

                if ($partido == 0) {
                    $visitante = $cantidadEquipos - 1;
                }

                $encuentros[] = [
                    'campeonato_id' => $campeonatoId,
                    'fase_id' => $faseId,
                    'grupo_id' => $grupoId,
                    'nro_fecha' => $fecha + 1,
                    'equipo_local_id' => $equipos[$local],
                    'equipo_visitante_id' => $equipos[$visitante],
                    'estado' => 'programado'
                ];
            }
        }
        return $encuentros;
    }
}
