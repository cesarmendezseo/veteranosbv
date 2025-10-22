<?php

namespace App\Livewire\Estadistica;

use App\Models\Campeonato;
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

        // Obtenemos el campeonato seleccionado
        $campeonato = Campeonato::find($this->campeonatoId);

        if (!$campeonato) {
            $this->amarillas = collect();
            return;
        }

        if ($campeonato->formato === 'todos_contra_todos' || $campeonato->formato === 'grupos') {
            $this->amarillas = EstadisticaJugadorEncuentro::with(['jugador.equipo', 'encuentro'])
                ->where('tarjeta_amarilla', '>=', 1)
                ->whereHas('jugador', function ($query) {
                    // Opcional: si querés búsqueda por jugador
                    if (!empty($this->buscarAmarillas)) {
                        $query->where('documento', 'like', '%' . $this->buscarAmarillas . '%')
                            ->orWhere('apellido', 'like', '%' . $this->buscarAmarillas . '%')
                            ->orWhere('nombre', 'like', '%' . $this->buscarAmarillas . '%');
                    }
                })
                ->whereHas('encuentro', function ($q) use ($campeonato) {
                    // Filtramos según el formato
                    if (in_array($campeonato->formato, ['todos_contra_todos', 'grupos'])) {
                        $q->where('campeonato_id', $campeonato->id);
                    } elseif (in_array($campeonato->formato, ['eliminacion_simple', 'eliminacion_doble'])) {
                        $q->where('campeonato_id', $campeonato->id);
                        // Si querés, podés filtrar por fase específica aquí
                    }
                })
                ->orderByDesc('jugador_id')
                ->get();
        } else {
            $this->amarillas = EstadisticaJugadorEncuentro::with([
                'jugador.equipo',
                'encuentro.eliminatoria' // <- relación agregada
            ])
                ->where('tarjeta_amarilla', '>=', 1)
                ->whereHas('jugador', function ($query) {
                    if (!empty($this->buscarAmarillas)) {
                        $query->where('documento', 'like', '%' . $this->buscarAmarillas . '%')
                            ->orWhere('apellido', 'like', '%' . $this->buscarAmarillas . '%')
                            ->orWhere('nombre', 'like', '%' . $this->buscarAmarillas . '%');
                    }
                })
                ->whereHas('encuentro', function ($q) use ($campeonato) {
                    $q->where('campeonato_id', $campeonato->id);
                })
                ->orderByDesc('jugador_id')
                ->get();
            dd($this->amarillas);
        }
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

        $this->jugadorBuscadoAmarilla = EstadisticaJugadorEncuentro::with(['jugador.equipo', 'encuentro', 'eliminatoria'])
            ->where('tarjeta_amarilla', '>=', 1)
            ->whereHas('jugador', function ($query) {
                $query->where('documento', 'like', '%' . $this->buscarAmarillas . '%')
                    ->orWhere('apellido', 'like', '%' . $this->buscarAmarillas . '%')
                    ->orWhere('nombre', 'like', '%' . $this->buscarAmarillas . '%');
            })
            ->when($this->campeonatoId, function ($query) use ($campeonato) {
                if (in_array($campeonato->formato, ['todos_contra_todos', 'grupos'])) {
                    $query->whereHas('encuentro', function ($q) use ($campeonato) {
                        $q->where('campeonato_id', $campeonato->id);
                    });
                } elseif (in_array($campeonato->formato, ['eliminacion_simple', 'eliminacion_doble'])) {
                    $query->whereHas('eliminatoria', function ($q) use ($campeonato) {
                        $q->where('campeonato_id', $campeonato->id);
                    });
                }
            })
            ->get();
        dd($this->jugadorBuscadoAmarilla);
    }


    public function render()
    {
        return view('livewire.estadistica.tarjetas-amarilla');
    }
}
