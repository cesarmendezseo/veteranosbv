<?php

namespace App\Livewire\Estadistica;

use App\Models\Campeonato;
use App\Models\Encuentro;
use App\Models\Eliminatoria;
use App\Models\EstadisticaJugadorEncuentro;
use Livewire\Component;
use Livewire\WithPagination;

class TarjetasDobleAmarilla extends Component
{
    use WithPagination;

    public $campeonatoId;
    public $campeonato;
    public $dobleAmarillas = [];
    public $buscarAmarillas = '';

    public function mount($campeonatoId = null)
    {
        $this->campeonatoId = $campeonatoId;
        $this->cargarDobleAmarillas();
    }

    public function updatedCampeonatoId()
    {
        $this->resetPage();
        $this->cargarDobleAmarillas();
    }

    public function updatedBuscarAmarillas()
    {
        $this->resetPage();
        $this->cargarDobleAmarillas();
    }

    public function cargarDobleAmarillas()
    {
        $this->campeonato = Campeonato::find($this->campeonatoId);

        if (!$this->campeonato) {
            $this->dobleAmarillas = collect();
            return;
        }

        $tipoModelo = in_array($this->campeonato->formato, ['todos_contra_todos', 'grupos'])
            ? Encuentro::class
            : Eliminatoria::class;

        $this->dobleAmarillas = EstadisticaJugadorEncuentro::with(['jugador.equipo', 'estadisticable'])
            ->where('tarjeta_doble_amarilla', '>=', 1)
            ->where('estadisticable_type', $tipoModelo)
            ->whereHasMorph('estadisticable', [$tipoModelo], function ($q) {
                $q->where('campeonato_id', $this->campeonato->id);
            })
            ->when($this->buscarAmarillas, function ($query) {
                $query->whereHas('jugador', function ($q) {
                    $q->where('documento', 'like', '%' . $this->buscarAmarillas . '%')
                        ->orWhere('apellido', 'like', '%' . $this->buscarAmarillas . '%')
                        ->orWhere('nombre', 'like', '%' . $this->buscarAmarillas . '%');
                });
            })
            ->orderByDesc('jugador_id')
            ->get();
    }

    public function render()
    {
        return view('livewire.estadistica.tarjetas-doble-amarilla');
    }
}
