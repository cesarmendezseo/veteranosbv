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
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%')
                        ->orWhereYear('created_at', $this->search);
                });
            })
            ->orderBy('nombre')
            ->paginate(10); // ⬅️ 3. APLICAR PAGINACIÓN

        return view('livewire.equipo.listado-buena-fe-index', [
            'campeonatos' => $campeonatos,
        ]);
    }
}
