<?php

namespace App\Livewire\Frontend;

use App\Models\Encuentro; // Asegúrate de que el modelo sea Encuentro
use Livewire\Component;

class Resultados extends Component
{
    public $jornadaSeleccionada;
    public $campeonatoId;

    public function mount($campeonatoId)
    {
        $this->campeonatoId = $campeonatoId;
        // Buscamos la primera jornada PERO solo de este campeonato
        $primeraJornada = Encuentro::where('campeonato_id', $this->campeonatoId)
            ->min('fecha_encuentro');

        $this->jornadaSeleccionada = $primeraJornada ?? '1';
    }

    public function setJornada($jornada)
    {
        $this->jornadaSeleccionada = $jornada;
    }

    public function render()
    {
        // 1. Botones: Solo jornadas que existan en ESTE campeonato
        $jornadasDisponibles = Encuentro::where('campeonato_id', $this->campeonatoId)
            ->select('fecha_encuentro')
            ->distinct()
            ->orderBy('fecha_encuentro', 'asc')
            ->pluck('fecha_encuentro');

        // 2. Resultados: Filtrados por jornada Y por campeonato
        $resultados = Encuentro::with(['equipoLocal', 'equipoVisitante', 'cancha'])
            ->where('campeonato_id', $this->campeonatoId) // <--- Filtro crítico
            ->where('fecha_encuentro', $this->jornadaSeleccionada)
            ->whereNotNull('gol_local')
            ->orderBy('fecha', 'asc')
            ->get();

        return view('livewire.frontend.resultados', [
            'jornadasDisponibles' => $jornadasDisponibles,
            'resultados' => $resultados
        ]);
    }
}
