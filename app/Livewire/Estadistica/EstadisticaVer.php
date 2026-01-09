<?php

namespace App\Livewire\Estadistica;

use App\Models\Campeonato;
use App\Models\Eliminatoria;
use App\Models\Encuentro;
use App\Models\EstadisticaJugadorEncuentro;
use App\Models\Jugador;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\Livewire;

class EstadisticaVer extends Component
{
    public $campeonatos;
    public $campeonatoId;
    public $fechasDisponibles = [];
    public $fechaSeleccionada;
    public $encuentros; // Se carga cuando se selecciona una fecha
    public $encuentroSeleccionado; // ID del encuentro seleccionado

    public $jugadoresLocal;
    public $jugadoresVisitante;
    public $datosJugadores = [];

    public $nombreLocal;
    public $nombreVisitante;
    public $equipoLocal_id;
    public $equipoVisitante_id;
    public $encuentroJugador; // ID del encuentro para el cual se guardan estadísticas, igual a encuentroSeleccionado
    public $formatoCampeonato;
    public $tipoEncuentro;
    public $fasesDisponibles = [];
    public $faseSeleccionada;


    public function mount($campeonatoId)
    {
        $this->campeonatoId = $campeonatoId;
        $this->encuentros = collect(); // Inicializar como colección vacía
        $this->jugadoresLocal = collect(); // Inicializar como colección vacía
        $this->jugadoresVisitante = collect(); // Inicializar como colección vacía
        $this->updatedCampeonatoId($campeonatoId);
    }


    //helper  para obtener el tipo polimórfico correcto según el formato del campeonato
    private function getPolymorphicType(): string
    {

        return match ($this->formatoCampeonato) {
            'todos_contra_todos', 'grupos' => \App\Models\Encuentro::class,
            'eliminacion_simple', 'eliminacion_doble' => \App\Models\Eliminatoria::class,
            default => throw new \Exception('Formato de campeonato desconocido: ' . $this->formatoCampeonato),
        };
    }




    /* ============================================================

========================================================*/
    public function updatedCampeonatoId($campeonatoId)
    {
        $this->campeonatoId = $campeonatoId;

        // Cargamos todas las fases que pertenecen a este campeonato
        // Usamos el modelo FaseCampeonato directamente
        $this->fasesDisponibles = \App\Models\FaseCampeonato::where('campeonato_id', $campeonatoId)
            ->orderBy('orden', 'asc') // O el campo que uses para el orden
            ->get();

        // Resetear los selects hijos
        $this->reset(['faseSeleccionada', 'fechaSeleccionada', 'fechasDisponibles', 'encuentros', 'encuentroSeleccionado']);
    }

    /* =================================================
                    FASE SELECCIONADA
=================================================== */

    // 2. Al seleccionar una FASE, cargamos las FECHAS de esa fase
    public function updatedFaseSeleccionada($value)
    {

        if ($value) {
            // Ahora buscamos las fechas (Jornadas) de los encuentros 
            // filtrando por el fase_id seleccionado
            $this->fechasDisponibles = Encuentro::where('campeonato_id', $this->campeonatoId)
                ->where('fase_id', $value)
                ->select('fecha_encuentro')
                ->distinct()
                ->orderBy('fecha_encuentro', 'asc')
                ->pluck('fecha_encuentro');
        } else {
            $this->fechasDisponibles = [];
        }
        //dd($this->fechasDisponibles);

        $this->reset(['fechaSeleccionada', 'encuentros', 'encuentroSeleccionado']);
    }
    /*======================================================================== 
                        FECHA SELECCIONADA
==================================================================*/

    // 3. Al seleccionar la FECHA, filtramos por CAMPEONATO, FASE y FECHA
    public function updatedFechaSeleccionada($value)
    {
        // Debug preventivo: si quieres ver qué llega, descomenta la siguiente línea
        // dd($this->faseSeleccionada, $value);

        if ($value && $this->faseSeleccionada) {
            // Buscamos los encuentros que coincidan con AMBOS criterios
            $this->encuentros = \App\Models\Encuentro::where('campeonato_id', $this->campeonatoId)
                ->where('fase_id', $this->faseSeleccionada)
                ->where('fecha_encuentro', $value)
                ->with(['equipoLocal', 'equipoVisitante'])
                ->get();
        } else {
            $this->encuentros = [];
        }

        // Resetear el encuentro seleccionado para que el usuario elija uno nuevo
        $this->reset(['encuentroSeleccionado']);
    }

