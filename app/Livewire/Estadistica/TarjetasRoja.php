<?php

namespace App\Livewire\Estadistica;

use App\Models\EstadisticaJugadorEncuentro;
use Livewire\Component;

class TarjetasRoja extends Component
{
    public $campeonatoId;
    public $rojas = [];
    public $buscarRoja;
    public $jugadorBuscado = [];
    public $search;

    public function mount($campeonatoId = null)
    {
        $this->campeonatoId = $campeonatoId;
        $this->cargarRojas();
    }

    public function updatedCampeonatoId()
    {
        $this->cargarRojas();
    }

    /**
     * Carga todas las tarjetas rojas del campeonato seleccionado
     */
    public function cargarRojas()
    {
        $this->rojas = EstadisticaJugadorEncuentro::with('jugador')
            ->where('tarjeta_roja', '>=', 1)
            ->when($this->campeonatoId, fn($q) => $q->where('campeonato_id', $this->campeonatoId))
            ->orderByDesc('jugador_id')
            ->get();
    }

    /**
     * Reinicia la paginación (si la usás)
     */
    public function buscar()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Búsqueda por documento, apellido o nombre
     */
    public function buscarJugadorRoja()
    {
        if (!empty($this->buscarRoja)) {
            $this->jugadorBuscado = EstadisticaJugadorEncuentro::with('jugador')
                ->whereHas('jugador', function ($query) {
                    $query->where('documento', 'like', '%' . $this->buscarRoja . '%')
                        ->orWhere('apellido', 'like', '%' . $this->buscarRoja . '%')
                        ->orWhere('nombre', 'like', '%' . $this->buscarRoja . '%');
                })
                ->where('tarjeta_roja', '>=', 1)
                ->when($this->campeonatoId, function ($query) {
                    $query->whereHas('encuentro', function ($q) {
                        $q->where('campeonato_id', $this->campeonatoId);
                    });
                })
                ->get();
        } else {
            $this->jugadorBuscado = [];
        }
    }

    public function render()
    {
        return view('livewire.estadistica.tarjetas-roja');
    }
}
