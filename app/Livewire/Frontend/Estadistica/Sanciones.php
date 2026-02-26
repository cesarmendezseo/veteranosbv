<?php

namespace App\Livewire\Frontend\Estadistica;

use App\Models\Campeonato;
use App\Models\Configuracion;
use App\Models\Eliminatoria;
use App\Models\Encuentro;
use App\Models\Sanciones as ModelsSanciones;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Sanciones extends Component
{
    use WithPagination;

    public $jugador_id;
    public $campeonato_id;
    public $search = '';
    public $campeonatoId;
    public $partidoJugadorInfo = null;
    public $partido_id;
    public $ordenFecha = 'desc'; // desc = más reciente primero
    public $etapaSeleccionada = null;

    public function mount($id)
    {

        $this->campeonatoId = $id;
    }

    public function updatedOrdenFecha()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function setEtapa($etapa)
    {
        $this->etapaSeleccionada = $etapa === $this->etapaSeleccionada ? null : $etapa; // toggle
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function buscarPartidoDelJugador()
    {
        $campeonato = Campeonato::find($this->campeonatoId);

        if (!$campeonato) {
            $this->partidoJugadorInfo = 'Campeonato inválido.';
            return;
        }

        // 🔥 EQUIPO DEL JUGADOR EN ESTE CAMPEONATO
        $equipoId = DB::table('campeonato_jugador_equipo')
            ->where('campeonato_id', $this->campeonatoId)
            ->where('jugador_id', $this->jugador_id)
            ->value('equipo_id');


        if (!$equipoId) {
            $this->partidoJugadorInfo = 'El jugador no pertenece a este campeonato.';
            return;
        }

        $fecha = (int) $this->fechaBuscada;

        if (in_array($campeonato->formato, ['todos_contra_todos', 'grupos'])) {

            $partido = Encuentro::with(['equipoLocal', 'equipoVisitante'])
                ->where('campeonato_id', $this->campeonatoId)
                ->where('fecha_encuentro', $fecha)
                ->where(function ($q) use ($equipoId) {
                    $q->where('equipo_local_id', $equipoId)
                        ->orWhere('equipo_visitante_id', $equipoId);
                })
                ->orderBy('hora')
                ->first();
        } else {

            $partido = Eliminatoria::with(['equipoLocal', 'equipoVisitante'])
                ->where('campeonato_id', $this->campeonatoId)
                ->where('fase', $fecha)
                ->where(function ($q) use ($equipoId) {
                    $q->where('equipo_local_id', $equipoId)
                        ->orWhere('equipo_visitante_id', $equipoId);
                })
                ->first();
        }

        if ($partido) {
            $this->partidoJugadorInfo =
                $partido->equipoLocal->nombre . ' vs ' . $partido->equipoVisitante->nombre;
            $this->partido_id = $partido->id;
        } else {
            $this->partidoJugadorInfo = 'No se encontró partido del jugador en esa fecha.';
        }
    }

    public function render()
    {
        $sanciones = ModelsSanciones::query()
            ->with([
                'jugador',
                'sancionable.equipoLocal',
                'sancionable.equipoVisitante'
            ])
            ->where('campeonato_id', $this->campeonatoId)
            ->when($this->search, function ($query) {
                $query->whereHas('jugador', function ($subQuery) {
                    $subQuery->where(function ($q) {
                        $q->where('apellido', 'like', '%' . $this->search . '%')
                            ->orWhere('nombre', 'like', '%' . $this->search . '%')
                            ->orWhere('documento', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->orderBy('etapa_sancion', $this->ordenFecha)
            ->paginate(20);


        return view('livewire.frontend.estadistica.sanciones', [
            'sanciones' => $sanciones,
        ]);
    }
}
