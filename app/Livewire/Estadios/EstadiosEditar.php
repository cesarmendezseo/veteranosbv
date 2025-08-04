<?php

namespace App\Livewire\Estadios;

use App\Models\Estadio;
use Livewire\Attributes\On;
use Livewire\Component;

class EstadiosEditar extends Component
{

    public $estadios;
    public $nombre;
    public $direccion;
    public $estadioSeleccionada;
    public $estadioId;
    public $ciudad;
    public $provincia;
    public $cod_pos;
    public $otros;



    public function mount($estadioId)
    {

        $this->estadioSeleccionada = Estadio::find($estadioId);

        $this->nombre = $this->estadioSeleccionada->nombre;
        $this->direccion = $this->estadioSeleccionada->direccion;
        $this->ciudad = $this->estadioSeleccionada->ciudad;
        $this->provincia = $this->estadioSeleccionada->provincia;
        $this->cod_pos = $this->estadioSeleccionada->cod_pos;
        $this->otros = $this->estadioSeleccionada->otros;
        $this->estadioId = $this->estadioSeleccionada->id;
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



        $this->estadioSeleccionada->update([
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
        $this->redirectRoute('estadios.index');
    }

    public function render()
    {
        return view('livewire.estadios.estadios-editar');
    }
}
