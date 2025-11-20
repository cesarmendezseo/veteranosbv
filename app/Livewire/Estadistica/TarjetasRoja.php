<?php

namespace App\Livewire\Estadistica;

use App\Models\Campeonato;
use App\Models\Encuentro;
use App\Models\Eliminatoria;
use App\Models\EstadisticaJugadorEncuentro;
use Livewire\Component;
use Livewire\WithPagination;

class TarjetasRoja extends Component
{
    use WithPagination;

    public $campeonatoId;
    public $campeonato;
    public $rojas = [];
    public $buscarRoja = '';
    public $equipoSeleccionado = null; // ✅ nuevo filtro por equipo

    public function mount($campeonatoId = null)
    {
        $this->campeonatoId = $campeonatoId;
        $this->cargarRojas();
    }

    public function getEquiposProperty()
    {
        return \App\Models\Equipo::orderBy('nombre')->get();
    }

    public function updatedCampeonatoId()
    {
        $this->resetPage();
        $this->cargarRojas();
    }

    public function updatedBuscarRoja()
    {
        $this->resetPage();
        $this->cargarRojas();
    }

    public function updatedEquipoSeleccionado()
    {
        $this->resetPage();
        $this->cargarRojas();
    }

    public function cargarRojas()
    {
        $this->campeonato = Campeonato::find($this->campeonatoId);

        if (!$this->campeonato) {
            $this->rojas = collect();
            return;
        }

        $tipoModelo = in_array($this->campeonato->formato, ['todos_contra_todos', 'grupos'])
            ? Encuentro::class
            : Eliminatoria::class;

        $this->rojas = EstadisticaJugadorEncuentro::with(['jugador.equipo', 'estadisticable'])
            ->where('tarjeta_roja', '>=', 1)
            ->where('estadisticable_type', $tipoModelo)
            ->whereHasMorph('estadisticable', [$tipoModelo], function ($q) {
                $q->where('campeonato_id', $this->campeonato->id);
            })
            ->when(
                $this->equipoSeleccionado,
                fn($q) =>
                $q->where('equipo_id', $this->equipoSeleccionado)
            )
            ->when($this->buscarRoja, function ($query) {
                $query->whereHas('jugador', function ($q) {
                    $q->where('documento', 'like', '%' . $this->buscarRoja . '%')
                        ->orWhere('apellido', 'like', '%' . $this->buscarRoja . '%')
                        ->orWhere('nombre', 'like', '%' . $this->buscarRoja . '%');
                });
            })
            ->orderByDesc('jugador_id')
            ->get();
    }

    public function render()
    {
        return view('livewire.estadistica.tarjetas-roja');
    }
}
