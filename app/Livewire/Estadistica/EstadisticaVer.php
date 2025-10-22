<?php

namespace App\Livewire\Estadistica;

use App\Models\Campeonato;
use App\Models\Eliminatoria;
use App\Models\Encuentro;
use App\Models\EstadisticaJugadorEncuentro;
use App\Models\Jugador;
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





    public function updatedCampeonatoId($campeonatoId)
    {


        $campeonato = Campeonato::where('id', $this->campeonatoId)->first();
        $this->formatoCampeonato = $campeonato->formato;



        if ($this->formatoCampeonato === 'todos_contra_todos' || $this->formatoCampeonato === 'grupos') {
            $this->fechasDisponibles = Encuentro::where('campeonato_id', $campeonatoId)
                ->select('fecha_encuentro')
                ->distinct()
                ->orderBy('fecha_encuentro')
                ->pluck('fecha_encuentro');
        } else {
            $this->fechasDisponibles = Eliminatoria::where('campeonato_id', $campeonatoId)
                ->select('fase')
                ->distinct()
                ->orderBy('fase')
                ->pluck('fase');
        }


        // Resetear dependencias
        $this->fechaSeleccionada = null;
        $this->encuentros = collect();
        $this->encuentroSeleccionado = null;
        $this->jugadoresLocal = collect();
        $this->jugadoresVisitante = collect();
        $this->datosJugadores = [];
        $this->nombreLocal = null;
        $this->nombreVisitante = null;
        $this->equipoLocal_id = null;
        $this->equipoVisitante_id = null;
        $this->encuentroJugador = null;
    }

    public function updatedFechaSeleccionada($value)
    {


        if ($value && $this->campeonatoId) {
            if ($this->formatoCampeonato === 'todos_contra_todos' || $this->formatoCampeonato === 'grupos') {
                $this->encuentros = Encuentro::where('campeonato_id', $this->campeonatoId)
                    ->where('fecha_encuentro', $value)
                    ->with(['equipoLocal.jugadores', 'equipoVisitante.jugadores'])
                    ->orderBy('fecha') // 'fecha' podría ser un timestamp para ordenar por hora si hay varios en un día
                    ->get();
            } else {
                $this->encuentros = Eliminatoria::where('campeonato_id', $this->campeonatoId)
                    ->where('fase', $value)
                    ->with(['equipoLocal.jugadores', 'equipoVisitante.jugadores'])
                    ->orderBy('fecha') // 'fecha' podría ser un timestamp para ordenar por hora si hay varios en un día
                    ->get();
            }
        }


        // Limpiar datos anteriores dependientes de la fecha
        $this->encuentroSeleccionado = null;
        $this->jugadoresLocal = collect();
        $this->jugadoresVisitante = collect();
        $this->datosJugadores = [];
        $this->nombreLocal = null;
        $this->nombreVisitante = null;
        $this->equipoLocal_id = null;
        $this->equipoVisitante_id = null;
        $this->encuentroJugador = null;
    }

    public function updatedEncuentroSeleccionado($value)
    {
        $this->resetEncuentroData();

        if (!$value) return;

        // Obtener el modelo polimórfico según el formato del campeonato
        $modelo = $this->getPolymorphicType();


        // Cargar el partido con jugadores
        $partido = $modelo::with([
            'equipoLocal.jugadores',
            'equipoVisitante.jugadores'
        ])->find($value);

        if (!$partido) return;

        // Setear datos base
        $this->tipoEncuentro = class_basename($modelo); // 'Encuentro' o 'Eliminatoria'
        $this->encuentroJugador = $partido->id;
        $this->nombreLocal = $partido->equipoLocal->nombre ?? 'Sin nombre';
        $this->nombreVisitante = $partido->equipoVisitante->nombre ?? 'Sin nombre';
        $this->equipoLocal_id = $partido->equipoLocal->id;
        $this->equipoVisitante_id = $partido->equipoVisitante->id;
        $this->jugadoresLocal = $partido->equipoLocal->jugadores ?? collect();
        $this->jugadoresVisitante = $partido->equipoVisitante->jugadores ?? collect();

        // IDs de jugadores
        $playerIds = $this->jugadoresLocal->pluck('id')
            ->merge($this->jugadoresVisitante->pluck('id'))
            ->unique()
            ->filter();

        // Buscar estadísticas polimórficas
        $existingStats = collect();
        if ($playerIds->isNotEmpty()) {
            $existingStats = EstadisticaJugadorEncuentro::where('estadisticable_id', $this->encuentroJugador)
                ->where('estadisticable_type', $modelo)
                ->whereIn('jugador_id', $playerIds)
                ->get()
                ->keyBy('jugador_id');
        }
        /*   dd([
            'encuentro_id' => $this->encuentroJugador,
            'tipo' => $this->getPolymorphicType(),
            'jugadores' => $playerIds->toArray()
        ]);
 */
        // Poblar datos jugadores
        foreach ($this->jugadoresLocal as $jugador) {
            $stats = $existingStats->get($jugador->id);
            $this->datosJugadores[$jugador->id] = [
                'equipo_id' => $this->equipoLocal_id,
                'goles' => $stats?->goles ?? 0,
                'amarilla' => (bool)($stats?->tarjeta_amarilla ?? false),
                'doble_amarilla' => (bool)($stats?->tarjeta_doble_amarilla ?? false),
                'roja' => (bool)($stats?->tarjeta_roja ?? false),
            ];
        }

        foreach ($this->jugadoresVisitante as $jugador) {
            $stats = $existingStats->get($jugador->id);
            $this->datosJugadores[$jugador->id] = [
                'equipo_id' => $this->equipoVisitante_id,
                'goles' => $stats?->goles ?? 0,
                'amarilla' => (bool)($stats?->tarjeta_amarilla ?? false),
                'doble_amarilla' => (bool)($stats?->tarjeta_doble_amarilla ?? false),
                'roja' => (bool)($stats?->tarjeta_roja ?? false),
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
        if (!$this->encuentroJugador || !$this->campeonatoId || !$this->tipoEncuentro) {
            $this->dispatch('seleccionar-campeonato');

            return;
        }

        $mensajesGuardados = [];
        $huboErroresDeValidacion = false;

        // Determinar el modelo polimórfico
        $modeloPartido = $this->tipoEncuentro === 'eliminatoria'
            ? Eliminatoria::class
            : Encuentro::class;

        foreach ($this->datosJugadores as $jugadorId => $datos) {
            // Normalizar valores
            $datos['amarilla'] = filter_var($datos['amarilla'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $datos['doble_amarilla'] = filter_var($datos['doble_amarilla'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $datos['roja'] = filter_var($datos['roja'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $datos['goles'] = !empty($datos['goles']) ? intval($datos['goles']) : 0;

            // Validar
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
                $errorMessages = $e->validator->errors()->all();
                $jugadorError = Jugador::find($jugadorId);
                $nombreJugadorError = $jugadorError ? ($jugadorError->apellido . ', ' . $jugadorError->nombre) : 'ID: ' . $jugadorId;

                LivewireAlert::title('Error de validación para: ' . $nombreJugadorError,)
                    ->text(implode("\n", $errorMessages))
                    ->error()
                    ->show();


                continue;
            }

            $tieneEstadisticasPositivas = (
                $datos['goles'] > 0 ||
                $datos['amarilla'] ||
                $datos['doble_amarilla'] ||
                $datos['roja']
            );

            $claveBusqueda = [
                'jugador_id' => $jugadorId,
                'estadisticable_id' => $this->encuentroJugador,
                'estadisticable_type' => $this->getPolymorphicType(), // ← tipo dinámico
            ];

            $registroExistente = EstadisticaJugadorEncuentro::where($claveBusqueda)->first();

            $nuevosDatosParaGuardar = [
                'equipo_id' => $datos['equipo_id'],
                'campeonato_id' => intval($this->campeonatoId),
                'goles' => $datos['goles'],
                'tarjeta_amarilla' => $datos['amarilla'],
                'tarjeta_doble_amarilla' => $datos['doble_amarilla'],
                'tarjeta_roja' => $datos['roja'],
            ];

            if ($tieneEstadisticasPositivas) {
                if ($registroExistente) {
                    $datosExistentes = collect([
                        'equipo_id' => $registroExistente->equipo_id,
                        'campeonato_id' => $registroExistente->campeonato_id,
                        'goles' => $registroExistente->goles,
                        'tarjeta_amarilla' => (bool)$registroExistente->tarjeta_amarilla,
                        'tarjeta_doble_amarilla' => (bool)$registroExistente->tarjeta_doble_amarilla,
                        'tarjeta_roja' => (bool)$registroExistente->tarjeta_roja,
                    ]);

                    if ($datosExistentes->diffAssoc($nuevosDatosParaGuardar)->isEmpty()) {
                        continue;
                    }
                }

                EstadisticaJugadorEncuentro::updateOrCreate($claveBusqueda, $nuevosDatosParaGuardar);

                $jugador = Jugador::find($jugadorId);
                if ($jugador) {
                    $mensajesGuardados[] = $jugador->apellido . ' ' . $jugador->nombre . ' ,';
                }
            } elseif ($registroExistente) {
                $registroExistente->delete();
                $jugador = Jugador::find($jugadorId);
                if ($jugador) {
                    $mensajesGuardados[] = $jugador->apellido . ' ' . $jugador->nombre . ' - Registro eliminado.';
                }
            }
        }

        if ($huboErroresDeValidacion) {
            // Ya se mostraron los errores individuales
        } elseif (!empty($mensajesGuardados)) {

            LivewireAlert::title('Estadistica guardada')
                ->text(implode("\n", $mensajesGuardados))
                ->success()
                ->show();


            if ($this->encuentroSeleccionado) {
                $currentEncuentro = $this->encuentroSeleccionado;
                $this->updatedEncuentroSeleccionado(null);
                $this->updatedEncuentroSeleccionado($currentEncuentro);
            }
        } else {
            LivewireAlert::title('info')
                ->text('sin cambios')
                ->error()
                ->show();
        }
    }

    public function render()
    {
        return view('livewire.estadistica.estadistica-ver');
    }
}
