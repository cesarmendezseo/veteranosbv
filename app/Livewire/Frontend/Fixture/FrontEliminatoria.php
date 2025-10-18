<?php

namespace App\Livewire\Frontend\Fixture;

use App\Models\Campeonato;
use Livewire\Component;

class FrontEliminatoria extends Component
{

    public $campeonatos;
    public $campeonatoSeleccionado;

    public function mount()
    {
        //$this->campeonatos = Campeonato::with('grupos', 'categoria')->get();
        $this->campeonatos = Campeonato::whereIn('formato', ['eliminacion_simple', 'eliminacion_doble'])->get();
    }

    public function render()
    {
        return view('livewire.frontend.fixture.front-eliminatoria');
    }
}
