<?php

namespace App\Livewire\Frontend\Estadistica;

use App\Models\Configuracion;
use App\Models\Sanciones as ModelsSanciones;
use Livewire\Component;
use Livewire\WithPagination;

class Sanciones extends Component
{
    use WithPagination;

    public $jugador_id;
    public $campeonato_id;
    public $search = '';
    public $campeonatoId;

    public function mount()
    {
        $this->campeonatoId = Configuracion::get('campeonato_principal');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $sanciones = ModelsSanciones::query()
            ->with([
                'jugador',
                'sancionable.equipoLocal',
                'sancionable.equipoVisitante'
            ])
            ->where('campeonato_id', $this->campeonatoId)
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

        return view('livewire.frontend.estadistica.sanciones', [
            'sanciones' => $sanciones,
        ]);
    }
}
