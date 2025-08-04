<?php

namespace App\Livewire\Estadios;

use App\Models\Estadio;
use Livewire\Attributes\On;
use Livewire\Component;

class EstadiosIndex extends Component
{


    public $estadios;
    public $nombre;
    public $direccion;
    public $estadioSeleccionada;
    public $estadioId;
    public $provincia;
    public $cod_pos;
    public $otros;


    public function mount()
    {
        $this->estadios = Estadio::all();
    }

    public function crear()
    {
        return redirect()->route('estadios.crear');
    }


    public function verEstadio($estadioId)
    {
        $this->estadioSeleccionada = \App\Models\Estadio::find($estadioId);
        $this->dispatch('static-modal');
    }


    #[On('eliminar-estadio')]
    public function eliminarEstadio($id)
    {
        Estadio::findOrFail($id)->delete();

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
        return view('livewire.estadios.estadios-index');
    }
}
