<?php

namespace App\Livewire\Fixture;

use App\Models\Campeonato;
use App\Models\Canchas;
use App\Models\Encuentro;
use App\Models\Equipo;
use App\Models\Grupo;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class FixtureAutomatico extends Component
{
    public $campeonatoSeleccionado;
    public $campeonato;
    public $modoSeleccionado = 'normal';
    public $porGrupos = false;
    public $fixtureGenerado = false;
    public $aniosDisponibles = [];
    public $canchas = [];
    public $campeonatos;
    public $grupoSeleccionado;
    public $grupos = [];
    public $anioSeleccionado;
    public $botonFixture = false;

    public function mount()
    {
        $this->aniosDisponibles = Campeonato::selectRaw('YEAR(created_at) as anio')

            ->distinct()
            ->orderBy('anio', 'desc')
            ->pluck('anio');

        $this->canchas = Canchas::all();
    }

    public function updatedAnioSeleccionado()
    {
        $this->campeonatos = Campeonato::whereYear('created_at', $this->anioSeleccionado)
            ->where('finalizado', 0)
            ->get();
        $this->campeonatoSeleccionado = null;
        $this->campeonato = null;
        $this->botonFixture = false;
        $this->porGrupos = false;
    }

    public function updatedCampeonatoSeleccionado($value)
    {
        $this->campeonato = Campeonato::find($value);

        if ($this->campeonato) {
            $this->botonFixture = true;

            // Detecta si es por grupos o todos contra todos según el campo formato
            $this->porGrupos = $this->campeonato->formato === 'grupos';
        } else {
            $this->botonFixture = false;
            $this->porGrupos = false;
        }
    }

    public function fixtureNormal()
    {
        $this->generarFixture($this->campeonato, 'normal', $this->porGrupos);
        LivewireAlert::title('Fixture generado con éxito')
            ->success()
            ->toast()
            ->show();
    }

    public function fixtureAlternancia()
    {
        $this->generarFixture($this->campeonato, 'alternancia', $this->porGrupos);
        LivewireAlert::title('Fixture con alternancia generado con éxito')
            ->success()
            ->toast()
            ->show();
    }

    public function fixtureIdaVuelta()
    {
        $this->generarFixture($this->campeonato, 'ida_vuelta', $this->porGrupos);
        LivewireAlert::title('Fixture de ida y vuelta generado con éxito')
            ->success()
            ->toast()
            ->show();
    }


    private function generarFixture($campeonato, $modo = 'normal', $porGrupos = false)
    {
        if (!$campeonato->faseActual) {
            throw new \Exception('El campeonato no tiene fase actual asignada');
        }

        $faseActual = $campeonato->faseActual;

        $fechaInicio = now();
        $canchas = Canchas::pluck('id')->toArray();
        $horarios = ['18:00', '19:00', '20:00', '21:00'];

        if ($porGrupos) {

            foreach ($campeonato->grupos as $grupo) {

                $equipos = $grupo->equipos->pluck('id')->toArray();

                if (count($equipos) < 2) {
                    continue;
                }

                $this->generarFixtureBase(
                    $equipos,
                    $modo,
                    $campeonato,
                    $faseActual,
                    $fechaInicio,
                    $canchas,
                    $horarios,
                    $grupo->id
                );
            }
        } else {

            $equipos = $campeonato->equipos->pluck('id')->toArray();

            if (count($equipos) < 2) {
                return;
            }

            $this->generarFixtureBase(
                $equipos,
                $modo,
                $campeonato,
                $faseActual,
                $fechaInicio,
                $canchas,
                $horarios
            );
        }
    }

    /*==================================
     Genera el fixture base para una fase
    =============================== */
    private function generarFixtureBase(
        array $equipos,
        string $modo,
        $campeonato,
        $fase,
        $fechaInicio,
        array $canchas,
        array $horarios,
        $grupoId = null
    ) {
        $canchaId = Canchas::query()->value('id');

        if (!$canchaId) {
            throw new \Exception('No hay canchas cargadas en el sistema');
        }
        $cantidadEquipos = count($equipos);

        if ($cantidadEquipos % 2 !== 0) {
            $equipos[] = null; // equipo libre
            $cantidadEquipos++;
        }

        $jornadas = $cantidadEquipos - 1;
        $mitad = $cantidadEquipos / 2;

        $rotacion = $equipos;
        $indiceCanchaHora = 0;

        for ($jornada = 1; $jornada <= $jornadas; $jornada++) {

            for ($i = 0; $i < $mitad; $i++) {

                $local = $rotacion[$i];
                $visitante = $rotacion[$cantidadEquipos - 1 - $i];

                if (!$local || !$visitante) {
                    continue;
                }

                Encuentro::create([
                    'campeonato_id' => $campeonato->id,
                    'grupo_id' => $grupoId,

                    'fase_id' => $fase->id,
                    'fase'    => $fase->nombre,

                    'fecha' => $fechaInicio->copy()->addDays(($jornada - 1) * 7)->format('Y-m-d'),
                    'hora' => $horarios[$indiceCanchaHora % count($horarios)],
                    'cancha_id' => $canchaId,
                    'estado' => 'por_programar',

                    'equipo_local_id' => $local,
                    'equipo_visitante_id' => $visitante,
                    'fecha_encuentro' => $jornada,
                ]);

                $indiceCanchaHora++;
            }

            // rotación round-robin
            $fijo = array_shift($rotacion);
            $ultimo = array_pop($rotacion);
            array_unshift($rotacion, $fijo);
            array_splice($rotacion, 1, 0, [$ultimo]);
        }

        // 🔁 IDA Y VUELTA
        if ($modo === 'ida_vuelta') {
            $canchaId = Canchas::query()->value('id');

            if (!$canchaId) {
                throw new \Exception('No hay canchas cargadas en el sistema');
            }
            for ($jornada = 1; $jornada <= $jornadas; $jornada++) {

                for ($i = 0; $i < $mitad; $i++) {

                    $local = $rotacion[$cantidadEquipos - 1 - $i];
                    $visitante = $rotacion[$i];

                    if (!$local || !$visitante) {
                        continue;
                    }

                    Encuentro::create([
                        'campeonato_id' => $campeonato->id,
                        'grupo_id' => $grupoId,

                        'fase_id' => $fase->id,
                        'fase'    => $fase->nombre,

                        'fecha' => $fechaInicio->copy()->addDays(($jornada + $jornadas - 1) * 7)->format('Y-m-d'),
                        'hora' => $horarios[$indiceCanchaHora % count($horarios)],
                        'cancha_id' => $canchaId,
                        'estado' => 'programado',

                        'equipo_local_id' => $local,
                        'equipo_visitante_id' => $visitante,
                        'fecha_encuentro' => $jornada + $jornadas,
                    ]);

                    $indiceCanchaHora++;
                }
            }
        }
    }


    public function render()
    {
        return view('livewire.fixture.fixture-automatico');
    }

    public function borrarTodo()
    {
        Encuentro::query()->delete();
    }
}
