<?php

namespace App\Services;

use App\Exports\EliminatoriaExport;
use App\Models\Encuentro;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EncuentrosExport;
use App\Models\Eliminatoria;
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
        $datosParaExcel[] = [strtoupper($nombreTorneo)]; // Agregar el nombre del torneo en la primera fila

        foreach ($encuentros as $cancha => $partidos) {
            $datosParaExcel[] = [$cancha]; // Solo el nombre de la cancha en la primera fila

            foreach ($partidos->sortBy('hora') as $encuentro) {

                $datosParaExcel[] = [


                    strtoupper($encuentro->equipoLocal->nombre ?? ''),
                    strval($encuentro->gol_local ?? 0),
                    strval($encuentro->gol_visitante ?? 0),
                    strtoupper($encuentro->equipoVisitante->nombre ?? ''),
                ];
            }

            $datosParaExcel[] = ['']; // Línea vacía para separar canchas
        }

        return Excel::download(new EncuentrosExport($datosParaExcel, $nombreTorneo, $fechaPrimerEncuentro, $fecha), 'Fixture Fecha_' . $fecha . '.xlsx');
    }

    //===========================   

    //===========================================
    public function exportarEliminatoria($campeonatoId, $fase)
    {
        $encuentros = Eliminatoria::with(['equipoLocal', 'equipoVisitante', 'canchas'])
            ->where('campeonato_id', $campeonatoId)
            ->where('fase', $fase)
            ->get()
            ->sortBy(function ($e) {
                return ($e->canchas->nombre ?? '') . ' ' . $e->hora;
            })
            ->groupBy(fn($e) => $e->canchas->nombre ?? 'Sin cancha');


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
                    strval($encuentro->goles_local ?? ''),
                    strval($encuentro->penales_local ?? ''),
                    strval($encuentro->penales_visitante ?? ''),
                    strval($encuentro->goles_visitante ?? ''),
                    $encuentro->equipoVisitante->nombre ?? '',
                ];
            }

            $datosParaExcel[] = ['']; // Línea vacía para separar canchas
        }

        return Excel::download(new EliminatoriaExport($datosParaExcel, $nombreTorneo, $fechaPrimerEncuentro, $fase), 'Fixture Fecha_' . $fase . '.xlsx');
    }

    /* **************************************** */
    public function exportarTodoElCampeonato($campeonatoId)
    {
        $encuentros = Encuentro::with(['equipoLocal', 'equipoVisitante'])
            ->where('campeonato_id', $campeonatoId)
            ->orderBy('fecha_encuentro')
            ->orderBy('hora')
            ->get()
            ->groupBy('fecha_encuentro'); // 🔹 Agrupamos SOLO por jornada

        // Datos del torneo
        $campeonato = \App\Models\Campeonato::find($campeonatoId);
        $nombreTorneo = $campeonato ? $campeonato->nombre : 'Torneo Desconocido';

        $datosParaExcel = [];
        $datosParaExcel[] = [strtoupper($nombreTorneo)];

        foreach ($encuentros as $jornada => $partidos) {

            // 🔹 Encabezado de la jornada
            $datosParaExcel[] = [''];
            $datosParaExcel[] = ["JORNADA $jornada"];
            $datosParaExcel[] = [''];

            foreach ($partidos as $encuentro) {
                $datosParaExcel[] = [
                    strtoupper($encuentro->equipoLocal->nombre ?? ''),
                    strval($encuentro->gol_local ?? 0),
                    strval($encuentro->gol_visitante ?? 0),
                    strtoupper($encuentro->equipoVisitante->nombre ?? ''),
                ];
            }

            $datosParaExcel[] = [''];
        }

        return Excel::download(
            new EncuentrosExport($datosParaExcel, $nombreTorneo, null, 'COMPLETO'),
            'Fixture_Completo_' . $nombreTorneo . '.xlsx'
        );
    }
}
