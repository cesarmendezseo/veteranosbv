<?php

namespace App\Livewire\Estadistica;

use App\Models\Campeonato;
use App\Models\Eliminatoria;
use App\Models\Encuentro;
use App\Models\EstadisticaJugadorEncuentro;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class TarjetasAmarilla extends Component
{
    public $amarillas;
    public $jugadorBuscadoAmarilla;
    public $dni;
    public $buscarAmarillas;
    public $dniJugador;
    public $search;
    public $campeonatoId;
    use WithPagination;


    public function mount($campeonatoId = null)
    {
        $this->campeonatoId = $campeonatoId;
        $this->cargarAmarillas();
    }

    public function updatedCampeonatoId()
    {
        $this->cargarAmarillas();
    }

    public function cargarAmarillas()
    {
        $campeonato = Campeonato::find($this->campeonatoId);

        if (!$campeonato) {
            $this->amarillas = collect();
            return;
        }

        $formato = $campeonato->formato;

        // Definir tipo polimórfico
        $tipoModelo = in_array($formato, ['todos_contra_todos', 'grupos'])
            ? \App\Models\Encuentro::class
            : \App\Models\Eliminatoria::class;

        $this->amarillas = EstadisticaJugadorEncuentro::with(['jugador.equipo', 'estadisticable'])
            ->where('tarjeta_amarilla', '>=', 1)
            ->where('estadisticable_type', $tipoModelo)
            ->whereHas('jugador', function ($query) {
                if (!empty($this->buscarAmarillas)) {
                    $query->where('documento', 'like', '%' . $this->buscarAmarillas . '%')
                        ->orWhere('apellido', 'like', '%' . $this->buscarAmarillas . '%')
                        ->orWhere('nombre', 'like', '%' . $this->buscarAmarillas . '%');
                }
            })
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



    public function buscarJugadorAmarilla()
    {
        if (empty($this->buscarAmarillas)) {
            $this->jugadorBuscadoAmarilla = [];
            return;
        }

        $campeonato = Campeonato::find($this->campeonatoId);

        if (!$campeonato) {
            $this->jugadorBuscadoAmarilla = [];
            return;
        }

        // Detectar tipo de modelo según formato
        $tipoModelo = in_array($campeonato->formato, ['todos_contra_todos', 'grupos'])
            ? Encuentro::class
            : Eliminatoria::class;

        $this->jugadorBuscadoAmarilla = EstadisticaJugadorEncuentro::with(['jugador.equipo', 'estadisticable'])
            ->where('tarjeta_amarilla', '>=', 1)
            ->whereHas('jugador', function ($query) {
                $query->where('documento', 'like', '%' . $this->buscarAmarillas . '%')
                    ->orWhere('apellido', 'like', '%' . $this->buscarAmarillas . '%')
                    ->orWhere('nombre', 'like', '%' . $this->buscarAmarillas . '%');
            })
            ->whereHasMorph('estadisticable', [$tipoModelo], function ($query) use ($campeonato) {
                $query->where('campeonato_id', $campeonato->id);
            })
            ->get();
    }


    public function render()
    {
        return view('livewire.estadistica.tarjetas-amarilla');
    }
}
