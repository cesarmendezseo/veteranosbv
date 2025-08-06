<?php

namespace App\Livewire\Canchas;

use App\Models\Canchas;
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
        Canchas::findOrFail($id)->delete();

        $this->dispatch('refresh');
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
