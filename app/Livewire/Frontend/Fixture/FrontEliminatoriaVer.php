<?php

namespace App\Livewire\Frontend\Fixture;

use App\Models\Campeonato;
use App\Models\Eliminatoria;
use Livewire\Component;

class FrontEliminatoriaVer extends Component
{
    public $campeonato;
    public $campeonatoId;
    public $bracketData = [];
    public $data = [];
    public $fases = [];
    public $encuentros = [];
    public $faseElegida;



    public function mount($campeonatoId)
    {
        $this->campeonatoId = $campeonatoId;
        $this->campeonato = Campeonato::findOrFail($campeonatoId);
        // Cargar encuentros por fases
        $this->fases = Eliminatoria::where('campeonato_id', $campeonatoId)
            ->select('fase')
            ->distinct()
            ->pluck('fase');
    }

    public function updatedFaseElegida($fase)
    {

        $this->encuentros = Eliminatoria::where('campeonato_id', $this->campeonatoId)
            ->where('fase', $fase)
            ->with(['equipoLocal', 'equipoVisitante'])
            ->get();
    }





    public function render()
    {


        return view('livewire.frontend.fixture.front-eliminatoria-ver');
    }
}
