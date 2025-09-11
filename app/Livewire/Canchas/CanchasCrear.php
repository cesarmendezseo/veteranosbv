<?php

namespace App\Livewire\Canchas;

use App\Models\Canchas;
use Livewire\Attributes\On;
use Livewire\Component;

class CanchasCrear extends Component
{
    public $canchas;
    public $nombre;
    public $direccion;
    public $canchaSeleccionada;
    public $canchaId;
    public $ciudad;
    public $provincia;
    public $cod_pos;
    public $otros;

    public function guardar()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'nullable|string|max:500',
            'ciudad' => 'nullable|string|max:255',
            'provincia' => 'nullable|string|max:255',
            'cod_pos' => 'nullable|string|max:20',
            'otros' => 'nullable|string|max:255',
        ]);

        Canchas::create([
            'nombre' => $this->nombre,
            'direccion' => $this->direccion,
            'ciudad' => $this->ciudad,
            'provincia' => $this->provincia,
            'cod_pos' => $this->cod_pos,
            'otros' => $this->otros,
        ]);

        $this->dispatch('crear');
    }

    #[On('return')]
    public function return()
    {
        return redirect()->route('canchas.index');
        // No hace falta que pongas nada acá, con solo tener este método, Livewire vuelve a renderizar
    }


    public function render()
    {
        return view('livewire.canchas.canchas-crear');
    }
}
