<?php

namespace App\Livewire\Estadistica;

use App\Models\EstadisticaJugadorEncuentro;
use Livewire\Component;

class Goleador extends Component
{
    public $goleadores;

    public function mount()
    {
        $this->goleadores = EstadisticaJugadorEncuentro::with('jugador')
            ->selectRaw('jugador_id, SUM(goles) as total_goles')
            ->where('goles', '>=', 1)
            ->groupBy('jugador_id')
            ->orderByDesc('total_goles')
            ->limit(20)
            ->get();
    }
    public function render()
    {
        return view('livewire.estadistica.goleador');
    }
}
