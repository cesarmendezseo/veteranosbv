<?php

namespace App\Livewire\Estadistica;

use App\Models\Campeonato;
use App\Models\EstadisticaJugadorEncuentro;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class CincoAmarillas extends Component
{
    use WithPagination;

    public $campeonatoId;
    public $search = "";
    public $equipoSeleccionado = null; // ✅ nuevo filtro por equipo

    public function buscar()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedEquipoSeleccionado()
    {
        $this->resetPage();
    }

    public function getEquiposProperty()
    {
        return \App\Models\Equipo::orderBy('nombre')->get();
    }

    public function render()
    {
        $campeonato = Campeonato::find($this->campeonatoId);

        if (!$campeonato) {
            return view('livewire.estadistica.cinco-amarillas', [
                'tarjetasAcumuladasPorJugador' => collect(),
            ]);
        }

        $formato = $campeonato->formato;
        $tipoModelo = in_array($formato, ['todos_contra_todos', 'grupos'])
            ? \App\Models\Encuentro::class
            : \App\Models\Eliminatoria::class;

        $tarjetasAcumuladasPorJugador = EstadisticaJugadorEncuentro::with(['jugador.equipo', 'estadisticable'])
            ->where('estadisticable_type', $tipoModelo)
            ->whereHasMorph('estadisticable', [$tipoModelo], function ($q) use ($campeonato) {
                $q->where('estado', 'jugado')
                    ->where('campeonato_id', $campeonato->id);
            })
            ->when(
                $this->equipoSeleccionado,
                fn($q) =>
                $q->where('equipo_id', $this->equipoSeleccionado)
            )
            ->when($this->search, function ($query) {
                $query->whereHas('jugador', function ($q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%')
                        ->orWhere('apellido', 'like', '%' . $this->search . '%')
                        ->orWhere('documento', 'like', '%' . $this->search . '%');
                });
            })
            ->select(
                'jugador_id',
                DB::raw('SUM(tarjeta_amarilla) as total_tarjetas_amarillas_acumuladas')
            )
            ->groupBy('jugador_id')
            ->having('total_tarjetas_amarillas_acumuladas', '>=', 5)
            ->orderByDesc('total_tarjetas_amarillas_acumuladas')
            ->paginate(15);

        return view('livewire.estadistica.cinco-amarillas', [
            'tarjetasAcumuladasPorJugador' => $tarjetasAcumuladasPorJugador,
        ]);
    }
}
