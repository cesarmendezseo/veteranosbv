<?php

namespace App\Livewire\Frontend\Estadistica;

use App\Models\EstadisticaJugadorEncuentro;
use Livewire\Component;

class Amarillas extends Component
{
    public $tarjetaElegida = 'todas';
    public $campeonatoId;
    public $search = ''; // 1. Nueva propiedad

    public function mount($id)
    {
        $this->campeonatoId = $id;
    }

    // Opcional: Para resetear filtros si cambias de búsqueda
    public function updatedSearch()
    {
        // Si usas paginación en el futuro, aquí iría $this->resetPage();
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
            ->join('jugadors', 'jugadors.id', '=', 'estadistica_jugador_encuentros.jugador_id')
            ->join('equipos', 'equipos.id', '=', 'estadistica_jugador_encuentros.equipo_id')
            ->selectRaw('
                jugadors.nombre AS nombre_jugador,
                jugadors.apellido AS apellido_jugador,
                equipos.nombre AS nombre_equipo,
                SUM(estadistica_jugador_encuentros.tarjeta_amarilla) AS total_amarilla,
                SUM(estadistica_jugador_encuentros.tarjeta_doble_amarilla) AS total_doble,
                SUM(estadistica_jugador_encuentros.tarjeta_roja) AS total_roja
            ')
            ->where('estadistica_jugador_encuentros.campeonato_id', $this->campeonatoId);

        // ===============================================
        // NUEVO: FILTRO POR JUGADOR (Nombre o Apellido)
        // ===============================================
        $query->when($this->search, function ($q) {
            $q->where(function ($sub) {
                $sub->where('jugadors.nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('jugadors.apellido', 'like', '%' . $this->search . '%');
            });
        });

        // El resto de tu lógica de tarjetas se mantiene intacta
        if ($this->tarjetaElegida != 'todas') {
            $columna = 'tarjeta_' . $this->tarjetaElegida;
            if (in_array($columna, ['tarjeta_amarilla', 'tarjeta_doble_amarilla', 'tarjeta_roja'])) {
                $query->where("estadistica_jugador_encuentros.{$columna}", '>', 0);
            }
        } else {
            $query->where(function ($q) {
                $q->where('estadistica_jugador_encuentros.tarjeta_amarilla', '>', 0)
                    ->orWhere('estadistica_jugador_encuentros.tarjeta_roja', '>', 0)
                    ->orWhere('estadistica_jugador_encuentros.tarjeta_doble_amarilla', '>', 0);
            });
        }

        $query->groupBy([
            'jugadors.nombre',
            'jugadors.apellido',
            'equipos.nombre',
        ]);

        return $query->orderBy('jugadors.apellido')->get();
    }
}
