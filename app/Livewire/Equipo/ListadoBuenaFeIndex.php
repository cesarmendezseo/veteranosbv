<?php

namespace App\Livewire\Equipo;

use App\Models\Campeonato;
use Livewire\Component;

class ListadoBuenaFeIndex extends Component
{
    public $campeonatos;
    public $campeonatoSeleccionado;

    public function mount()
    {
        $this->campeonatos = Campeonato::with('grupos', 'categoria')->get();
    }
    public function render()
    {
        return view('livewire.equipo.listado-buena-fe-index');
    }
}
