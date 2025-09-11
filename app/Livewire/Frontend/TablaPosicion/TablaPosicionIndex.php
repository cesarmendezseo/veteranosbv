<?php

namespace App\Livewire\Frontend\TablaPosicion;

use App\Models\Campeonato;
use Livewire\Component;
use Livewire\WithPagination;

class TablaPosicionIndex extends Component
{


    use WithPagination;
    public $search = '';
    protected $listeners = ['refresh' => '$refresh'];

    // Cada vez que se actualiza search, resetea la página
    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function render()
    {
        $campeonatos = Campeonato::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%')
                        ->orWhereYear('created_at', $this->search); // búsqueda por año
                });
            })
            ->orderBy('nombre')
            ->paginate(10);

        return view('livewire.frontend.tabla-posicion.tabla-posicion-index', [
            'campeonatos' => $campeonatos,
        ]);
    }
}
