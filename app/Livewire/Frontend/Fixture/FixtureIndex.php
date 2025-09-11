<?php

namespace App\Livewire\Frontend\Fixture;

use App\Models\Campeonato;
use Livewire\Component;

class FixtureIndex extends Component
{
    public $campeonatos;
    public $campeonatoSeleccionado;

    public function mount()
    {
        $this->campeonatos = Campeonato::with('grupos', 'categoria')->get();
    }

    public function render()
    {
        return view('livewire.frontend.fixture.fixture-index');
    }
}
