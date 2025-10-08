<?php

namespace App\Livewire\Fixture;

use App\Models\Campeonato;
use App\Models\Canchas;
use App\Models\Eliminatoria as ModelsEliminatoria;
use App\Models\Equipo;
use App\Services\EncuentroExportService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Illuminate\Validation\ValidationException;
use Jantinnerezo\LivewireAlert\LivewireAlert as LivewireAlertLivewireAlert;
use Livewire\Component;

class Eliminatoria extends Component
{
    public $campeonato;
    public $campeonato_id;
    public $fase_actual;
    public $fases = [
        'sesentaicuatroavos',
        'treintaidosavos',
        'dieciseisavos',
        'octavos',
        'cuartos',
        'semifinal',
        '3er y 4to',
        'final'
    ];
    public $equiposDisponibles = [];
    public $encuentros = [];
    public $canchas = [];

    // Campos para nuevos encuentros
    public $nuevo_local;
    public $nuevo_visitante;
    public $nueva_fecha;
    public $nueva_hora;
    public $nueva_cancha;

    // Campos para resultados
    public $goles_local = [];
    public $goles_visitante = [];
    public $penal_local = [];
    public $penal_visitante = [];

    public function mount($campeonatoId)
    {
        $this->campeonato_id = $campeonatoId;
        $this->campeonato = Campeonato::findOrFail($campeonatoId);
        $this->canchas = Canchas::orderBy('nombre')->get();

        $this->fase_actual = $this->obtenerFaseActual();
        $this->cargarEquiposDisponibles();
        $this->cargarEncuentros();
    }

    // =========================================================
    // CÁLCULO DE FASES
    // =========================================================
    private function calcularFases($totalEquipos)
    {
        if ($totalEquipos <= 2) return ['final'];
        if ($totalEquipos <= 4) return ['semifinal', 'final'];
        if ($totalEquipos <= 8) return ['cuartos', 'semifinal', 'final'];
        if ($totalEquipos <= 16) return ['octavos', 'cuartos', 'semifinal', 'final'];
        if ($totalEquipos <= 32) return ['dieciseisavos', 'octavos', 'cuartos', 'semifinal', 'final'];
        if ($totalEquipos <= 64) return ['treintaidosavos', 'dieciseisavos', 'octavos', 'cuartos', 'semifinal', 'final'];
        if ($totalEquipos <= 128) return ['sesentaicuatroavos', 'treintaidosavos', 'dieciseisavos', 'octavos', 'cuartos', 'semifinal', 'final'];


        return ['fase previa', 'dieciseisavos', 'octavos', 'cuartos', 'semifinal', 'final'];
    }

    private function obtenerFaseActual()
    {
        $ultimaFase = ModelsEliminatoria::where('campeonato_id', $this->campeonato_id)
            ->orderByDesc('id')
            ->value('fase');

        if (!$ultimaFase) {
            $totalEquipos = DB::table('campeonato_equipo')
                ->where('campeonato_id', $this->campeonato_id)
                ->count();

            $this->fases = $this->calcularFases($totalEquipos);
            return $this->fases[0];
        }

        $pendientes = ModelsEliminatoria::where('campeonato_id', $this->campeonato_id)
            ->where('fase', $ultimaFase)
            ->where('estado', '!=', 'jugado')
            ->count();

        if ($pendientes > 0) return $ultimaFase;

        $index = array_search($ultimaFase, $this->fases);
        return $index !== false && isset($this->fases[$index + 1])
            ? $this->fases[$index + 1]
            : $ultimaFase;
    }

