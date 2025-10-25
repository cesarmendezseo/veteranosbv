<?php

namespace App\Livewire\Estadistica;

use App\Models\Campeonato;
use App\Models\Eliminatoria;
use App\Models\Encuentro;
use App\Models\EstadisticaJugadorEncuentro;
use Livewire\Component;
use Livewire\WithPagination;

class TarjetasAmarilla extends Component
{
    use WithPagination;

    public $amarillas;
    public $buscarAmarillas = '';
    public $campeonatoId;
    public $campeonato;

    public function mount($campeonatoId = null)
    {
        $this->campeonatoId = $campeonatoId;
        $this->cargarAmarillas();
    }

    public function updatedBuscarAmarillas()
    {
        $this->resetPage();
        $this->cargarAmarillas();
    }

    public function updatedCampeonatoId()
    {
        $this->resetPage();
        $this->cargarAmarillas();
    }

    public function cargarAmarillas()
    {
        $this->campeonato = Campeonato::find($this->campeonatoId);

        if (!$this->campeonato) {
            $this->amarillas = collect();
            return;
        }

        $formato = $this->campeonato->formato;

        $tipoModelo = in_array($formato, ['todos_contra_todos', 'grupos'])
            ? Encuentro::class
            : Eliminatoria::class;

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
            ->whereHasMorph('estadisticable', [$tipoModelo], function ($q) {
                $q->where('campeonato_id', $this->campeonato->id);
            })
            ->orderByDesc('jugador_id')
            ->get();
    }

    public function render()
    {
        return view('livewire.estadistica.tarjetas-amarilla');
    }
}
