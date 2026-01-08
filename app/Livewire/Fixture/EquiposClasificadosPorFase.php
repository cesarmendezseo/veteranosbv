<?php

namespace App\Livewire\Fixture;

use App\Models\Campeonato;
use Livewire\Component;

class EquiposClasificadosPorFase extends Component
{

    public Campeonato $campeonato;

    public function mount(Campeonato $campeonato)
    {
        $this->campeonato = $campeonato->load([
            'fases.equipos',
            'fases.equipos.pivot',
        ]);
    }


    public function render()
    {
        return view('livewire.fixture.equipos-clasificados-por-fase');
    }
}
