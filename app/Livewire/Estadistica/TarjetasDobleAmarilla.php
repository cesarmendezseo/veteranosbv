<?php

namespace App\Livewire\Estadistica;

use App\Models\Campeonato;
use App\Models\EstadisticaJugadorEncuentro;
use Livewire\Component;

class TarjetasDobleAmarilla extends Component
{

    public $campeonatoId;
    public $dobleAmarillas = [];
    public $buscarAmarillas;
    public $jugadorBuscadoAmarilla = [];
    public $search;

    public function mount($campeonatoId = null)
    {
        $this->campeonatoId = $campeonatoId;
        $this->cargarDobleAmarillas();
    }

    public function updatedCampeonatoId()
    {
        $this->cargarDobleAmarillas();
    }

    /**
     * Carga todos los jugadores con doble amarilla del campeonato seleccionado
     */
    public function cargarDobleAmarillas()
    {
        $campeonato = Campeonato::find($this->campeonatoId);

        if (!$campeonato) {
            $this->dobleAmarillas = collect();
            return;
        }

        $formato = $campeonato->formato;
        $tipoModelo = in_array($formato, ['todos_contra_todos', 'grupos'])
            ? \App\Models\Encuentro::class
            : \App\Models\Eliminatoria::class;

        $this->dobleAmarillas = EstadisticaJugadorEncuentro::with(['jugador.equipo', 'estadisticable'])
            ->where('tarjeta_doble_amarilla', '>=', 1)
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

    /**
     * Permite buscar jugadores específicos con doble amarilla
     */
    public function buscarJugadorAmarilla()
    {
        $campeonato = Campeonato::find($this->campeonatoId);

        if (!$campeonato || empty($this->buscarAmarillas)) {
            $this->jugadorBuscadoAmarilla = [];
            return;
        }

        $formato = $campeonato->formato;
        $tipoModelo = in_array($formato, ['todos_contra_todos', 'grupos'])
            ? \App\Models\Encuentro::class
            : \App\Models\Eliminatoria::class;

        $this->jugadorBuscadoAmarilla = EstadisticaJugadorEncuentro::with(['jugador.equipo', 'estadisticable'])
            ->where('tarjeta_doble_amarilla', '>=', 1)
            ->where('estadisticable_type', $tipoModelo)
            ->whereHas('jugador', function ($query) {
                $query->where('documento', 'like', '%' . $this->buscarAmarillas . '%')
                    ->orWhere('apellido', 'like', '%' . $this->buscarAmarillas . '%')
                    ->orWhere('nombre', 'like', '%' . $this->buscarAmarillas . '%');
            })
            ->whereHasMorph('estadisticable', [$tipoModelo], function ($q) use ($campeonato) {
                $q->where('campeonato_id', $campeonato->id);
            })
            ->get();
    }

    public function render()
    {
        return view('livewire.estadistica.tarjetas-doble-amarilla');
    }
}
