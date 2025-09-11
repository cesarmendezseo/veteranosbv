<?php

namespace App\Livewire\Estadistica;

use Livewire\Component;

class EstadisticaIndex extends Component
{
    public $vistaActual = 'goleadores'; // Valor inicial

    public function render()
    {
        return view('livewire.estadistica.estadistica-index');
    }
}
