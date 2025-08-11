<?php

namespace App\Livewire\Fixture;

use App\Models\Campeonato;
use App\Models\Canchas;
use App\Models\Encuentro;
use App\Models\Equipo;
use App\Models\Grupo;
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
        $this->campeonatos = Campeonato::whereYear('created_at', $this->anioSeleccionado)->get();
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
    }

    public function fixtureAlternancia()
    {
        $this->generarFixture($this->campeonato, 'alternancia', $this->porGrupos);
    }

    public function fixtureIdaVuelta()
    {
        $this->generarFixture($this->campeonato, 'ida_vuelta', $this->porGrupos);
    }


    private function generarFixture($campeonato, $modo = 'normal', $porGrupos = false)
    {

        $canchas = Canchas::pluck('id')->toArray();
        $horarios = ['14:00', '15:00', '16:00', '17:00'];
        $fechaInicio = now();

        $indiceCanchaHora = 0; // 🔹 índice global para evitar pisar horarios

        if ($porGrupos) {
            $grupos = Grupo::where('campeonato_id', $campeonato->id)->get();
            foreach ($grupos as $grupo) {
                $equipos = $grupo->equipos->pluck('id')->toArray();
                $indiceCanchaHora = $this->generarFixtureBase(
                    $equipos,
                    $modo,
                    $campeonato,
                    $fechaInicio,
                    $canchas,
                    $horarios,
                    $grupo->id,
                    $indiceCanchaHora
                );
            }
        } else {
            $equipos = Equipo::whereHas('campeonatos', function ($q) use ($campeonato) {
                $q->where('campeonato_id', $campeonato->id);
            })->pluck('id')->toArray();

            $this->generarFixtureBase(
                $equipos,
                $modo,
                $campeonato,
                $fechaInicio,
                $canchas,
                $horarios,
                null,
                $indiceCanchaHora
            );
        }
    }

    private function generarFixtureBase($equipos, $modo, $campeonato, $fechaInicio, $canchas, $horarios, $grupoId = null, $indiceCanchaHora = 0)
    {
        if (count($equipos) % 2 !== 0) {
            $equipos[] = null;
        }

        $totalEquipos = count($equipos);
        $totalJornadas = $totalEquipos - 1;

        for ($jornada = 1; $jornada <= $totalJornadas; $jornada++) {
            for ($i = 0; $i < $totalEquipos / 2; $i++) {
                $local = $equipos[$i];
                $visitante = $equipos[$totalEquipos - 1 - $i];

                if (($modo === 'alternancia' || $modo === 'ida_vuelta') && $jornada % 2 === 0) {
                    [$local, $visitante] = [$visitante, $local];
                }

                if ($local !== null && $visitante !== null) {
                    Encuentro::create([
                        'campeonato_id' => $campeonato->id,
                        'grupo_id' => $grupoId,
                        'fecha' => $fechaInicio->copy()->addDays(($jornada - 1) * 7)->format('Y-m-d'),
                        'hora' => $horarios[$indiceCanchaHora % count($horarios)],
                        'cancha_id' => $canchas[intdiv($indiceCanchaHora, count($horarios)) % count($canchas)],
                        'estado' => 'programado',
                        'equipo_local_id' => $local,
                        'equipo_visitante_id' => $visitante,
                        'fecha_encuentro' => $jornada,
                    ]);
                    $indiceCanchaHora++;
                }
            }
            $equipos = array_merge([$equipos[0]], array_slice($equipos, -1), array_slice($equipos, 1, -1));
        }

        if ($modo === 'ida_vuelta') {
            for ($jornada = 1; $jornada <= $totalJornadas; $jornada++) {
                $partidos = Encuentro::where('campeonato_id', $campeonato->id)
                    ->where('grupo_id', $grupoId)
                    ->where('fecha_encuentro', $jornada)
                    ->get();

                foreach ($partidos as $partido) {
                    Encuentro::create([
                        'campeonato_id' => $campeonato->id,
                        'grupo_id' => $grupoId,
                        'fecha' => $fechaInicio->copy()->addDays(($totalJornadas + $jornada - 1) * 7)->format('Y-m-d'),
                        'hora' => $partido->hora,
                        'cancha_id' => $partido->cancha_id,
                        'estado' => 'programado',
                        'equipo_local_id' => $partido->equipo_visitante_id,
                        'equipo_visitante_id' => $partido->equipo_local_id,
                        'fecha_encuentro' => $totalJornadas + $jornada,
                    ]);
                }
            }
        }

        return $indiceCanchaHora; // 🔹 devolvemos el índice para el siguiente grupo
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
