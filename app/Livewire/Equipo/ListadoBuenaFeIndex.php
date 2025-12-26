<?php

namespace App\Livewire\Equipo;

use App\Models\Campeonato;
use Livewire\Component;
use Livewire\WithPagination;

class ListadoBuenaFeIndex extends Component
{
    use WithPagination;

    public $campeonatoSeleccionado;
    public $campeonatoId;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $campeonatos = Campeonato::query()
            ->where('finalizado', 0) // 🔒 filtro fijo
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%')
                        ->orWhereYear('created_at', $this->search);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.equipo.listado-buena-fe-index', [
            'campeonatos' => $campeonatos,
        ]);
    }
}
