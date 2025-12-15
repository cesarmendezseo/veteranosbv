<?php

namespace App\Livewire\Frontend\Estadistica;

use App\Models\Configuracion;
use App\Models\EstadisticaJugadorEncuentro;
use Livewire\Component;
use Livewire\WithPagination;

class Goleador extends Component
{
    use WithPagination;

    public $campeonatoId;
    public $search = '';
    public $equipoSeleccionado = null;

    public function mount()
    {
        $this->campeonatoId = Configuracion::get('campeonato_principal');
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
        $goleadores = EstadisticaJugadorEncuentro::query()
            ->selectRaw('jugador_id, SUM(goles) as total_goles')
            ->where('goles', '>=', 1)
            ->where('campeonato_id', $this->campeonatoId)

            ->when($this->equipoSeleccionado, function ($q) {
                $q->whereHas('jugador.equiposPorCampeonato', function ($q2) {
                    $q2->where('equipos.id', $this->equipoSeleccionado)
                        ->wherePivot('campeonato_id', $this->campeonatoId);
                });
            })

            ->when($this->search, function ($query) {
                $query->whereHas('jugador', function ($q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%')
                        ->orWhere('apellido', 'like', '%' . $this->search . '%')
                        ->orWhere('documento', 'like', '%' . $this->search . '%');
                });
            })

            ->with([
                'jugador' => function ($q) {
                    $q->with([
                        'equiposPorCampeonato' => function ($q2) {
                            $q2->wherePivot('campeonato_id', $this->campeonatoId);
                        }
                    ]);
                }
            ])

            ->groupBy('jugador_id')
            ->orderByDesc('total_goles')
            ->paginate(20);


        return view('livewire.frontend.estadistica.goleador', [
            'goleadores' => $goleadores,
        ]);
    }
}
