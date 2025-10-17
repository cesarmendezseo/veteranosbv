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
    public function mount($campeonatoId)
    {
        $this->campeonatoId = $campeonatoId;
        $this->campeonato = Campeonato::findOrFail($campeonatoId);
    }




    public function render()
    {
        $eliminatorias = Eliminatoria::where('campeonato_id', $this->campeonatoId)
            ->with(['equipoLocal', 'equipoVisitante'])
            ->orderBy('fase')
            ->orderBy('partido_numero')
            ->get();

        $equipos = $eliminatorias->map(function ($p) {
            return [$p->equipoLocal->nombre, $p->equipoVisitante->nombre];
        });

        $resultados = $eliminatorias->map(function ($p) {
            return [$p->goles_local ?? null, $p->goles_visitante ?? null];
        });

        return view('livewire.frontend.fixture.front-eliminatoria-ver', [
            'equipos' => $equipos,
            'resultados' => $resultados,
        ]);
    }
}
