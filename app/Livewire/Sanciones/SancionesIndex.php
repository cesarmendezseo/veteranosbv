<?php

namespace App\Livewire\Sanciones;

use Livewire\Component;

class SancionesIndex extends Component
{
    public $vistaActual = 'ver';

    public function render()
    {
        return view('livewire.sanciones.sanciones-index');
    }
}
