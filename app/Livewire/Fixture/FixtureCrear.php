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
    public $libres = [];


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

    /* ======================================================
     | CARGA DINÁMICA DE EQUIPOS POR GRUPO
     ===================================================== */

    public function updatedFaseSeleccionada($value)
    {
        $this->fase_seleccionada = $value;
        $this->cargarEquiposFiltrados();
        /*  $this->fase_seleccionada = $value;

        // 1. Obtener IDs de equipos que ya perdieron en esta fase
        $eliminadosIds = Encuentro::where('fase_id', $this->fase_seleccionada)
            ->whereNotNull('perdedor_id')
            ->pluck('perdedor_id')
            ->toArray();

        // 2. Intentamos cargar equipos DESIGNADOS para esa fase que NO estén eliminados
        $equiposEnFase = Equipo::whereHas('fases', function ($q) {
            $q->where('fase_id', $this->fase_seleccionada);
        })
            ->whereNotIn('id', $eliminadosIds) // <--- ESTE ES EL FILTRO CLAVE
            ->get();

        if ($equiposEnFase->count() > 0) {
            $this->equipos = $equiposEnFase;
            $this->grupos = [];
        } else {
            // Si es fase regular, usualmente no hay perdedores eliminados aún
            $this->equipos = Equipo::whereHas('campeonatos', function ($q) {
                $q->where('campeonato_id', $this->campeonato_id);
            })->get();
        } */
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
            $this->campeonato = Campeonato::find($this->campeonatoSeleccionado);
            $this->campeonato_id = $this->campeonato->id;
            $this->fase_seleccionada = $this->campeonato->fase_actual_id;

            // Cargar grupos si existen
            $this->grupos = Grupo::where('campeonato_id', $this->campeonato_id)->get();

            $this->cargarEquiposFiltrados();
        }
    }
    /* ======================================================
        | CARGA DINÁMICA DE EQUIPOS POR GRUPO
    ===========================================================*/


    public function updatedGrupoId()
    {
        $this->cargarEquiposFiltrados();
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
            'fase_id' => $faseActualDB->id,
            'fase' => $faseActualDB->nombre, // Guardamos el nombre de la fase elegida
            'fecha' => $this->fecha,
            'hora' => $this->hora,
            'cancha_id' => $this->cancha_id,
            'estado' => $this->estado,
            'equipo_local_id' => $this->equipo_local_id,
            'equipo_visitante_id' => $this->equipo_visitante_id,
            'fecha_encuentro' => $this->jornada,
        ]);

        // REFRESCAR LA LISTA DE EQUIPOS DISPONIBLES
        $this->cargarEquiposFiltrados();
        LivewireAlert::title('¡Éxito!')
            ->text('Encuentro creado correctamente.')
            ->success()
            ->show();
        $this->reset(['fecha', 'hora', 'equipo_local_id', 'equipo_visitante_id']);
    }
    /* 
    =====================================================
     | CARGA DE EQUIPOS FILTRADOS (ELIMINADOS)
     =====================================================
    */
    public function cargarEquiposFiltrados()
    {
        if (!$this->fase_seleccionada) return;

        $faseObj = \App\Models\FaseCampeonato::find($this->fase_seleccionada);
        if (!$faseObj) return;

        // 1. Obtener IDs de equipos que ya perdieron EN ESTA FASE
        // (Solo para fases que no sean 'Regular', donde sí hay eliminación directa)
        $eliminadosIds = Encuentro::where('fase_id', $this->fase_seleccionada)
            ->whereNotNull('perdedor_id')
            ->pluck('perdedor_id')
            ->toArray();

        // 2. Si la fase tiene equipos asignados (Oro/Plata/Playoffs)
        if ($faseObj->equipos()->count() > 0) {
            $this->equipos = $faseObj->equipos()
                ->whereNotIn('equipos.id', $eliminadosIds) // Solo los que siguen vivos
                ->orderBy('nombre')
                ->get();
            $this->grupos = [];
        }
        // 3. Si es Fase Regular pero queremos filtrar por grupo
        elseif ($this->grupo_id) {
            $this->equipos = Equipo::whereHas('campeonatos', function ($query) {
                $query->where('campeonato_equipo.campeonato_id', $this->campeonato_id)
                    ->where('campeonato_equipo.grupo_id', $this->grupo_id);
            })
                ->whereNotIn('id', $eliminadosIds)
                ->get();
        }
        // 4. Fase Regular general
        else {
            $this->equipos = Equipo::whereHas('campeonatos', function ($query) {
                $query->where('campeonato_id', $this->campeonato_id);
            })
                ->whereNotIn('id', $eliminadosIds)
                ->orderBy('nombre')
                ->get();
        }
    }

    /* =====================================================
     | BÚSQUEDA DE EQUIPOS LIBRES POR FECHA
     =====================================================*/
    public function buscarLibres()
    {
        /* ===============================================================
        1) CARGAR EQUIPOS SEGÚN FORMATO (GRUPOS o TODOS CONTRA TODOS)
        =============================================================== */
        if ($this->grupo_id) {
            // Equipos del grupo
            $equipos = Equipo::select('equipos.*', 'campeonato_equipo.grupo_id')
                ->join('campeonato_equipo', 'equipos.id', '=', 'campeonato_equipo.equipo_id')
                ->where('campeonato_equipo.campeonato_id', $this->campeonato_id)
                ->where('campeonato_equipo.grupo_id', $this->grupo_id)
                ->where('equipos.nombre', '!=', 'Sin Equipo')
                ->get();
        } else {
            // Todos los equipos del campeonato
            $equipos = Equipo::select('equipos.*', 'campeonato_equipo.grupo_id')
                ->join('campeonato_equipo', 'equipos.id', '=', 'campeonato_equipo.equipo_id')
                ->where('campeonato_equipo.campeonato_id', $this->campeonato_id)
                ->where('equipos.nombre', '!=', 'Sin Equipo')
                ->get();
        }
        /* ===============================================================
        2) TRAER FECHAS
        =============================================================== */
        $fechas = Encuentro::where('campeonato_id', $this->campeonato_id)
            ->distinct()
            ->pluck('fecha_encuentro');

        $libresPorFecha = [];

        /* ===============================================================
        3) CALCULAR LIBRES POR FECHA
        =============================================================== */
        foreach ($fechas as $fecha) {

            $locales = Encuentro::where('campeonato_id', $this->campeonato_id)
                ->where('fecha_encuentro', $fecha)
                ->pluck('equipo_local_id');

            $visitantes = Encuentro::where('campeonato_id', $this->campeonato_id)
                ->where('fecha_encuentro', $fecha)
                ->pluck('equipo_visitante_id');

            $equiposQueJuegan = $locales->merge($visitantes)->unique();

            // Equipos libres
            $libres = $equipos->whereNotIn('id', $equiposQueJuegan);

            $libresPorFecha[$fecha] = $libres->values();

            /* ===============================================================
            4) CREAR AUTOMÁTICAMENTE EL ENCUENTRO ENTRE LIBRES
            =============================================================== */
            if ($libres->count() === 2) {

                $equipoA = $libres->values()[0];
                $equipoB = $libres->values()[1];

                $grupoA = $equipoA->pivot->grupo_id ?? null;
                $grupoB = $equipoB->pivot->grupo_id ?? null;

                $grupoDelEncuentro = $grupoA ?: $grupoB;

                $fechaReal = now()->toDateString();
                $horaAuto = '00:00:00'; // Asegúrate de incluir los segundos
                $canchaAuto = Canchas::first()->id ?? 1;

                // Verificar que no exista ya el enfrentamiento
                $existe = Encuentro::where('campeonato_id', $this->campeonato_id)
                    ->where('fecha_encuentro', $fecha)
                    ->where(function ($q) use ($equipoA, $equipoB) {
                        $q->where(function ($q2) use ($equipoA, $equipoB) {
                            $q2->where('equipo_local_id', $equipoA->id)
                                ->where('equipo_visitante_id', $equipoB->id);
                        })
                            ->orWhere(function ($q2) use ($equipoA, $equipoB) {
                                $q2->where('equipo_local_id', $equipoB->id)
                                    ->where('equipo_visitante_id', $equipoA->id);
                            });
                    })
                    ->exists();

                if (!$existe) {
                    Encuentro::create([
                        'campeonato_id' => $this->campeonato_id,
                        'grupo_id' => $grupoDelEncuentro,
                        'fecha' => $fechaReal,
                        'hora' => $horaAuto,
                        'cancha_id' => $canchaAuto,
                        'estado' => 'programado',
                        'equipo_local_id' => $equipoA->id,
                        'equipo_visitante_id' => $equipoB->id,
                        'gol_local' => 0,
                        'gol_visitante' => 0,
                        'fecha_encuentro' => $fecha,
                    ]);
                }
            }
        }
        $this->libres = $libresPorFecha;
    }

    /*====================================================
 | RENDERIZADO 
==================================================== */

    public function render()
    {
        $encuentros = Encuentro::with(['equipoLocal', 'equipoVisitante', 'cancha'])
            ->where('campeonato_id', $this->campeonato_id)
            ->where('fase_id', $this->fase_seleccionada)
            // OPCIONAL: Si quieres que al cargar el gol el partido desaparezca de esta lista:
            // ->where('estado', '!=', 'jugado') 
            ->orderBy('fecha_encuentro', 'asc')
            ->orderBy('hora')
            ->get();

        return view('livewire.fixture.fixture-crear', compact('encuentros'));
    }
}
