<?php

namespace App\Livewire\Sanciones;

use Livewire\Component;

class SancionesIndex extends Component
{
    public $vistaActual = 'ver';
    public $campeonatoId;
    public $campeonatos = [];


    public function mount()
    {

        $this->campeonatos = \App\Models\Campeonato::orderBy('created_at', 'desc')->get();
    }


    public function render()
    {
        return view('livewire.sanciones.sanciones-index');
    }
}
