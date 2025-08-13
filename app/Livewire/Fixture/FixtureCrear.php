<?php

namespace App\Livewire\Fixture;

use App\Models\Campeonato;
use App\Models\Canchas;
use App\Models\Encuentro;
use App\Models\Equipo;
use App\Models\Grupo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Livewire\Component;

use App\Services\EncuentroExportService as ServicesEncuentroExportService;

class FixtureCrear extends Component
{
    public $campeonatoId;
    public $campeonato;
    public $anioSeleccionado;
    public $aniosDisponibles = [];
    public $campeonatos = [];
    public $campeonatoSeleccionado;
    public $campeonato_id;
    public $grupos = [];
    public $equipos = [];
    public $grupoSeleccionado;
    public $equiposSeleccionados = [];
    public $fixture = [];
    public $grupo_id;
    public $equipo_local_id;
    public $equipo_visitante_id;
    public $canchas = [];
    public $cancha_id;
    public $fecha;
    public $hora;
    public $estado = 'programado'; // Estado por defecto
    public $jornada;

    public function mount($campeonatoId)
    {

        $this->campeonatoId = $campeonatoId;
        $this->campeonatoSeleccionado = $campeonatoId;
        $this->campeonato = Campeonato::find($campeonatoId);

        $this->canchas = Canchas::all(); // suponiendo que tenés una tabla de canchas
        $this->updatedCampeonatoSeleccionado();
    }



    //----------------------------- CAMPEONATO SELECCIONADO ------------------------------
    public function updatedCampeonatoSeleccionado()
    {
        if ($this->campeonatoSeleccionado) {
            // Buscar el campeonato seleccionado
            $campeonato = Campeonato::find($this->campeonatoSeleccionado);

            // Cargar grupos si el formato es por grupo
            if ($campeonato && $campeonato->formato === 'grupos') {
                $this->grupos = Grupo::where('campeonato_id', $campeonato->id)->get();
                $this->equipos = [];
            } else {
                // Cargar equipos si es todos contra todos
                $this->grupos = [];
                $this->equipos = Equipo::whereHas('campeonatos', function ($query) use ($campeonato) {
                    $query->where('campeonato_id', $campeonato->id);
                })->orderBy('nombre')->get();
            }

            // También podrías setear otros campos si querés
            $this->campeonato_id = $campeonato->id;
        } else {
            $this->grupos = [];
            $this->equipos = [];
        }
    }

    //----------------------------- GRUPO SELECCIONADO ------------------------------
    public function updatedGrupoId()
    {

        if ($this->grupo_id) {
            // Filtrar equipos que pertenecen al campeonato seleccionado y al grupo seleccionado
            $this->equipos = Equipo::whereHas('campeonatos', function ($query) {
                // Aquí usamos el nombre de la tabla pivote en lugar de 'pivot'
                $query->where('campeonato_equipo.campeonato_id', $this->campeonato_id)
                    ->where('campeonato_equipo.grupo_id', $this->grupo_id); // Filtramos por grupo_id en la tabla pivote

            })->get();

            //  dd($this->equipos); // Ver los equipos filtrados
        } else {
            $this->equipos = [];
        }
    }

    //======== validar que el equipo local no sea el mismo que el visitante
    public function updatedEquipoVisitanteId()
    {
        if ($this->equipo_visitante_id === $this->equipo_local_id) {
            $this->addError('equipo_visitante_id', 'El equipo visitante no puede ser el mismo que el equipo local.');
        } else {
            $this->resetErrorBag('equipo_visitante_id');
        }
    }

    //================ actualiza dinamicamente el select visitante
    public function getEquiposVisitantesProperty()
    {
        if (!$this->equipo_local_id) {
            return $this->equipos;
        }

        return collect($this->equipos)->filter(function ($equipo) {
            return $equipo->id != $this->equipo_local_id;
        });
    }

    //============== guardar el fixture

