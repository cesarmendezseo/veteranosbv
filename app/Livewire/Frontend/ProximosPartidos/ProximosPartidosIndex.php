<?php

namespace App\Livewire\Frontend\ProximosPartidos;

use App\Models\Eliminatoria;
use App\Models\Encuentro;
use Livewire\Component;

class ProximosPartidosIndex extends Component
{
    public $campeonatoId;
    public $jornadaSeleccionada = '';
    public $jornadasDisponibles = [];

    public function mount($campeonatoId)
    {
        $this->campeonatoId = $campeonatoId;

        // Cargamos jornadas de partidos en AMBOS estados
        $this->jornadasDisponibles = Encuentro::where('campeonato_id', $this->campeonatoId)
            ->where(function ($q) {
                $q->where('estado', 'like', 'programado')
                    ->orWhere('estado', 'like', 'por_programar');
            })
            ->whereNotNull('fecha_encuentro')
            ->distinct()
            ->orderBy('fecha_encuentro', 'asc')
            ->pluck('fecha_encuentro');

        if ($this->jornadaSeleccionada == '' && $this->jornadasDisponibles->count() > 0) {
            $this->jornadaSeleccionada = $this->jornadasDisponibles->first();
        }
    }

    public function render()
    {
        // Traemos encuentros asegurando que incluya ambos estados (Case Insensitive)
        $proximos = Encuentro::with(['equipoLocal', 'equipoVisitante', 'campeonato', 'cancha'])
            ->leftJoin('canchas', 'encuentros.cancha_id', '=', 'canchas.id') // Unimos con canchas
            ->select('encuentros.*') // Evitamos que los campos de canchas sobreescriban al encuentro
            ->where('campeonato_id', $this->campeonatoId)
            ->where(function ($query) {
                $query->where('estado', 'like', 'programado')
                    ->orWhere('estado', 'like', 'por_programar');
            })
            ->when($this->jornadaSeleccionada, function ($query) {
                return $query->where('fecha_encuentro', $this->jornadaSeleccionada);
            })
            // ORDEN PRIORITARIO:
            ->orderBy('canchas.nombre', 'asc') // 1. Por nombre de cancha
            ->orderBy('fecha', 'asc')          // 2. Por fecha
            ->orderBy('hora', 'asc')           // 3. Por hora
            ->get();

        $proximosEliminatorias = Eliminatoria::with(['equipoLocal', 'equipoVisitante', 'campeonato'])
            ->where('campeonato_id', $this->campeonatoId)
            ->where(function ($q) {
                $q->where('estado', 'like', 'programado')
                    ->orWhere('estado', 'like', 'por_programar');
            })
            ->orderBy('fecha', 'asc')
            ->get();

        return view('livewire.frontend.proximos-partidos.proximos-partidos-index', [
            'proximos' => $proximos,
            'proximosEliminatorias' => $proximosEliminatorias
        ]);
    }
}