    // =========================================================
    // EQUIPOS DISPONIBLES
    // =========================================================
    public function cargarEquiposDisponibles()
    {
        $faseIndex = array_search($this->fase_actual, $this->fases);

        if ($faseIndex === 0) {
            $this->equiposDisponibles = DB::table('campeonato_equipo')
                ->join('equipos', 'equipos.id', '=', 'campeonato_equipo.equipo_id')
                ->where('campeonato_equipo.campeonato_id', $this->campeonato_id)
                ->select('equipos.id', 'equipos.nombre')
                ->orderBy('equipos.nombre', 'asc')
                ->get();
        } else {

            $faseAnterior = $this->fases[$faseIndex - 1];

            if ($faseAnterior !== 'semifinal') {
                /* se pasa los equipos ganadores si la fase antoerior no es = a semifinal */

                $ganadoresIds = ModelsEliminatoria::where('campeonato_id', $this->campeonato_id)
                    ->where('fase', $faseAnterior)
                    ->where('estado', 'jugado')
                    ->get()
                    ->map(fn($e) => optional($e->ganador())->id) // solo el ID
                    ->filter()
                    ->unique()
                    ->values()
                    ->toArray();


                $this->equiposDisponibles = Equipo::whereIn('id', $ganadoresIds)
                    ->orderBy('nombre', 'asc')
                    ->get();
            } else {
                /* se pasa los perdedores y ganadores por que quiere decir que es la final y hay que crear el encuentro por el 3er y 4to puesto y la final */
                $ganadores = collect();
                $perdedores = collect();

                $encuentros = ModelsEliminatoria::where('campeonato_id', $this->campeonato_id)
                    ->where('fase', $faseAnterior)
                    ->where('estado', 'jugado')
                    ->with(['equipoLocal', 'equipoVisitante']) // importante para acceder a los modelos
                    ->get();

                foreach ($encuentros as $e) {
                    $ganador = $e->ganador();

                    if ($ganador) {
                        $ganadores->push($ganador);

                        $perdedor = $ganador->id === $e->equipoLocal->id
                            ? $e->equipoVisitante
                            : $e->equipoLocal;

                        $perdedores->push($perdedor);
                    }
                }
                $ganadores = $ganadores->unique('id')->sortBy('nombre')->values();
                $perdedores = $perdedores->unique('id')->sortBy('nombre')->values();

                //Creo la final y el partido por el tercer puesto automatico    
                $this->crearFinal($ganadores, $perdedores);
            }
        }
    }

    // =========================================================
    // CARGAR ENCUENTROS
    // =========================================================
    public function cargarEncuentros()
    {
        if ($this->fase_actual === 'final') {
            $this->encuentros = ModelsEliminatoria::with(['equipoLocal', 'equipoVisitante', 'canchas'])
                ->where('campeonato_id', $this->campeonato_id)
                ->where('estado', 'programado')
                ->get();
        } else


            $this->encuentros = ModelsEliminatoria::with(['equipoLocal', 'equipoVisitante', 'canchas'])
                ->where('campeonato_id', $this->campeonato_id)
                ->where('fase', $this->fase_actual)
                ->get();


        foreach ($this->encuentros as $e) {
            $this->goles_local[$e->id] = $e->goles_local;
            $this->goles_visitante[$e->id] = $e->goles_visitante;
            $this->penal_local[$e->id] = $e->penales_local;
            $this->penal_visitante[$e->id] = $e->penales_visitante;
        }
    }

    // =========================================================
    // CREAR ENCUENTRO MANUAL
    // =========================================================
    public function crearEncuentro()
    {
        $this->validate([
            'nuevo_local' => 'required|different:nuevo_visitante',
            'nuevo_visitante' => 'required',
            'nueva_fecha' => 'required|date',
            'nueva_hora' => 'required',
            'nueva_cancha' => 'required|exists:canchas,id',
        ]);

        // **NUEVA LLAMADA DE VERIFICACIÓN**
        if (!$this->verificarConflictosProgramacion()) {
            return; // Detiene la ejecución si hay conflictos
        }

        // Verificar que los equipos no estén repetidos en esta fase
        $yaJugados = ModelsEliminatoria::where('campeonato_id', $this->campeonato_id)
            ->where('fase', $this->fase_actual)
            ->where(function ($q) {
                $q->where('equipo_local_id', $this->nuevo_local)
                    ->orWhere('equipo_visitante_id', $this->nuevo_local)
                    ->orWhere('equipo_local_id', $this->nuevo_visitante)
                    ->orWhere('equipo_visitante_id', $this->nuevo_visitante);
            })
            ->exists();

        if ($yaJugados) {
            $this->dispatch('error', ['message' => 'Uno de los equipos ya tiene un encuentro en esta fase.']);
            return;
        }

        ModelsEliminatoria::create([
            'campeonato_id' => $this->campeonato_id,
            'fase' => $this->fase_actual,
            'equipo_local_id' => $this->nuevo_local,
            'equipo_visitante_id' => $this->nuevo_visitante,
            'fecha' => $this->nueva_fecha,
            'hora' => $this->nueva_hora,
            'cancha' => $this->nueva_cancha,
            'estado' => 'pendiente',
        ]);

        $this->reset(['nuevo_local', 'nuevo_visitante', 'nueva_fecha', 'nueva_hora', 'nueva_cancha']);
        $this->cargarEncuentros();
    }

