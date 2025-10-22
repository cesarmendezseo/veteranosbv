<?php

namespace App\Livewire\Estadistica;

use App\Models\EstadisticaJugadorEncuentro;
use Livewire\Component;

class TarjetasDobleAmarilla extends Component
{
    public $campeonatoId;
    public $dobleAmarillas = [];
    public $buscarAmarillas;
    public $jugadorBuscadoAmarilla = [];
    public $search;

    public function mount($campeonatoId = null)
    {
        $this->campeonatoId = $campeonatoId;
        $this->cargarDobleAmarillas();
    }

    public function updatedCampeonatoId()
    {
        $this->cargarDobleAmarillas();
    }

    /**
     * Carga todos los jugadores con doble amarilla del campeonato seleccionado
     */
    public function cargarDobleAmarillas()
    {
        $this->dobleAmarillas = EstadisticaJugadorEncuentro::with('jugador')
            ->where('tarjeta_doble_amarilla', '>=', 1)
            ->when($this->campeonatoId, fn($q) => $q->where('campeonato_id', $this->campeonatoId))
            ->orderByDesc('jugador_id')
            ->get();
    }

    /**
     * Reinicia la paginación (si usás paginación)
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
     * Permite buscar jugadores específicos con doble amarilla
     */
    public function buscarJugadorAmarilla()
    {
        if (!empty($this->buscarAmarillas)) {
            $this->jugadorBuscadoAmarilla = EstadisticaJugadorEncuentro::with('jugador')
                ->whereHas('jugador', function ($query) {
                    $query->where('documento', 'like', '%' . $this->buscarAmarillas . '%')
                        ->orWhere('apellido', 'like', '%' . $this->buscarAmarillas . '%')
                        ->orWhere('nombre', 'like', '%' . $this->buscarAmarillas . '%');
                })
                ->where('tarjeta_doble_amarilla', '>=', 1)
                ->when($this->campeonatoId, function ($query) {
                    $query->whereHas('encuentro', function ($q) {
                        $q->where('campeonato_id', $this->campeonatoId);
                    });
                })
                ->get();
        } else {
            $this->jugadorBuscadoAmarilla = [];
        }
    }

    public function render()
    {
        return view('livewire.estadistica.tarjetas-doble-amarilla');
    }
}
