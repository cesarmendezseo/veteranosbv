<?php

namespace App\Livewire\Sanciones;

use App\Models\Campeonato;
use App\Models\Eliminatoria;
use App\Models\Encuentro;
use App\Models\Jugador;
use App\Models\Sanciones;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Illuminate\Database\QueryException;
use Jantinnerezo\LivewireAlert\LivewireAlert as LivewireAlertLivewireAlert;
use Livewire\Component;

class SancionesCrear extends Component
{
    public $jugador_id;
    public $campeonato_id;
    public $campeonatoId;
    public $fecha_sancion;
    public $partidos_sancionados = 1;
    public $nombreJugador;

    public $observacion;
    public $motivo;

    public $jugadores;
    public $campeonatos;
    public $jugadorBuscadoSancion;

    public $buscarJugador;

    public $jugadorSeleccionado = null;

    public $partido_id;
    public $partido_tipo;
    public $partidosDisponibles = [];
    public $fechaBuscada;
    public $opcionesFechaFase = [];
    public $partidoJugadorInfo = null;


    public function mount($campeonatoId)
    {
        $campeonato = Campeonato::find($campeonatoId);

        $this->partidosDisponibles = in_array($campeonato->formato, ['todos_contra_todos', 'grupos'])
            ? Encuentro::where('campeonato_id', $this->campeonatoId)->get()
            : Eliminatoria::with(['equipoLocal', 'equipoVisitante'])->where('campeonato_id', $this->campeonatoId)->get();

        //dd($this->partidosDisponibles);
        $this->partido_tipo = in_array($campeonato->formato, ['todos_contra_todos', 'grupos'])
            ? 'App\Models\Encuentro'
            : 'App\Models\Eliminatoria';

        $this->jugadores = [];
        $this->cargarOpcionesFechaFase();
    }

    //===================================================
    public function cargarOpcionesFechaFase()
    {
        $ordenFases = ['treintaydosavo', 'diesoseisavo', 'octavos', 'cuartos', 'semifinal', '3er y 4to', 'final'];
        $campeonato = Campeonato::find($this->campeonatoId);

        if (in_array($campeonato->formato, ['todos_contra_todos', 'grupos'])) {
            // Cargar fechas únicas de encuentros
            $this->opcionesFechaFase = Encuentro::where('campeonato_id', $this->campeonatoId)
                ->pluck('fecha_encuentro')
                ->unique()
                ->sort()
                ->values()
                ->toArray();
        } else {
            // Cargar fases únicas de eliminatorias
            $this->opcionesFechaFase = Eliminatoria::where('campeonato_id', $this->campeonatoId)
                ->pluck('fase')
                ->unique()
                ->filter()
                ->sortBy(function ($fase) use ($ordenFases) {
                    return array_search(strtolower($fase), $ordenFases);
                })
                ->values()
                ->toArray();
        }
    }

    //=========================================================
    public function buscarJugadorSancion()
    {

        if (!empty($this->buscarJugador)) {

            $buscar = trim($this->buscarJugador);

            $this->jugadores = Jugador::with('equipo')
                ->where(function ($query) use ($buscar) {
                    $palabras = explode(' ', $buscar);

                    foreach ($palabras as $palabra) {
                        $palabra = trim($palabra);
                        if ($palabra !== '') {
                            $query->where(function ($subquery) use ($palabra) {
                                $subquery->where('nombre', 'like', "%{$palabra}%")
                                    ->orWhere('apellido', 'like', "%{$palabra}%")
                                    ->orWhere('documento', 'like', "%{$palabra}%");
                            });
                        }
                    }
                })
                ->get();
        } else {

            $this->jugadores = [];
        }
    }
    //======================================================================

    public function agregarJugador($jugadorId)
    {
        $this->jugadorSeleccionado = $this->jugadores->firstWhere('id', $jugadorId);
        $this->jugador_id = $jugadorId;
        $this->nombreJugador = $this->jugadorSeleccionado->nombreCompleto . ' - ' . $this->jugadorSeleccionado->equipo->nombre;
        $this->buscarJugador = ""; // oculta el listado
    }

