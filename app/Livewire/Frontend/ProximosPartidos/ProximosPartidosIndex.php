<?php

namespace App\Livewire\Frontend\ProximosPartidos;

use App\Models\Eliminatoria;
use App\Models\Encuentro;
use Carbon\Carbon;
use Livewire\Component;

class ProximosPartidosIndex extends Component
{
    public $campeonatoId;

    public $jornadaSeleccionada = ''; // Filtro seleccionado
    public $jornadasDisponibles = []; // Lista para el select

    public function mount($campeonatoId)
    {
        $this->campeonatoId = $campeonatoId;

        // 1. Obtenemos todas las jornadas disponibles
        $this->jornadasDisponibles = Encuentro::where('campeonato_id', $this->campeonatoId)
            ->whereNotNull('fecha_encuentro')
            ->distinct()
            ->orderBy('fecha_encuentro', 'asc')
            ->pluck('fecha_encuentro');

        // 2. Establecemos la primera jornada como valor inicial por defecto
        // Si la lista no está vacía, toma la primera; si no, queda vacío.
        if ($this->jornadasDisponibles->count() > 0) {
            $this->jornadaSeleccionada = $this->jornadasDisponibles->first();
        }
    }

    public function render()
    {
        // Consultamos los encuentros filtrando SIEMPRE por la jornada seleccionada
        // Ya no dependemos de "Carbon::now()" porque queremos ver la jornada completa
        $proximos = Encuentro::with(['equipoLocal', 'equipoVisitante', 'campeonato'])
            ->where('campeonato_id', $this->campeonatoId)
            ->when($this->jornadaSeleccionada, function ($query) {
                return $query->where('fecha_encuentro', $this->jornadaSeleccionada);
            })
            ->orderBy('fecha', 'asc')
            ->orderBy('hora', 'asc')
            ->get();

        $proximosEliminatorias = Eliminatoria::with(['equipoLocal', 'equipoVisitante', 'campeonato'])
            ->where('campeonato_id', $this->campeonatoId)
            ->where('estado', 'programado')
            ->orderBy('fecha', 'asc')
            ->get();

        return view('livewire.frontend.proximos-partidos.proximos-partidos-index', [
            'proximos' => $proximos,
            'proximosEliminatorias' => $proximosEliminatorias
        ]);
    }
}
