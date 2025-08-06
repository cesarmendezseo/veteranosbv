<?php

namespace App\Livewire\Canchas;

use App\Models\Canchas;
use Livewire\Attributes\On;
use Livewire\Component;

class CanchasEditar extends Component
{

    public $canchas;
    public $nombre;
    public $direccion;
    public $canchaSeleccionada;
    public $estadioId;
    public $ciudad;
    public $provincia;
    public $cod_pos;
    public $otros;



    public function mount($estadioId)
    {

        $this->canchaSeleccionada = Canchas::find($estadioId);

        $this->nombre = $this->canchaSeleccionada->nombre;
        $this->direccion = $this->canchaSeleccionada->direccion;
        $this->ciudad = $this->canchaSeleccionada->ciudad;
        $this->provincia = $this->canchaSeleccionada->provincia;
        $this->cod_pos = $this->canchaSeleccionada->cod_pos;
        $this->otros = $this->canchaSeleccionada->otros;
        $this->estadioId = $this->canchaSeleccionada->id;
    }

    public function actualizar()
    {

        $this->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'nullable|string|max:500',
            'ciudad' => 'nullable|string|max:255',
            'provincia' => 'nullable|string|max:255',
            'cod_pos' => 'nullable|string|max:20',
            'otros' => 'nullable|string|max:255',
        ]);



        $this->canchaSeleccionada->update([
            'nombre' => $this->nombre,
            'direccion' => $this->direccion,
            'ciudad' => $this->ciudad,
            'provincia' => $this->provincia,
            'cod_pos' => $this->cod_pos,
            'otros' => $this->otros,
        ]);

        $this->dispatch('editar');
    }

    #[On('return')]
    public function return()
    {
        $this->redirectRoute('canchas.index');
    }

    public function render()
    {
        return view('livewire.canchas.canchas-editar');
    }
}
