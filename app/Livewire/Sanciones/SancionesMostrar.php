<?php

namespace App\Livewire\Sanciones;

use App\Models\Campeonato;
use Livewire\Component;
use App\Models\Sanciones;
use Livewire\WithPagination;

class SancionesMostrar extends Component
{
    use WithPagination;

    public $jugador_id;
    public $campeonato_id = '';
    public $search = '';
    public $soloPendientes = false;


    // Resetear página al cambiar filtros
    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function updatedCampeonatoId()
    {
        $this->resetPage();
    }
    public function updatedSoloPendientes()
    {
        $this->resetPage();
    }

    public function render()
    {
        $campeonatos = Campeonato::orderBy('nombre', 'asc')->get();

        $sanciones = Sanciones::query()
            ->with(['jugador', 'campeonato', 'sancionable.equipoLocal', 'sancionable.equipoVisitante'])

            // 1. Filtro por Campeonato
            ->when($this->campeonato_id, function ($query) {
                $query->where('campeonato_id', $this->campeonato_id);
            })

            // 2. Filtro: Solo los que aún deben fechas
            ->when($this->soloPendientes, function ($query) {
                $query->whereColumn('partidos_cumplidos', '<', 'partidos_sancionados');
            })

            // 3. Filtro por Búsqueda de texto
            ->when($this->search, function ($query) {
                $query->whereHas('jugador', function ($subQuery) {
                    $subQuery->where(function ($q) {
                        $q->where('apellido', 'like', '%' . $this->search . '%')
                            ->orWhere('nombre', 'like', '%' . $this->search . '%');
                    })->orWhere('documento', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('etapa_sancion', 'desc')
            ->paginate(20);

        return view('livewire.sanciones.sanciones-mostrar', [
            'sanciones' => $sanciones,
            'campeonatos' => $campeonatos,
        ]);
    }
}
