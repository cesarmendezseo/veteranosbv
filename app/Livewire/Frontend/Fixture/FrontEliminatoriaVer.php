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

        // Buscar la primera fase programada
        $faseProgramada = Eliminatoria::where('campeonato_id', $campeonatoId)
            ->where('estado', 'Programado')
            ->orderBy('fase')
            ->value('fase');

        // Si no hay programada, buscar la última jugada
        $this->faseElegida = $faseProgramada ?? Eliminatoria::where('campeonato_id', $campeonatoId)
            ->where('estado', 'Jugado')
            ->orderByDesc('fecha')
            ->value('fase');

        // Si la fase elegida es "Final", verificar si también está programado "3er y 4to"
        if ($this->faseElegida === '3er y 4to') {
            $tercerCuartoProgramado = Eliminatoria::where('campeonato_id', $campeonatoId)
                ->where('fase', '3er y 4to')
                ->where('estado', 'Programado')
                ->exists();

            if ($tercerCuartoProgramado) {
                $this->encuentros = Eliminatoria::where('campeonato_id', $campeonatoId)
                    ->where('estado', 'Programado')
                    ->whereIn('fase', ['Final', '3er y 4to'])
                    ->with(['equipoLocal', 'equipoVisitante'])
                    ->get();
                return;
            } else {
            }
        }

        // Cargar encuentros de la fase elegida
        $this->encuentros = Eliminatoria::where('campeonato_id', $campeonatoId)
            ->where('fase', $this->faseElegida)
            ->with(['equipoLocal', 'equipoVisitante'])
            ->get();
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
