<?php

namespace App\Livewire\Estadistica;

use App\Models\Campeonato;
use App\Models\Encuentro;
use App\Models\EstadisticaJugadorEncuentro;
use App\Models\Jugador;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

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


    public function mount($campeonatoId)
    {
        $this->campeonatoId = $campeonatoId;
        $this->encuentros = collect(); // Inicializar como colección vacía
        $this->jugadoresLocal = collect(); // Inicializar como colección vacía
        $this->jugadoresVisitante = collect(); // Inicializar como colección vacía
        $this->updatedCampeonatoId($campeonatoId);
    }

    public function updatedCampeonatoId($campeonatoId)
    {
        if ($campeonatoId) {
            $this->fechasDisponibles = Encuentro::where('campeonato_id', $campeonatoId)
                ->select('fecha_encuentro')
                ->distinct()
                ->orderBy('fecha_encuentro')
                ->pluck('fecha_encuentro');
        } else {
            $this->fechasDisponibles = [];
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
            $this->encuentros = Encuentro::where('campeonato_id', $this->campeonatoId)
                ->where('fecha_encuentro', $value)
                ->with(['equipoLocal.jugadores', 'equipoVisitante.jugadores'])
                ->orderBy('fecha') // 'fecha' podría ser un timestamp para ordenar por hora si hay varios en un día
                ->get();
        } else {
            $this->encuentros = collect();
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
        if ($value) {
            $encuentro = Encuentro::with([
                'equipoLocal.jugadores',
                'equipoVisitante.jugadores'
            ])->find($value);

            if ($encuentro) {
                $this->nombreLocal = $encuentro->equipoLocal->nombre ?? 'Sin nombre';
                $this->nombreVisitante = $encuentro->equipoVisitante->nombre ?? 'Sin nombre';
                $this->equipoLocal_id = $encuentro->equipoLocal->id;
                $this->equipoVisitante_id = $encuentro->equipoVisitante->id;
                $this->encuentroJugador = $encuentro->id; // ID del encuentro actual

                $this->jugadoresLocal = $encuentro->equipoLocal->jugadores ?? collect();
                $this->jugadoresVisitante = $encuentro->equipoVisitante->jugadores ?? collect();

                // 1. Recolectar todos los IDs de jugadores de ambos equipos
                $playerIds = $this->jugadoresLocal->pluck('id')
                    ->merge($this->jugadoresVisitante->pluck('id'))
                    ->unique()
                    ->filter(); // Asegurarse de que no haya IDs nulos o vacíos

                // 2. Obtener estadísticas existentes para estos jugadores en este encuentro
                $existingStats = collect(); // Por defecto, colección vacía
                if ($playerIds->isNotEmpty()) {
                    $existingStats = EstadisticaJugadorEncuentro::where('encuentro_id', $this->encuentroJugador)
                        ->whereIn('jugador_id', $playerIds)
                        ->get()
                        ->keyBy('jugador_id'); // Clave por jugador_id para búsqueda fácil
                }

                $this->datosJugadores = []; // Reinicializar datos para el nuevo encuentro

                // 3. Poblar datosJugadores para el equipo local
                foreach ($this->jugadoresLocal as $jugador) {
                    $stats = $existingStats->get($jugador->id); // Obtener estadísticas si existen para este jugador
                    $this->datosJugadores[$jugador->id] = [
                        'equipo_id' => $this->equipoLocal_id,
                        'goles' => $stats ? $stats->goles : 0,
                        'amarilla' => $stats ? (bool)$stats->tarjeta_amarilla : false,
                        'doble_amarilla' => $stats ? (bool)$stats->tarjeta_doble_amarilla : false,
                        'roja' => $stats ? (bool)$stats->tarjeta_roja : false,
                    ];
                }

                // 4. Poblar datosJugadores para el equipo visitante
                foreach ($this->jugadoresVisitante as $jugador) {
                    $stats = $existingStats->get($jugador->id); // Obtener estadísticas si existen para este jugador
                    $this->datosJugadores[$jugador->id] = [
                        'equipo_id' => $this->equipoVisitante_id,
                        'goles' => $stats ? $stats->goles : 0,
                        'amarilla' => $stats ? (bool)$stats->tarjeta_amarilla : false,
                        'doble_amarilla' => $stats ? (bool)$stats->tarjeta_doble_amarilla : false,
                        'roja' => $stats ? (bool)$stats->tarjeta_roja : false,
                    ];
                }
            } else {
                // Encuentro no encontrado, limpiar datos
                $this->resetEncuentroData();
            }
        } else {
            // No hay encuentro seleccionado, limpiar datos
            $this->resetEncuentroData();
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
        if (!$this->encuentroJugador || !$this->campeonatoId) {
            $this->dispatch('seleccionar-campeonato');

            return;
        }

        $mensajesGuardados = [];
        $huboErroresDeValidacion = false;

        foreach ($this->datosJugadores as $jugadorId => $datos) {
            // Normalizar valores booleanos de checkboxes y goles
            $datos['amarilla'] = filter_var($datos['amarilla'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $datos['doble_amarilla'] = filter_var($datos['doble_amarilla'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $datos['roja'] = filter_var($datos['roja'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $datos['goles'] = !empty($datos['goles']) ? intval($datos['goles']) : 0;

            // Validar los datos individuales de cada jugador
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

                $this->dispatch('swal', [
                    'icon' => 'error',
                    'title' => 'Error de validación para: ' . $nombreJugadorError,
                    'text' => implode("\n", $errorMessages),
                    'timer' => 7000
                ]);
                continue; // Saltar este jugador y continuar con el siguiente
            }

            // Determinar si el jugador tiene alguna estadística positiva en este envío
            $tieneEstadisticasPositivas = (
                $datos['goles'] > 0 ||
                $datos['amarilla'] ||
                $datos['doble_amarilla'] ||
                $datos['roja']
            );

            $registroExistente = EstadisticaJugadorEncuentro::where('jugador_id', $jugadorId)
                ->where('encuentro_id', $this->encuentroJugador)
                ->first();

            if ($tieneEstadisticasPositivas) {
                // Si el jugador tiene estadísticas positivas, las creamos o actualizamos
                $nuevosDatosParaGuardar = [
                    'equipo_id' => $datos['equipo_id'],
                    'campeonato_id' => intval($this->campeonatoId),
                    'goles' => $datos['goles'],
                    'tarjeta_amarilla' => $datos['amarilla'],
                    'tarjeta_doble_amarilla' => $datos['doble_amarilla'],
                    'tarjeta_roja' => $datos['roja'],
                ];

                // Solo actualizar si hay cambios reales para evitar writes innecesarios
                if ($registroExistente) {
                    $datosExistentesParaComparar = [
                        'equipo_id' => $registroExistente->equipo_id,
                        'campeonato_id' => $registroExistente->campeonato_id,
                        'goles' => $registroExistente->goles,
                        'tarjeta_amarilla' => (bool)$registroExistente->tarjeta_amarilla,
                        'tarjeta_doble_amarilla' => (bool)$registroExistente->tarjeta_doble_amarilla,
                        'tarjeta_roja' => (bool)$registroExistente->tarjeta_roja,
                    ];
                    if (collect($nuevosDatosParaGuardar)->diffAssoc($datosExistentesParaComparar)->isEmpty()) {
                        continue; // Sin cambios, saltar este jugador
                    }
                }

                EstadisticaJugadorEncuentro::updateOrCreate(
                    [
                        'jugador_id' => $jugadorId,
                        'encuentro_id' => $this->encuentroJugador,
                    ],
                    $nuevosDatosParaGuardar
                );

                $jugador = Jugador::find($jugadorId);
                if ($jugador) {
                    $mensajesGuardados[] = $jugador->apellido . ' ' . $jugador->nombre . ' - Estadísticas actualizadas.';
                }
            } elseif ($registroExistente) {
                // Si el jugador NO tiene estadísticas positivas EN EL FORMULARIO
                // Y EXISTE un registro previo en la base de datos, lo eliminamos.
                $registroExistente->delete();
                $jugador = Jugador::find($jugadorId);
                if ($jugador) {
                    $mensajesGuardados[] = $jugador->apellido . ' ' . $jugador->nombre . ' - Registro de estadísticas eliminado (sin goles/tarjetas).';
                }
            }
            // Si el jugador no tiene estadísticas positivas EN EL FORMULARIO
            // Y NO EXISTE un registro previo, no hacemos nada (continue).
        }

        if ($huboErroresDeValidacion) {
            // Ya se mostraron los SweetAlerts individuales para cada error.
            // Podrías mostrar un mensaje general adicional si lo deseas.
        } elseif (!empty($mensajesGuardados)) {
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Guardado con éxito!',
                'text' => implode("\n", $mensajesGuardados),
            ]);
            // Recargar los datos del encuentro para que el formulario refleje la BD
            if ($this->encuentroSeleccionado) {
                $currentEncuentro = $this->encuentroSeleccionado;
                $this->updatedEncuentroSeleccionado(null); // Limpia
                $this->updatedEncuentroSeleccionado($currentEncuentro); // Recarga
            }
        } else if (!$huboErroresDeValidacion) {
            $this->dispatch('swal', [
                'icon' => 'info',
                'title' => 'Sin cambios',
                'text' => 'No se detectaron cambios para guardar en las estadísticas de los jugadores.',
                'timer' => 4000
            ]);
        }
        $this->dispatch('ok');
    }

    public function render()
    {
        return view('livewire.estadistica.estadistica-ver');
    }
}