    // =========================================================
    public function crearFinal($ganadores, $perdedores)
    {
        // 1. Verificar si la FINAL ya existe
        $finalExiste = ModelsEliminatoria::where('campeonato_id', $this->campeonato_id)
            ->where('fase', 'final')
            ->exists();

        // 2. Verificar si el 3er y 4to Puesto ya existe
        $tercerPuestoExiste = ModelsEliminatoria::where('campeonato_id', $this->campeonato_id)
            ->where('fase', 'tercer y cuarto puesto')
            ->exists();

        // 3. Crear los encuentros solo si NO existen
        if (!$finalExiste && !$tercerPuestoExiste) {
            // Convertir hora base a objeto Carbon
            $horaBase = Carbon::parse($this->nueva_hora);

            // Horario para perdedores
            $horaPerdedores = $horaBase->copy();
            // Horario para ganadores (1h más tarde)
            $horaGanadores = $horaBase->copy()->addHour();

            // Armar encuentros por pares
            $paresPerdedores = $perdedores->chunk(2);
            $paresGanadores = $ganadores->chunk(2);

            foreach ($paresPerdedores as $par) {
                if ($par->count() === 2) {
                    ModelsEliminatoria::create([
                        'campeonato_id' => $this->campeonato_id,
                        'fase' => '3er y 4to',
                        'equipo_local_id' => $par[0]->id,
                        'equipo_visitante_id' => $par[1]->id,
                        'fecha' => $this->nueva_fecha ?: '2055-12-31',
                        'hora' => $horaPerdedores->format('H:i'),
                        'cancha' => 'sin definir',
                        'estado' => 'pendiente',
                    ]);
                }
            }

            foreach ($paresGanadores as $par) {
                if ($par->count() === 2) {
                    ModelsEliminatoria::create([
                        'campeonato_id' => $this->campeonato_id,
                        'fase' => 'final',
                        'equipo_local_id' => $par[0]->id,
                        'equipo_visitante_id' => $par[1]->id,
                        'fecha' =>  $this->nueva_fecha ?: '2055-12-31',
                        'hora' => $horaGanadores->format('H:i'),
                        'cancha' => 'sin definir',
                        'estado' => 'pendiente',
                    ]);
                }
            }

            $this->cargarEncuentros();
        }
    }

    // =========================================================

    private function verificarConflictosProgramacion(): bool
    {
        // =========================================================
        // 1. CONFLICTO: EQUIPO REPETIDO EN LA FASE
        // =========================================================

        // Nota: Optimizamos el query que tenías en crearEncuentro
        $equipoRepetido = ModelsEliminatoria::where('campeonato_id', $this->campeonato_id)
            ->where('fase', $this->fase_actual)
            ->where(function ($q) {
                $q->where('equipo_local_id', $this->nuevo_local)
                    ->orWhere('equipo_visitante_id', $this->nuevo_local)
                    ->orWhere('equipo_local_id', $this->nuevo_visitante)
                    ->orWhere('equipo_visitante_id', $this->nuevo_visitante);
            })
            ->exists();

        if ($equipoRepetido) {


            LivewireAlert::title('Conflicto de Equipo')
                ->text('Uno de los equipos ya tiene un encuentro programado en esta fase.')
                ->error()
                ->toast()
                ->show();
            return false;
        }

        // =========================================================
        // 2. CONFLICTO: COLISIÓN DE HORARIO EN LA MISMA CANCHA (± 1 HORA)
        // =========================================================

        // Convertir la nueva fecha y hora a un objeto Carbon para facilitar la comparación
        $nuevaProgramacion = Carbon::parse($this->nueva_fecha . ' ' . $this->nueva_hora);

        // Definir los rangos de tiempo
        $horaInicio = $nuevaProgramacion->copy()->subHours(1)->format('H:i:s');
        $horaFin = $nuevaProgramacion->copy()->addHours(1)->format('H:i:s');

        // Buscar encuentros existentes en la misma cancha y fecha
        $colisionHoraria = ModelsEliminatoria::where('campeonato_id', $this->campeonato_id)
            ->where('fecha', $this->nueva_fecha)
            ->where('cancha', $this->nueva_cancha)
            ->where(function ($query) use ($horaInicio, $horaFin) {
                // Un encuentro colisiona si su hora está dentro del rango (horaInicio, horaFin)
                // Esto incluye encuentros que empiezan 1 hora antes o hasta 1 hora después
                $query->whereBetween('hora', [$horaInicio, $horaFin]);
            })
            ->exists();

        $canchaAsociada = Canchas::find($this->nueva_cancha);

        if ($colisionHoraria) {
            LivewireAlert::title('Conflicto de Cancha/Horario')
                ->text('Ya hay un encuentro programado en la Cancha ' . $canchaAsociada->nombre . ' o una hora muy cercana (debe haber al menos 1 hora de diferencia).')
                ->warning()
                ->toast()
                ->asConfirm()
                ->show();
            return false;
        }

        // Si pasa ambas verificaciones
        return true;
    }



    //=============exportar a exel ===============


    public function render()
    {
        return view('livewire.fixture.eliminatoria');
    }
}
