<?php

namespace App\Livewire\Fixture;

use App\Models\Campeonato;
use App\Models\Canchas;
use App\Models\Encuentro;
use App\Models\Equipo;
use App\Models\Grupo;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\On;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Spatie\Permission\Commands\Show;

class FixtureCrear extends Component
{
    public $campeonatoId;
    public $campeonato;
    public $campeonatoSeleccionado;
    public $campeonato_id;
    public $grupos = [];
    public $equipos = [];
    public $grupo_id;
    public $equipo_local_id;
    public $equipo_visitante_id;
    public $canchas = [];
    public $cancha_id;
    public $fecha;
    public $hora;
    public $estado = 'programado';
    public $jornada;
    public $fase_nombre = 'regular';
    public $fase_seleccionada; // Nueva propiedad para el selector manual
    public $fases_campeonato = [];


    public function mount($campeonatoId)
    {
        $this->campeonatoId = $campeonatoId;
        $this->campeonatoSeleccionado = $campeonatoId;
        $this->canchas = Canchas::all();
        // Cargar las fases disponibles del campeonato
        $this->fases_campeonato = \App\Models\FaseCampeonato::where('campeonato_id', $campeonatoId)
            ->orderBy('orden')
            ->get();

        // Por defecto, apuntamos a la fase actual del campeonato
        $camp = Campeonato::find($campeonatoId);
        $this->fase_seleccionada = $camp->fase_actual_id;
        $this->updatedCampeonatoSeleccionado();
    }


    public function updatedFaseSeleccionada($value)
    {
        $this->fase_seleccionada = $value;

        // Intentamos cargar equipos DESIGNADOS para esa fase
        $equiposEnFase = Equipo::whereHas('fases', function ($q) {
            $q->where('fase_id', $this->fase_seleccionada);
        })->get();

        if ($equiposEnFase->count() > 0) {
            // Si hay clasificados (Oro/Plata), mostramos solo esos
            $this->equipos = $equiposEnFase;
            $this->grupos = [];
        } else {
            // Si no hay designados (Fase Regular), mostramos todos los del campeonato
            $this->equipos = Equipo::whereHas('campeonatos', function ($q) {
                $q->where('campeonato_id', $this->campeonato_id);
            })->get();
        }
    }

    public function cargarEquiposSegunFase()
    {
        if (!$this->fase_seleccionada) return;

        $faseObj = \App\Models\FaseCampeonato::with('equipos')->find($this->fase_seleccionada);

        // Si la fase tiene equipos designados (como Oro/Plata), cargamos esos
        if ($faseObj && $faseObj->equipos->count() > 0) {
            $this->equipos = $faseObj->equipos()->orderBy('nombre')->get();
            $this->grupos = []; // Ocultar grupos si es liguilla
        }
        // Si es la fase regular (orden 1), cargamos la lógica normal
        else {
            if ($this->campeonato->formato === 'grupos') {
                $this->grupos = Grupo::where('campeonato_id', $this->campeonato_id)->get();
                $this->equipos = [];
            } else {
                $this->grupos = [];
                $this->equipos = Equipo::whereHas('campeonatos', function ($query) {
                    $query->where('campeonatos.id', $this->campeonato_id);
                })->orderBy('nombre')->get();
            }
        }
    }

    /* =====================================================
     | LÓGICA DE DESIGNACIÓN (AVANCE DE FASE)
     ===================================================== */

    public function confirmarCierreFase()
    {
        // 1. Buscamos el campeonato
        $campeonato = Campeonato::find($this->campeonato_id);

        if ($campeonato) {
            $exito = $campeonato->avanzarFase();

            if ($exito) {
                // 1. RECARGA MANUAL: Forzamos a Laravel a leer la tabla 'equipos_fase' de nuevo
                $this->campeonato = $campeonato->fresh(['faseActual.equipos']);

                // 2. Sincronizamos la fase seleccionada con la nueva fase (Oro/Plata)
                $this->fase_seleccionada = $this->campeonato->fase_actual_id;

                // Forzamos la carga de equipos desde la tabla pivot
                $fase = \App\Models\FaseCampeonato::find($this->fase_seleccionada);
                $this->equipos = $fase->equipos;
                // 3. Actualizamos la lista de equipos para los selectores del formulario
                if ($this->campeonato->faseActual) {
                    $this->equipos = $this->campeonato->faseActual->equipos()
                        ->orderBy('nombre')
                        ->get();
                }

                // 4. Notificación con LivewireAlert
                LivewireAlert::title('¡Éxito!')
                    ->text('Equipos designados y cargados correctamente.')
                    ->success()
                    ->show();
            } else {
                LivewireAlert::title('Error')
                    ->text('No se pudo procesar la designación.')
                    ->error()
                    ->show();
            }
        }
    }



    /* =====================================================
     | CARGA DINÁMICA DE EQUIPOS
     ===================================================== */

