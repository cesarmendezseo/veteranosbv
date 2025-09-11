<?php

namespace App\Livewire\Canchas;

use App\Models\Canchas;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class CanchasIndex extends Component
{



    public $canchas;
    public $nombre;
    public $direccion;
    public $canchaSeleccionada;
    public $canchaId;
    public $provincia;
    public $cod_pos;
    public $otros;


    public function mount()
    {
        $this->canchas = Canchas::all();
    }

    public function crear()
    {
        return redirect()->route('canchas.crear');
    }


    public function verEstadio($canchaId)
    {

        $this->canchaSeleccionada = \App\Models\Canchas::find($canchaId);
        $this->dispatch('static-modal');
    }


    #[On('eliminar-estadio')]
    public function eliminarEstadio($id)
    {
        $cancha = Canchas::find($id);

        if (!$cancha) {
            LivewireAlert::title('Error')
                ->text('La cancha no existe..')
                ->error()
                ->toast()
                ->position('top')
                ->show();

            return;
        }

        // Verificar si la cancha está asociada a encuentros
        if ($cancha->encuentros()->exists()) {
            LivewireAlert::title('Error')
                ->text('No se puede eliminar la cancha porque está asociada a uno o más encuentros...')
                ->error()
                ->toast()
                ->position('top')
                ->show();

            return;
        }

        // Si no está asociada, eliminar
        $cancha->delete();

        $this->dispatch('refresh');

        LivewireAlert::title('Ok')
            ->text('Cancha eliminada correctament')
            ->success()
            ->toast()
            ->position('top')
            ->show();
    }

    #[On('refresh')]
    public function refresh()
    {
        $this->dispatch('baja');
        // No hace falta que pongas nada acá, con solo tener este método, Livewire vuelve a renderizar
    }

    public function render()
    {
        return view('livewire.canchas.canchas-index');
    }
}
