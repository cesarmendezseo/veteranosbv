<?php

namespace App\Services;



use App\Models\Encuentro;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EncuentrosExport;
use Carbon\Carbon;

class EncuentroExportService
{
    public function exportarPorCampeonatoYFecha($campeonatoId, $fecha)
    {
        $encuentros = Encuentro::with(['equipoLocal', 'equipoVisitante', 'cancha'])
            ->where('campeonato_id', $campeonatoId)
            ->where('fecha_encuentro', $fecha)
            ->get()
            ->sortBy(['cancha.nombre', 'hora']) // Ordenar por cancha y hora
            ->groupBy('cancha.nombre');

        // Obtener el nombre del torneo
        $campeonato = \App\Models\Campeonato::find($campeonatoId);
        $nombreTorneo = $campeonato ? $campeonato->nombre : 'Torneo Desconocido';
        // Obtener la fecha del primer encuentro (si existe alguno)
        $fechaPrimerEncuentro = null;
        if ($encuentros->isNotEmpty()) {
            $primerGrupo = $encuentros->first();
            if ($primerGrupo->isNotEmpty()) {
                $fechaPrimerEncuentro = $primerGrupo->first()->fecha;
            }
        }

        // Preparar los datos para Excel
        $datosParaExcel = [];
        $datosParaExcel[] = [$nombreTorneo]; // Agregar el nombre del torneo en la primera fila

        foreach ($encuentros as $cancha => $partidos) {
            $datosParaExcel[] = [$cancha]; // Solo el nombre de la cancha en la primera fila

            foreach ($partidos->sortBy('hora') as $encuentro) {

                $datosParaExcel[] = [


                    $encuentro->equipoLocal->nombre ?? '',
                    strval($encuentro->gol_local ?? 0),
                    strval($encuentro->gol_visitante ?? 0),
                    $encuentro->equipoVisitante->nombre ?? '',
                ];
            }

            $datosParaExcel[] = ['']; // Línea vacía para separar canchas
        }

        return Excel::download(new EncuentrosExport($datosParaExcel, $nombreTorneo, $fechaPrimerEncuentro, $fecha), 'Fixture Fecha_' . $fecha . '.xlsx');
    }
}
