<?php

namespace App\Livewire\Frontend\ProximosPartidos;

use App\Models\Encuentro;
use Carbon\Carbon;
use Livewire\Component;

class ProximosPartidosIndex extends Component
{
    public $proximos = [];

    public function mount()
    {
        $hoy = Carbon::now();

        $this->proximos = Encuentro::with(['equipoLocal', 'equipoVisitante'])
            ->whereDate('fecha', '>=', $hoy)   // desde hoy en adelante
            ->orderBy('fecha', 'asc')
            ->take(15) // opcional: limita a los próximos 5 partidos
            ->get();
    }
    public function render()
    {
        return view('livewire.frontend.proximos-partidos.proximos-partidos-index');
    }
}
