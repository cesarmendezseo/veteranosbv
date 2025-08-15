<?php

namespace App\Livewire\Campeonato;

use App\Models\Campeonato;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class CampeonatoIndex extends Component
{
    use WithPagination;

    public $campeonatoSeleccionado;
    public $search = '';
    protected $listeners = ['refresh' => '$refresh'];

    // Cada vez que se actualiza search, resetea la página
    public function updatingSearch()
    {
        $this->resetPage();
    }

    #[On('eliminar-campeonato')]
    public function eliminarCampeonato($id)
    {
        Campeonato::findOrFail($id)->delete();
        $this->dispatch('baja');
        $this->dispatch('refresh');
    }

    public function crear()
    {
        return redirect()->route('campeonato.crear');
    }

    public function verCampeonato($campeonatoId)
    {
        $this->campeonatoSeleccionado = Campeonato::with('grupos', 'criterioDesempate')->find($campeonatoId);
        $this->dispatch('static-modal');
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

        return view('livewire.campeonato.campeonato-index', [
            'campeonatos' => $campeonatos,
        ]);
    }
}
