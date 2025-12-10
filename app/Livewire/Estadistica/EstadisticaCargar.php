<?php

namespace App\Livewire\Estadistica;

use App\Models\Campeonato;
use Livewire\Component;
use Livewire\WithPagination;

class EstadisticaCargar extends Component
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
            ->where('finalizado', 0)      // 👈 Filtra los NO finalizados
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%')
                        ->orWhereYear('created_at', $this->search);
                });
            })
            ->orderBy('nombre')
            ->paginate(10);

        return view('livewire.estadistica.estadistica-cargar', [
            'campeonatos' => $campeonatos, // Esta variable local es la que tiene la paginación y el filtro.
        ]);
    }
}
