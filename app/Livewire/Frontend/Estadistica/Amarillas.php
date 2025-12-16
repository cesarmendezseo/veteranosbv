<?php

namespace App\Livewire\Frontend\Estadistica;

use App\Models\EstadisticaJugadorEncuentro;
use Livewire\Component;

class Amarillas extends Component
{
    public $tarjetaElegida = 'todas';
    public $campeonatoId;


    public function mount($id)
    {

        $this->campeonatoId = $id;
    }

    public function render()
    {

        return view('livewire.frontend.estadistica.amarillas', [
            'estadisticas' => $this->getEstadisticasFiltradas(),
        ]);
    }


    protected function getEstadisticasFiltradas()
    {
        $query = EstadisticaJugadorEncuentro::query()
            // ===============================================
            // A. JOINS: Unir con jugadores y equipos
            // ===============================================
            ->join('jugadors', 'jugadors.id', '=', 'estadistica_jugador_encuentros.jugador_id')
            ->join('equipos', 'equipos.id', '=', 'estadistica_jugador_encuentros.equipo_id')

            // ===============================================
            // B. SELECT: Seleccionar datos del jugador, equipo y sumar tarjetas
            // ===============================================
            ->selectRaw('
            jugadors.nombre AS nombre_jugador,
            jugadors.apellido AS apellido_jugador,
            equipos.nombre AS nombre_equipo,
            SUM(estadistica_jugador_encuentros.tarjeta_amarilla) AS total_amarilla,
            SUM(estadistica_jugador_encuentros.tarjeta_doble_amarilla) AS total_doble,
            SUM(estadistica_jugador_encuentros.tarjeta_roja) AS total_roja
        ')

            // ===============================================
            // C. FILTRO FIJO POR CAMPEONATO
            // ===============================================
            ->where('estadistica_jugador_encuentros.campeonato_id', $this->campeonatoId);

        // ===============================================
        // D. FILTRO INTERACTIVO POR TARJETA (del paso anterior)
        // ===============================================
        if ($this->tarjetaElegida != 'todas') {
            $columna = 'tarjeta_' . $this->tarjetaElegida;

            // Filtra solo los encuentros donde el jugador recibió el tipo de tarjeta seleccionado
            if (in_array($columna, ['tarjeta_amarilla', 'tarjeta_doble_amarilla', 'tarjeta_roja'])) {
                $query->where("estadistica_jugador_encuentros.{$columna}", '>', 0);
            }
        } else {
            // Caso 'TODAS' las tarjetas: Filtro OR
            $query->where(function ($q) {
                $q->where('estadistica_jugador_encuentros.tarjeta_amarilla', '>', 0)
                    ->orWhere('estadistica_jugador_encuentros.tarjeta_roja', '>', 0)
                    ->orWhere('estadistica_jugador_encuentros.tarjeta_doble_amarilla', '>', 0);
            });
        }

        // ===============================================
        // E. GROUP BY: Debe agrupar por todas las columnas NO AGREGADAS
        // ===============================================
        $query->groupBy([
            'jugadors.nombre',
            'jugadors.apellido',
            'equipos.nombre',
        ]);

        // Opcional: ordenar por total de tarjetas o por apellido
        return $query->orderBy('jugadors.apellido')->get();
    }
}