    // Simplificamos el helper ya que solo usas Encuentro
    /*  private function getPolymorphicType(): string
    {
        return \App\Models\Encuentro::class;
    } */


    /*======================================================================== 
                        ENCUENTROS SELECCIONADOS
==================================================================*/



    public function updatedEncuentroSeleccionado($value)
    {
        $this->resetEncuentroData();

        if (!$value) return;

        // Ya no necesitamos buscar el tipo polimórfico dinámicamente
        // Usamos directamente el modelo Encuentro
        $partido = \App\Models\Encuentro::with(['equipoLocal', 'equipoVisitante'])->find($value);

        if (!$partido) return;

        // Datos base del encuentro
        $this->tipoEncuentro = 'Encuentro'; // Valor fijo ahora
        $this->encuentroJugador = $partido->id;
        $this->nombreLocal = $partido->equipoLocal->nombre ?? 'Sin nombre';
        $this->nombreVisitante = $partido->equipoVisitante->nombre ?? 'Sin nombre';
        $this->equipoLocal_id = $partido->equipoLocal->id;
        $this->equipoVisitante_id = $partido->equipoVisitante->id;

        // Cargar jugadores (usando tu método existente jugadoresDelCampeonato)
        $this->jugadoresLocal = $partido->equipoLocal
            ->jugadoresDelCampeonato($this->campeonatoId)
            ->orderBy('apellido')->orderBy('nombre')->get();

        $this->jugadoresVisitante = $partido->equipoVisitante
            ->jugadoresDelCampeonato($this->campeonatoId)
            ->orderBy('apellido')->orderBy('nombre')->get();

        // Obtener todos los IDs de los jugadores para la consulta de estadísticas
        $playerIds = $this->jugadoresLocal->pluck('id')
            ->merge($this->jugadoresVisitante->pluck('id'))
            ->unique();

        // Cargar estadísticas existentes (Asegúrate de que 'estadisticable_type' guarde la clase de Encuentro)
        $modeloClase = \App\Models\Encuentro::class;

        $existingStats = EstadisticaJugadorEncuentro::where('estadisticable_id', $this->encuentroJugador)
            ->where('estadisticable_type', $modeloClase)
            ->whereIn('jugador_id', $playerIds)
            ->get()
            ->keyBy('jugador_id');

        // Poblamos los datos para ambos equipos en un solo ciclo si prefieres, 
        // pero mantenemos tu estructura separada para mayor claridad
        $this->mapearEstadisticas($this->jugadoresLocal, $this->equipoLocal_id, $existingStats);
        $this->mapearEstadisticas($this->jugadoresVisitante, $this->equipoVisitante_id, $existingStats);
    }

    /**
     * Función auxiliar para evitar repetir código al mapear stats
     */
    private function mapearEstadisticas($jugadores, $equipoId, $existingStats)
    {
        foreach ($jugadores as $jugador) {
            $stats = $existingStats->get($jugador->id);
            $this->datosJugadores[$jugador->id] = [
                'equipo_id'      => $equipoId,
                'goles'          => $stats?->goles ?? 0,
                'amarilla'       => (bool)($stats?->tarjeta_amarilla ?? false),
                'doble_amarilla' => (bool)($stats?->tarjeta_doble_amarilla ?? false),
                'roja'           => (bool)($stats?->tarjeta_roja ?? false),
            ];
        }
    }




    private function resetEncuentroData()
    {
        $this->encuentroJugador = null;
        $this->jugadoresLocal = collect();
        $this->jugadoresVisitante = collect();
        $this->datosJugadores = [];
        $this->nombreLocal = null;
        $this->nombreVisitante = null;
        $this->equipoLocal_id = null;
        $this->equipoVisitante_id = null;
    }


