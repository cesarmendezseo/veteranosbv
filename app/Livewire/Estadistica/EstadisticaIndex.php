<?php

namespace App\Livewire\Estadistica;

use App\Models\Campeonato;
use Livewire\Component;

class EstadisticaIndex extends Component
{
    public $vistaActual = 'goleadores'; // Valor inicial
    public $campeonatos;
    public $campeonatoId;



    public function mount()
    {

        $this->campeonatos = Campeonato::all()->where('finalizado', 0);
    }

    public function updatedCampeonatoId($value)
    {
        $this->vistaActual = 'goleadores';
    }

    public function render()
    {
        return view('livewire.estadistica.estadistica-index');
    }
}
