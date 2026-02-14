<?php

namespace App\Livewire\Frontend;

use App\Models\Encuentro; // Asegúrate de que el modelo sea Encuentro
use Livewire\Component;

class Resultados extends Component
{
    public $jornadaSeleccionada;

    public function mount()
    {
        // Buscamos la primera jornada (la menor)
        $primeraJornada = Encuentro::min('fecha_encuentro');

        // Asignamos la primera jornada al cargar la página
        $this->jornadaSeleccionada = $primeraJornada ?? 'Fecha 1';
    }

    public function setJornada($jornada)
    {
        $this->jornadaSeleccionada = $jornada;
    }

    public function render()
    {
        // Ordenamos de menor a mayor para que los botones salgan en orden (1, 2, 3...)
        $jornadasDisponibles = Encuentro::select('fecha_encuentro')
            ->distinct()
            ->orderBy('fecha_encuentro', 'asc')
            ->pluck('fecha_encuentro');

        $resultados = Encuentro::with(['equipoLocal', 'equipoVisitante', 'cancha'])
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
