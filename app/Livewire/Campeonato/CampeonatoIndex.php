<?php

namespace App\Livewire\Campeonato;

use App\Models\Campeonato;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
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
        $campeonato = Campeonato::find($id);

        if (!$campeonato) {

            LivewireAlert::title('Error')
                ->text('El campeonato no existe.')
                ->error()
                ->toast()
                ->position('top')
                ->show();
            return;
        }

        // Verifico si tiene equipos asignados
        if ($campeonato->equipos()->exists()) {
            LivewireAlert::title('Error')
                ->text('No se puede eliminar el campeonato porque tiene equipos asignados..')
                ->error()
                ->toast()
                ->position('top')
                ->show();

            return;
        }

        $campeonato->delete();

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
