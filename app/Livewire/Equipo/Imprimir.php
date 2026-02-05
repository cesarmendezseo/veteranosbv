<?php

namespace App\Livewire\Equipo;

use App\Models\CampeonatoJugadorEquipo;
use App\Models\Sanciones;
use Livewire\Component;

class Imprimir extends Component
{
    public $equipoId;
    public $equipoNombre;
    public $torneoNombre;
    public $campeonatoId;
    public $fecha;
    public $jugadores;
    public $cancha;

    public function mount($equipoId, $torneoNombre, $campeonatoId, $fecha, $cancha = '')
    {
        $this->equipoId = $equipoId;
        $this->equipoNombre = \App\Models\Equipo::find($equipoId)->nombre ?? 'Equipo';
        $this->torneoNombre = $torneoNombre ?? 'Torneo Oficial';
        $this->campeonatoId = $campeonatoId;
        $this->fecha = $fecha;
        $this->cancha = $cancha; // 🆕 Agregar propiedad pública en la clase

        $this->cargarJugadores();
    }

    public function cargarJugadores()
    {
        $this->jugadores = CampeonatoJugadorEquipo::with(['jugador'])
            ->where('campeonato_id', $this->campeonatoId)
            ->where('equipo_id', $this->equipoId)
            ->whereNull('fecha_baja')
            ->get()
            ->sortBy(fn($registro) => $registro->jugador->apellido)
            ->values()
            ->map(function ($registro, $index) {
                $jugador = $registro->jugador;

                $sancionActiva = Sanciones::where('jugador_id', $jugador->id)
                    ->where('cumplida', false)
                    ->first();

                $leyenda = '';
                $firma = '';
                $tieneSancion = false;

                if ($sancionActiva) {
                    $tieneSancion = true;
                    $firma = 'SUSPENDIDO';

                    if ($sancionActiva->fecha_inicio && $sancionActiva->fecha_fin) {
                        $leyenda = $this->calcularPeriodoSancion(
                            $sancionActiva->fecha_inicio,
                            $sancionActiva->fecha_fin
                        );
                    } else {
                        $motivo = strtolower(trim($sancionActiva->motivo ?? ''));

                        if (str_contains($motivo, 'amarilla')) {
                            $leyenda = 'PAGA';
                        } else {
                            $pendientes = $sancionActiva->partidos_sancionados - $sancionActiva->partidos_cumplidos;
                            $leyenda = $pendientes . ' FECHAS';
                        }
                    }
                }

                return [
                    'numero' => $index + 1,
                    'dni' => strtoupper($jugador->documento ?? ''),
                    'apellido' => strtoupper($jugador->apellido ?? ''),
                    'nombre' => strtoupper($jugador->nombre ?? ''),
                    'firma' => strtoupper($firma),
                    'sancion' => strtoupper($leyenda),
                    'tieneSancion' => $tieneSancion
                ];
            });
    }

    /*  private function calcularPeriodoSancion($fechaInicio, $fechaFin)
    {
        if (!$fechaInicio || !$fechaFin) return '';

        try {
            $inicio = \Carbon\Carbon::parse($fechaInicio);
            $fin = \Carbon\Carbon::parse($fechaFin);

            // diff() devuelve enteros, diffInYears() puede devolver floats en ciertas versiones
            $diferencia = $inicio->diff($fin);
            $anios = $diferencia->y;
            $meses = $diferencia->m;

            $partes = [];
            if ($anios > 0) $partes[] = $anios . ($anios == 1 ? ' año' : ' años');
            if ($meses > 0) $partes[] = $meses . ($meses == 1 ? ' mes' : ' meses');

            $textoTiempo = !empty($partes) ? implode(' y ', $partes) : 'Menos de 1 mes';

            // Retornamos el tiempo Y las fechas entre paréntesis como en tu imagen
            return $textoTiempo;
        } catch (\Exception $e) {
            return '';
        }
    } */
    private function calcularPeriodoSancion($fechaInicio, $fechaFin)
    {
        if (!$fechaInicio || !$fechaFin) return '';

        try {
            $inicio = \Carbon\Carbon::parse($fechaInicio);
            $fin = \Carbon\Carbon::parse($fechaFin);

            // Usamos diff() para obtener enteros exactos
            $diferencia = $inicio->diff($fin);
            $anios = $diferencia->y;
            $meses = $diferencia->m;

            $partes = [];
            if ($anios > 0) $partes[] = $anios . ($anios == 1 ? ' año' : ' años');
            if ($meses > 0) $partes[] = $meses . ($meses == 1 ? ' mes' : ' meses');

            $textoTiempo = !empty($partes) ? implode(' y ', $partes) : 'Menos de 1 mes';

            // Retornamos el tiempo y el rango de fechas en una línea secundaria pequeña
            return $textoTiempo . "<br><small>(" . $inicio->format('d/m/Y') . " - " . $fin->format('d/m/Y') . ")</small>";
        } catch (\Exception $e) {
            return '';
        }
    }

    public function imprimir()
    {
        $this->dispatch('imprimir-planilla');
    }
    public function render()
    {
        return view('livewire.equipo.imprimir');
    }
}
