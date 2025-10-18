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

        // Cargar todas las fases disponibles
        $this->fases = Eliminatoria::where('campeonato_id', $campeonatoId)
            ->orderBy('estado')
            ->pluck('fase')
            ->unique()
            ->values();

        // Verificar si la fase "3er y 4to" está programada
        $faseTercerCuarto = Eliminatoria::where('campeonato_id', $campeonatoId)
            ->where('fase', '3er y 4to')
            ->where('estado', 'Programado')
            ->exists();

        // Cargar encuentros según condición
        if ($faseTercerCuarto) {
            $this->faseElegida = '3er y 4to';

            $this->encuentros = Eliminatoria::where('campeonato_id', $campeonatoId)
                ->whereIn('fase', ['3er y 4to', 'Final'])
                ->with(['equipoLocal', 'equipoVisitante'])
                ->get();
        } else {
            $this->faseElegida = 'Final';

            $this->encuentros = Eliminatoria::where('campeonato_id', $campeonatoId)
                ->where('fase', 'Final')
                ->with(['equipoLocal', 'equipoVisitante'])
                ->get();
        }
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