    public function guardarEncuentro()
    {

        try {
            $this->validate([
                'campeonato_id' => 'required|exists:campeonatos,id',
                'grupo_id' => 'nullable|exists:grupos,id',
                'fecha' => 'required|date',
                'hora' => 'required',
                'cancha_id' => 'required|exists:canchas,id',
                'equipo_local_id' => 'required|different:equipo_visitante_id|exists:equipos,id',
                'equipo_visitante_id' => 'required|different:equipo_local_id|exists:equipos,id',
                'estado' => 'required|in:programado,jugado,pendiente',
                'jornada' => 'required|integer|min:1',
            ], [
                'campeonato_id.required' => 'Debe seleccionar un campeonato.',
                'grupo_id.exists' => 'El grupo seleccionado no es válido.',
                'fecha.required' => 'La fecha es obligatoria.',
                'hora.required' => 'La hora es obligatoria.',
                'cancha_id.required' => 'Debe seleccionar una cancha.',
                'equipo_local_id.required' => 'Debe seleccionar el equipo local.',
                'equipo_local_id.different' => 'El equipo local no puede ser el mismo que el visitante.',
                'equipo_visitante_id.required' => 'Debe seleccionar el equipo visitante.',
                'equipo_visitante_id.different' => 'El equipo visitante no puede ser el mismo que el local.',
                'estado.required' => 'Debe seleccionar el estado del encuentro.',
                'estado.in' => 'El estado seleccionado no es válido.',
                'jornada.required' => 'La jornada es obligatoria.',
                'jornada.integer' => 'La jornada debe ser un número.',
                'jornada.min' => 'La jornada debe ser al menos 1.',
            ]);

            $horaEncuentro = Carbon::parse($this->fecha . ' ' . $this->hora);


            // Verifica si un equipo ya juega ese día
            $conflictoEquipo = Encuentro::where('fecha_encuentro', $this->fecha)
                ->where(function ($q) {
                    $q->where('equipo_local_id', $this->equipo_local_id)
                        ->orWhere('equipo_visitante_id', $this->equipo_local_id)
                        ->orWhere('equipo_local_id', $this->equipo_visitante_id)
                        ->orWhere('equipo_visitante_id', $this->equipo_visitante_id);
                })
                ->exists();

            if ($conflictoEquipo) {
                $this->addError('fecha', 'Uno de los equipos ya tiene un partido en esa fecha.');
                return;
            }


            // Verifica si hay un partido en la misma cancha en el mismo horario
            $inicioNuevo = Carbon::parse($this->fecha . ' ' . $this->hora);
            $finNuevo = $inicioNuevo->copy()->addMinutes(60); // Asumiendo 90 minutos de duración

            $conflictoCancha = Encuentro::where('fecha', $this->fecha)
                ->where('cancha_id', $this->cancha_id)
                ->get()
                ->some(function ($encuentro) use ($inicioNuevo, $finNuevo) {
                    $inicioExistente = Carbon::parse($encuentro->fecha . ' ' . $encuentro->hora);
                    $finExistente = $inicioExistente->copy()->addMinutes(60); // Asumiendo 90 minutos de duración

                    // Verificar si hay superposición
                    return ($inicioNuevo < $finExistente && $finNuevo > $inicioExistente);
                });

            if ($conflictoCancha) {
                $this->addError('hora', 'El horario seleccionado se superpone con otro partido en esta cancha.');
                return;
            }

            Encuentro::create([
                'campeonato_id' => $this->campeonato_id,
                'grupo_id' => $this->grupo_id,
                'fecha' => $this->fecha,
                'hora' => $this->hora,
                'cancha_id' => $this->cancha_id,
                'estado' => $this->estado,
                'equipo_local_id' => $this->equipo_local_id,
                'equipo_visitante_id' => $this->equipo_visitante_id,
                'fecha_encuentro' => $this->jornada,
            ]);

            // 3. Mensaje flash y reset
            session()->flash('success', 'Encuentro guardado correctamente.');
            $this->reset([
                'fecha',
                'hora',
                'cancha_id',
                'equipo_local_id',
                'equipo_visitante_id',
                'estado',
                'jornada', // ¿Faltaba este campo?

                // Reseteamos el campeonato_id para que se pueda seleccionar otro
            ]);
        } catch (\Exception $e) {
            logger()->error('Error al guardar: ' . $e->getMessage());
            $this->addError('general', 'Error al guardar el encuentro: ' . $e->getMessage());
        }
    }

    //=============exportar a exel ===============

    public function exportar(Request $request, ServicesEncuentroExportService $servicio)
    {


        $request->validate([
            'campeonato_id' => 'required|exists:campeonatos,id',
            'fecha_encuentro' => 'required',
        ]);

        return $servicio->exportarPorCampeonatoYFecha($request->campeonato_id, $request->fecha_encuentro);
    }


    //==========================================================

    public function render()
    {
        return view('livewire.fixture.fixture-crear');
    }
}
