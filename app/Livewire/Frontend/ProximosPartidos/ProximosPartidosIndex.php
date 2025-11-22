<?php

namespace App\Livewire\Frontend\ProximosPartidos;

use App\Models\Configuracion;
use App\Models\Eliminatoria;
use App\Models\Encuentro;
use Carbon\Carbon;
use Livewire\Component;

class ProximosPartidosIndex extends Component
{
    public $proximos = [];
    public $proximosEliminatorias = [];
    public $campeonatoId;

    public function mount()
    {

        $campeonatoId = Configuracion::get('campeonato_principal');

        $hoy = Carbon::now();

        $this->proximos = Encuentro::with(['equipoLocal', 'equipoVisitante'])
            ->where('campeonato_id', $campeonatoId)   // 👈 FILTRA POR CAMPEONATO
            ->whereDate('fecha', '>=', $hoy)
            ->orderBy('fecha', 'asc')
            ->take(15)
            ->get();


        $this->proximosEliminatorias = Eliminatoria::with(['equipoLocal', 'equipoVisitante'])
            ->where('campeonato_id', $campeonatoId)   // 👈 FILTRA POR CAMPEONATO
            ->where('estado', 'programado')
            ->whereDate('fecha', '>=', $hoy)
            ->orderBy('fecha', 'asc')
            ->take(15)
            ->get();
        /* dd($this->proximosEliminatorias); */
    }
    public function render()
    {
        return view('livewire.frontend.proximos-partidos.proximos-partidos-index');
    }
}
