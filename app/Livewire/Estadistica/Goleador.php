<?php

namespace App\Livewire\Estadistica;

use App\Models\EstadisticaJugadorEncuentro;
use Livewire\Component;
use Livewire\WithPagination;

class Goleador extends Component
{
    use WithPagination;

    public $campeonatoId;
    public $search = '';
    public $equipoSeleccionado = null;

    public function mount($campeonatoId)
    {
        $this->campeonatoId = $campeonatoId;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedEquipoSeleccionado()
    {
        $this->resetPage();
    }

    public function getEquiposProperty()
    {
        return \App\Models\Equipo::orderBy('nombre')->get();
    }

    public function render()
    {
        $goleadores = EstadisticaJugadorEncuentro::with('jugador.equipo')
            ->selectRaw('jugador_id, SUM(goles) as total_goles')
            ->where('goles', '>=', 1)
            ->where('campeonato_id', $this->campeonatoId)
            ->when(
                $this->equipoSeleccionado,
                fn($q) =>
                $q->where('equipo_id', $this->equipoSeleccionado)
            )
            ->when($this->search, function ($query) {
                $query->whereHas('jugador', function ($q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%')
                        ->orWhere('apellido', 'like', '%' . $this->search . '%')
                        ->orWhere('documento', 'like', '%' . $this->search . '%');
                });
            })
            ->groupBy('jugador_id')
            ->orderByDesc('total_goles')
            ->paginate(20);


        return view('livewire.estadistica.goleador', [
            'goleadores' => $goleadores,
        ]);
    }
}