    public function guardarDatos()
    {
        // Verificamos que tengamos un encuentro y campeonato seleccionado
        if (!$this->encuentroJugador || !$this->campeonatoId) {
            $this->dispatch('seleccionar-campeonato');
            return;
        }

        $mensajesGuardados = [];
        $huboErroresDeValidacion = false;

        // Modelo fijo ahora
        $modeloClase = \App\Models\Encuentro::class;

        foreach ($this->datosJugadores as $jugadorId => $datos) {
            // Normalizar valores para asegurar que lleguen como booleanos o enteros
            $datos['amarilla'] = filter_var($datos['amarilla'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $datos['doble_amarilla'] = filter_var($datos['doble_amarilla'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $datos['roja'] = filter_var($datos['roja'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $datos['goles'] = !empty($datos['goles']) ? intval($datos['goles']) : 0;

            // Validar datos del jugador
            try {
                Validator::make($datos, [
                    'equipo_id' => 'required|exists:equipos,id',
                    'goles' => 'required|integer|min:0',
                    'amarilla' => 'required|boolean',
                    'doble_amarilla' => 'required|boolean',
                    'roja' => 'required|boolean',
                ])->validate();
            } catch (ValidationException $e) {
                $huboErroresDeValidacion = true;
                $jugadorError = Jugador::find($jugadorId);
                $nombreError = $jugadorError ? ($jugadorError->apellido . ' ' . $jugadorError->nombre) : $jugadorId;

                LivewireAlert::title('Error en: ' . $nombreError)
                    ->text(implode(", ", $e->validator->errors()->all()))
                    ->error()->show();
                continue;
            }

            // ¿El jugador tiene alguna acción registrada?
            $tieneAcciones = ($datos['goles'] > 0 || $datos['amarilla'] || $datos['doble_amarilla'] || $datos['roja']);

            $claveBusqueda = [
                'jugador_id' => $jugadorId,
                'estadisticable_id' => $this->encuentroJugador,
                'estadisticable_type' => $modeloClase, // Siempre Encuentro
            ];

            $nuevosDatos = [
                'equipo_id' => $datos['equipo_id'],
                'campeonato_id' => intval($this->campeonatoId),
                'goles' => $datos['goles'],
                'tarjeta_amarilla' => $datos['amarilla'],
                'tarjeta_doble_amarilla' => $datos['doble_amarilla'],
                'tarjeta_roja' => $datos['roja'],
            ];

            $registroExistente = EstadisticaJugadorEncuentro::where($claveBusqueda)->first();

            if ($tieneAcciones) {
                // Si hay cambios respecto a lo que ya existe, guardamos/actualizamos
                if (!$registroExistente || collect($registroExistente->only(array_keys($nuevosDatos)))->diffAssoc($nuevosDatos)->isNotEmpty()) {
                    EstadisticaJugadorEncuentro::updateOrCreate($claveBusqueda, $nuevosDatos);

                    $jugador = Jugador::find($jugadorId);
                    $mensajesGuardados[] = $jugador->apellido . " " . $jugador->nombre;
                }
            } elseif ($registroExistente) {
                // Si no tiene acciones pero existe un registro, lo borramos (limpieza)
                $registroExistente->delete();
                $jugador = Jugador::find($jugadorId);
                $mensajesGuardados[] = ($jugador ? $jugador->apellido : $jugadorId) . " (Borrado)";
            }
        }

        $this->mostrarNotificacionFinal($huboErroresDeValidacion, $mensajesGuardados);
        $this->resetEncuentroData();
    }

    /**
     * Muestra el resultado final de la operación
     */
    private function mostrarNotificacionFinal($huboErrores, $mensajes)
    {
        if (!$huboErrores && !empty($mensajes)) {
            LivewireAlert::title('¡Éxito!')
                ->text('Datos actualizados de: ' . implode(", ", $mensajes))
                ->success()->show();

            // Recargar datos para refrescar la UI
            $current = $this->encuentroSeleccionado;
            $this->updatedEncuentroSeleccionado($current);
        } elseif (empty($mensajes) && !$huboErrores) {
            LivewireAlert::title('Información')
                ->text('No se detectaron cambios nuevos.')
                ->info()->show();
        }
    }

    public function actualizarEquiposJugadores()
    {
        // 1. Obtener todos los registros de la pivote del campeonato seleccionado
        $registros = DB::table('campeonato_jugador_equipo')
            ->where('campeonato_id', $this->campeonatoId)
            ->get();

        // 2. Recorrer cada registro del campeonato
        foreach ($registros as $registro) {

            // Buscar al jugador
            $jugador = Jugador::find($registro->jugador_id);

            if ($jugador) {
                // Actualizar su equipo_id con el equipo de la pivote
                $jugador->equipo_id = $registro->equipo_id;
                $jugador->save();
            }
        }

        $this->dispatch('alert', 'Equipos actualizados correctamente.');
    }

    public function render()
    {
        return view('livewire.estadistica.estadistica-ver');
    }
}
