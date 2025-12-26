<?php

namespace App\Livewire\Sanciones;

use App\Models\Campeonato;
use Livewire\Component;
use Livewire\WithPagination;

class SancionesIndex extends Component
{
    use WithPagination; // ⬅️ DEBES USARLO

    public $vistaActual = 'ver';
    public $campeonatoId;

    public $search = '';
    protected $listeners = ['refresh' => '$refresh'];




    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $campeonatos = Campeonato::query()
            ->where('finalizado', 0)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%')
                        ->orWhereYear('created_at', $this->search);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.sanciones.sanciones-index', [
            'campeonatos' => $campeonatos, // Esta variable local es la que tiene la paginación y el filtro.
        ]);
    }
}
