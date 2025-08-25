<?php

namespace App\Livewire\Equipo;

use App\Exports\ListadoBuenaFeExport;
use App\Livewire\JugadoresTable;
use App\Models\Campeonato;
use App\Models\Encuentro;
use App\Models\Equipo;
use App\Models\Grupo;
use App\Models\Jugador;
use App\Models\Sanciones;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str; // Para Str::slug()

class ListadoBuenaFe extends Component
{
    public $aniosDisponibles;
    public $campeonatos;
    public $campeonato_id;
    public $grupos = [];
    public $grupoSeleccionado;
    public $equiposDelGrupo = [];
    public $mensajeAnioSeleccionado = '';
    public $anioSeleccionado;
    public $equiposSeleccionado;
    public $jugadoresEquipos = [];
    public $nombreTorneo;
    public $fecha;
    public $equipoElegido;
    public $campeonatoId;

    public function mount($campeonatoId)
    {

        $this->campeonatos = Campeonato::all();

        // Obtener el último campeonato
        $ultimoCampeonato = Campeonato::latest()->first();

        // Selecciona el ID si hay
        $this->campeonato_id = $ultimoCampeonato ? $ultimoCampeonato->id : null;

        // Años disponibles
        $this->aniosDisponibles = Campeonato::selectRaw('YEAR(created_at) as anio')
            ->distinct()
            ->orderByDesc('anio')
            ->pluck('anio')
            ->toArray();

        $this->anioSeleccionado = !empty($this->aniosDisponibles)
            ? $this->aniosDisponibles[0]
            : null;

        // Cargar equipos si hay un campeonato válido
        if ($this->campeonatoId) {
            $campeonato = Campeonato::find($this->campeonatoId);
            $this->equiposDelGrupo = $campeonato->equipos->sortBy('nombre');
        } else {
            $this->equiposDelGrupo = collect(); // o []
        }
    }
    //===================================================================================
    public function updatedAnioSeleccionado($nuevoValor)
    {

        $this->campeonatos = Campeonato::whereYear('created_at', $nuevoValor)->get();
    }

    //================================================================================
    public function updatedCampeonatoId()
    {

        $campeonatos = Campeonato::find($this->campeonatoId);

        $this->equiposDelGrupo = $campeonatos->equipos->sortBy('nombre');
    }

    //=================================================================================
    public function updatedEquiposSeleccionado()
    {

        $this->jugadoresEquipos = Jugador::with(['sanciones' => function ($q) {
            $q->where('cumplida', false);
        }])
            ->where('equipo_id', $this->equiposSeleccionado)
            ->where('is_active', true)
            ->orderBy('apellido', 'asc')
            ->get();

        $this->equipoElegido = $this->equiposSeleccionado;
    }

    //=================================================================================
    public function exportarJugadores()
    {
        //obtengo el nombre del torneo
        $nombreTorneo = $this->campeonatos->first()->nombre;
        $equipo = Equipo::find($this->equiposSeleccionado);
        $fecha = $this->fecha;


        return Excel::download(
            new ListadoBuenaFeExport(
                $this->equiposSeleccionado,
                $nombreTorneo,
                $this->campeonato_id,
                $this->fecha
            ),
            'Fecha-' . $fecha . ' ' . Str::slug($equipo->nombre) . '.xlsx' // Nombre legible y sin espacios
        );
    }

    //=================================================================
    public function actualizarSanciones()
    {
        $sanciones = Sanciones::where('cumplida', false)->get();


        foreach ($sanciones as $sancion) {
            $jugador = $sancion->jugador;
            $equipo = $jugador->equipo_id;

            $encuentros = Encuentro::where('campeonato_id', $sancion->campeonato_id)
                ->where('estado', 'Jugado')
                ->where('fecha_encuentro', '>', $sancion->fecha_sancion)
                ->where(function ($q) use ($equipo) {
                    $q->where('equipo_local_id', $equipo)
                        ->orWhere('equipo_visitante_id', $equipo);
                })
                ->orderBy('fecha_encuentro')
                ->get();

            $partidosCumplidos = $encuentros->count();

            $sancion->partidos_cumplidos = $partidosCumplidos;
            $sancion->cumplida = $partidosCumplidos >= $sancion->partidos_sancionados;
            $sancion->save();
        }
        $this->dispatch('actualizar-sancion');
    }

    //=================================================================
    public function render()
    {
        return view('livewire.equipo.listado-buena-fe');
    }
}