    public function updatedCampeonatoSeleccionado()
    {
        if ($this->campeonatoSeleccionado) {
            $this->campeonato = Campeonato::with(['faseActual.equipos'])->find($this->campeonatoSeleccionado);
            $this->campeonato_id = $this->campeonato->id;

            // 1. Si estamos en Liguilla (Oro/Plata) y hay equipos designados
            if ($this->campeonato->faseActual && $this->campeonato->faseActual->equipos->count() > 0) {
                $this->equipos = $this->campeonato->faseActual->equipos()->orderBy('nombre')->get();
                $this->grupos = [];
                $this->fase_nombre = $this->campeonato->faseActual->nombre;
            }
            // 2. Si es fase de grupos regular
            elseif ($this->campeonato->formato === 'grupos') {
                $this->grupos = Grupo::where('campeonato_id', $this->campeonato_id)->get();
                $this->equipos = [];
            }
            // 3. Si es todos contra todos regular
            else {
                $this->grupos = [];
                $this->equipos = Equipo::whereHas('campeonatos', function ($query) {
                    $query->where('campeonato_id', $this->campeonato_id);
                })->orderBy('nombre')->get();
            }
        }
    }

    public function updatedGrupoId()
    {
        if ($this->grupo_id) {
            $this->equipos = Equipo::whereHas('campeonatos', function ($query) {
                $query->where('campeonato_equipo.campeonato_id', $this->campeonato_id)
                    ->where('campeonato_equipo.grupo_id', $this->grupo_id);
            })->get();
        }
    }

    /* =====================================================
     | VALIDACIONES Y GUARDADO
     ===================================================== */

    public function getEquiposVisitantesProperty()
    {
        return collect($this->equipos)->filter(fn($e) => $e->id != $this->equipo_local_id);
    }

    /*============================================================ 
                        GUARDAR ENCUENTRO
    =============================================================*/

    public function guardarEncuentro()
    {
        $this->validate([
            'fase_seleccionada' => 'required', // Validar que se eligió una fase
            'equipo_local_id' => 'required',
            'campeonato_id' => 'required',
            'fecha' => 'required|date',
            'hora' => 'required',
            'cancha_id' => 'required',
            'equipo_local_id' => 'required',
            'equipo_visitante_id' => 'required|different:equipo_local_id',
            'jornada' => 'required|integer',
        ]);

        // 2. VALIDACIÓN DE DISPONIBILIDAD (Crucial)
        $conflicto = Encuentro::where('fecha', $this->fecha)
            ->where(function ($q) {
                $q->whereIn('equipo_local_id', [$this->equipo_local_id, $this->equipo_visitante_id])
                    ->orWhereIn('equipo_visitante_id', [$this->equipo_local_id, $this->equipo_visitante_id]);
            })
            ->first();

        if ($conflicto) {
            $nombreEquipo = ($conflicto->equipo_local_id == $this->equipo_local_id || $conflicto->equipo_visitante_id == $this->equipo_local_id)
                ? "El equipo local"
                : "El equipo visitante";

            LivewireAlert::title('Conflicto de Equipo')
                ->text("$nombreEquipo ya tiene un partido programado para el día " . date('d/m/Y', strtotime($this->fecha)))
                ->error()
                ->show();
            return; // Detiene el guardado
        }
        // Validación de superposición de cancha
        $inicioNuevo = Carbon::parse($this->fecha . ' ' . $this->hora);
        $finNuevo = $inicioNuevo->copy()->addMinutes(60);

        $conflicto = Encuentro::where('fecha', $this->fecha)
            ->where('cancha_id', $this->cancha_id)
            ->get()
            ->some(function ($enc) use ($inicioNuevo, $finNuevo) {
                $ini = Carbon::parse($enc->fecha . ' ' . $enc->hora);
                $fin = $ini->copy()->addMinutes(60);
                return ($inicioNuevo < $fin && $finNuevo > $ini);
            });

        if ($conflicto) {
            LivewireAlert::title('Error')
                ->text('Cancha ocupada en ese horario.')
                ->error()
                ->show();

            return;
        }
        $faseActualDB = \App\Models\FaseCampeonato::find($this->fase_seleccionada);

        Encuentro::create([
            'campeonato_id' => $this->campeonato_id,
            'grupo_id' => $this->grupo_id,
            'fase_campeonato_id' => $faseActualDB->id,
            'fase' => $faseActualDB->nombre, // Guardamos el nombre de la fase elegida
            'fecha' => $this->fecha,
            'hora' => $this->hora,
            'cancha_id' => $this->cancha_id,
            'estado' => $this->estado,
            'equipo_local_id' => $this->equipo_local_id,
            'equipo_visitante_id' => $this->equipo_visitante_id,
            'fecha_encuentro' => $this->jornada,
        ]);


        LivewireAlert::title('¡Éxito!')
            ->text('Encuentro creado correctamente.')
            ->success()
            ->show();
        $this->reset(['fecha', 'hora', 'equipo_local_id', 'equipo_visitante_id']);
    }

    public function render()
    {
        $encuentros = Encuentro::with(['equipoLocal', 'equipoVisitante', 'cancha'])
            ->where('campeonato_id', $this->campeonato_id)
            // Filtramos por el nombre de la fase actual en lugar del ID
            ->where('fase', $this->campeonato->faseActual->nombre ?? 'regular')
            ->orderBy('fecha_encuentro', 'desc')
            ->orderBy('hora')
            ->get();

        return view('livewire.fixture.fixture-crear', compact('encuentros'));
    }
}
