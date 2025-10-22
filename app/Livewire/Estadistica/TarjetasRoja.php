<?php

namespace App\Livewire\Estadistica;

use App\Models\Campeonato;
use App\Models\EstadisticaJugadorEncuentro;
use Livewire\Component;

class TarjetasRoja extends Component
{
    public $campeonatoId;
    public $rojas = [];
    public $buscarRoja;
    public $jugadorBuscado = [];
    public $search;

    public function mount($campeonatoId = null)
    {
        $this->campeonatoId = $campeonatoId;
        $this->cargarRojas();
    }

    public function updatedCampeonatoId()
    {
        $this->cargarRojas();
    }

    public function cargarRojas()
    {
        $campeonato = Campeonato::find($this->campeonatoId);

        if (!$campeonato) {
            $this->rojas = collect();
            return;
        }

        $formato = $campeonato->formato;
        $tipoModelo = in_array($formato, ['todos_contra_todos', 'grupos'])
            ? \App\Models\Encuentro::class
            : \App\Models\Eliminatoria::class;

        $this->rojas = EstadisticaJugadorEncuentro::with(['jugador.equipo', 'estadisticable'])
            ->where('tarjeta_roja', '>=', 1)
            ->where('estadisticable_type', $tipoModelo)
            ->whereHasMorph('estadisticable', [$tipoModelo], function ($q) use ($campeonato) {
                $q->where('campeonato_id', $campeonato->id);
            })
            ->orderByDesc('jugador_id')
            ->get();
    }

    public function buscar()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function buscarJugadorRoja()
    {
        $campeonato = Campeonato::find($this->campeonatoId);

        if (!$campeonato || empty($this->buscarRoja)) {
            $this->jugadorBuscado = [];
            return;
        }

        $formato = $campeonato->formato;
        $tipoModelo = in_array($formato, ['todos_contra_todos', 'grupos'])
            ? \App\Models\Encuentro::class
            : \App\Models\Eliminatoria::class;

        $this->jugadorBuscado = EstadisticaJugadorEncuentro::with(['jugador.equipo', 'estadisticable'])
            ->where('tarjeta_roja', '>=', 1)
            ->where('estadisticable_type', $tipoModelo)
            ->whereHas('jugador', function ($query) {
                $query->where('documento', 'like', '%' . $this->buscarRoja . '%')
                    ->orWhere('apellido', 'like', '%' . $this->buscarRoja . '%')
                    ->orWhere('nombre', 'like', '%' . $this->buscarRoja . '%');
            })
            ->whereHasMorph('estadisticable', [$tipoModelo], function ($q) use ($campeonato) {
                $q->where('campeonato_id', $campeonato->id);
            })
            ->get();
    }

    public function render()
    {
        return view('livewire.estadistica.tarjetas-roja');
    }
}
