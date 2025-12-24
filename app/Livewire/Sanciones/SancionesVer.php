<?php

namespace App\Livewire\Sanciones;

use App\Models\Campeonato;
use App\Models\Sanciones;
use Livewire\Component;
use Livewire\WithPagination;

class SancionesVer extends Component
{
    use WithPagination;

    public $jugador_id;
    public $campeonato_id;
    public $search = '';

    public function mount($campeonatoId)
    {
        $this->campeonato_id = $campeonatoId;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $sanciones = Sanciones::query()
            ->with([
                'jugador',
                'sancionable.equipoLocal',
                'sancionable.equipoVisitante'
            ])
            /* ->where('campeonato_id', $this->campeonato_id) */
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

        return view('livewire.sanciones.sanciones-ver', [
            'sanciones' => $sanciones,
        ]);
    }
}
