<?php

namespace App\Livewire\Estadistica;

use App\Models\EstadisticaJugadorEncuentro;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class CincoAmarillas extends Component
{

    use WithPagination;

    public $campeonatoId;
    public $search = "";

    public function buscar()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $tarjetasAcumuladasPorJugador = EstadisticaJugadorEncuentro::join('encuentros', 'estadistica_jugador_encuentros.encuentro_id', '=', 'encuentros.id')
            ->join('jugadors', 'estadistica_jugador_encuentros.jugador_id', '=', 'jugadors.id')
            ->join('equipos', 'estadistica_jugador_encuentros.equipo_id', '=', 'equipos.id')
            ->where('encuentros.estado', 'jugado')
            ->when($this->campeonatoId, function ($query) {
                $query->where('encuentros.campeonato_id', $this->campeonatoId);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('jugadors.nombre', 'like', '%' . $this->search . '%')
                        ->orWhere('jugadors.apellido', 'like', '%' . $this->search . '%')
                        ->orWhere('jugadors.documento', 'like', '%' . $this->search . '%');
                });
            })
            ->select(
                'jugadors.id as jugador_id',
                'jugadors.nombre as nombre_jugador',
                'jugadors.apellido as apellido_jugador',
                'equipos.nombre as nombre_equipo',
                DB::raw('SUM(estadistica_jugador_encuentros.tarjeta_amarilla) as total_tarjetas_amarillas_acumuladas')
            )
            ->groupBy('jugadors.id', 'jugadors.nombre', 'jugadors.apellido', 'equipos.nombre')
            ->having('total_tarjetas_amarillas_acumuladas', '>=', 5)
            ->orderByDesc('total_tarjetas_amarillas_acumuladas')
            ->paginate(15);

        return view('livewire.estadistica.cinco-amarillas', [
            'tarjetasAcumuladasPorJugador' => $tarjetasAcumuladasPorJugador,
        ]);
    }
}