    //====================================================================
    public function cargarPartidosPorFecha()
    {
        $campeonato = Campeonato::find($this->campeonatoId);

        if (!$this->fechaBuscada) {
            $this->partidosDisponibles = [];
            return;
        }

        if (in_array($campeonato->formato, ['todos_contra_todos', 'grupos'])) {
            $this->partido_tipo = Encuentro::class;

            $this->partidosDisponibles = Encuentro::with(['equipoLocal', 'equipoVisitante'])
                ->where('campeonato_id', $this->campeonatoId)
                ->where('fecha_encuentro', $this->fechaBuscada)
                ->get();
        } else {
            $this->partido_tipo = Eliminatoria::class;

            $this->partidosDisponibles = Eliminatoria::with(['equipoLocal', 'equipoVisitante'])
                ->where('campeonato_id', $this->campeonatoId)
                ->where('fase', $this->fechaBuscada)
                ->get();
        }
        $this->buscarPartidoDelJugador();
    }
    //==================================================================
    public function buscarPartidoDelJugador()
    {
        $jugador = Jugador::find($this->jugador_id);
        $equipoId = $jugador->equipo_id;

        $campeonato = Campeonato::find($this->campeonatoId);

        if (in_array($campeonato->formato, ['todos_contra_todos', 'grupos'])) {
            $partido = Encuentro::with(['equipoLocal', 'equipoVisitante'])
                ->where('campeonato_id', $this->campeonatoId)
                ->where('fecha_encuentro', $this->fechaBuscada)
                ->where(function ($q) use ($equipoId) {
                    $q->where('equipo_local_id', $equipoId)
                        ->orWhere('equipo_visitante_id', $equipoId);
                })
                ->first();
        } else {
            $partido = Eliminatoria::with(['equipoLocal', 'equipoVisitante'])
                ->where('campeonato_id', $this->campeonatoId)
                ->where('fase', $this->fechaBuscada)
                ->where(function ($q) use ($equipoId) {
                    $q->where('equipo_local_id', $equipoId)
                        ->orWhere('equipo_visitante_id', $equipoId);
                })
                ->first();
        }

        if ($partido) {
            $this->partidoJugadorInfo = $partido->equipoLocal->nombre . ' vs ' . $partido->equipoVisitante->nombre;
            $this->partido_id = $partido->id;
        } else {
            $this->partidoJugadorInfo = 'No se encontró partido del jugador en esa fecha/fase.';
        }
    }
    //======================================================================
    public function guardar()
    {
        try {
            $this->validate([
                'jugador_id' => 'required|exists:jugadors,id',
                'partido_id' => 'required|integer|exists:' . (new $this->partido_tipo)->getTable() . ',id',
                'partidos_sancionados' => 'required|integer|min:1',
                'motivo' => 'required|string',
                'observacion' => 'nullable|string',
                'fechaBuscada' => 'required|string',
            ]);

            $campeonato = Campeonato::find($this->campeonatoId);
            $jugador = Jugador::find($this->jugador_id);
            $equipoJugador = $jugador->equipo_id;

            $partido = $this->partido_tipo::find($this->partido_id);

            // ✅ Validar que el jugador pertenezca al partido
            if (!in_array($equipoJugador, [$partido->equipo_local_id, $partido->equipo_visitante_id])) {
                LivewireAlert::title('Error')
                    ->text('El jugador no pertenece al partido seleccionado')
                    ->error()
                    ->show();
                return;
            }

            // ✅ Validar si el jugador ya tiene una sanción pendiente en este campeonato
            $sancionPendiente = Sanciones::where('jugador_id', $this->jugador_id)
                ->where('campeonato_id', $this->campeonatoId)
                ->whereColumn('partidos_cumplidos', '<', 'partidos_sancionados')
                ->exists();

            if ($sancionPendiente) {
                LivewireAlert::title('Error')
                    ->text('Este jugador ya tiene una sanción pendiente en este campeonato.')
                    ->warning()
                    ->show();
                return;
            }

            // ✅ Crear la sanción si no hay pendientes
            Sanciones::create([
                'jugador_id' => $this->jugador_id,
                'campeonato_id' => $this->campeonatoId,
                'etapa_sancion' => $this->fechaBuscada,
                'partidos_sancionados' => $this->partidos_sancionados,
                'partidos_cumplidos' => 0,
                'cumplida' => false,
                'motivo' => $this->motivo,
                'observacion' => $this->observacion,
                'sancionable_id' => $this->partido_id,
                'sancionable_type' => $this->partido_tipo,
            ]);

            LivewireAlert::title('Éxito')
                ->text('Sanción guardada correctamente.')
                ->success()
                ->show();

            $this->reset([
                'jugador_id',
                'partido_id',
                'partido_tipo',
                'fechaBuscada',
                'partidos_sancionados',
                'motivo',
                'observacion',
                'partidoJugadorInfo',
            ]);

            $this->jugadores = [];
        } catch (QueryException $e) {
            LivewireAlert::title('Error SQL')
                ->text('Ocurrió un error al guardar la sanción: ' . $e->getMessage())
                ->error()
                ->show();
        } catch (\Exception $e) {
            LivewireAlert::title('Error inesperado')
                ->text('Algo salió mal: ' . $e->getMessage())
                ->error()
                ->show();
        }
        $this->jugadorSeleccionado = null;
    }



    //======================================================
    public function actualizarCumplimientosSanciones()
    {
        // Procesar sanciones en bloques para evitar sobrecarga de memoria
        Sanciones::where('cumplida', false)->chunk(50, function ($sanciones) {
            foreach ($sanciones as $sancion) {
                $jugador = $sancion->jugador;

                // Validar que el jugador y su equipo existan
                if (!$jugador || !$jugador->equipo_id) {
                    continue;
                }

                $equipoId = $jugador->equipo_id;

                // Contar partidos jugados posteriores a la fecha de sanción donde participó el equipo
                $partidosCumplidos = Encuentro::where('campeonato_id', $sancion->campeonato_id)
                    ->where('estado', 'Jugado')
                    ->where('fecha_encuentro', '>', $sancion->fecha_sancion)
                    ->where(function ($q) use ($equipoId) {
                        $q->where('equipo_local_id', $equipoId)
                            ->orWhere('equipo_visitante_id', $equipoId);
                    })
                    ->count();

                // Solo guardar si hay cambios
                $cumplida = $partidosCumplidos >= $sancion->partidos_sancionados;

                if (
                    $sancion->partidos_cumplidos !== $partidosCumplidos ||
                    $sancion->cumplida !== $cumplida
                ) {
                    $sancion->partidos_cumplidos = $partidosCumplidos;
                    $sancion->cumplida = $cumplida;
                    $sancion->save();
                }
            }
        });

        // Emitir evento Livewire para actualizar vista si es necesario
        $this->dispatch('actualizar-sancion');
        LivewireAlert::title('ok')
            ->text('Sancion actualizada')
            ->success()
            ->show();
    }

    public function updatedBuscarJugador()
    {
        $this->buscarJugadorSancion();
    }

    //=====================================================
    public function render()
    {
        return view('livewire.sanciones.sanciones-crear');
    }
}
