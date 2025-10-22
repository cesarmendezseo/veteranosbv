<?php

namespace App\Livewire\Estadistica;

use App\Models\EstadisticaJugadorEncuentro;
use Livewire\Component;

class Goleador extends Component
{
    public $goleadores;
    public $campeonatoId;



    public function mount($campeonatoId)
    {
        $this->campeonatoId = $campeonatoId;
    }
    public function render()
    {
        $this->goleadores = EstadisticaJugadorEncuentro::with('jugador')
            ->selectRaw('jugador_id, SUM(goles) as total_goles')
            ->where('goles', '>=', 1)
            ->where('campeonato_id', $this->campeonatoId) // 👈 filtra por campeonato
            ->groupBy('jugador_id')
            ->orderByDesc('total_goles')
            ->limit(20)
            ->get();
        return view('livewire.estadistica.goleador');
    }
}
