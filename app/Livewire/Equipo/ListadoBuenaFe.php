<?php

namespace App\Livewire\Equipo;

use App\Exports\ListadoBuenaFeExport;
use App\Models\Campeonato;
use App\Models\Encuentro;
use App\Models\Equipo;
use App\Models\Jugador;
use App\Models\Sanciones;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class ListadoBuenaFe extends Component
{
    public $campeonato;            // un solo campeonato
    public $campeonatoId;          // id recibido
    public $equiposDelCampeonato;  // equipos del campeonato
    public $equipoSeleccionado;    // id del equipo elegido
    public $jugadoresEquipos = []; // jugadores del equipo
    public $fecha;

    public function mount($campeonatoId)
    {
        // Guardo el id
        $this->campeonatoId = $campeonatoId;

        // Cargo el campeonato con sus equipos
        $this->campeonato = Campeonato::with('equipos')->find($campeonatoId);

        // Si existe, cargo los equipos ordenados
        $this->equiposDelCampeonato = $this->campeonato
            ? $this->campeonato->equipos->sortBy('nombre')
            : collect();
    }

    // Cuando se elige un equipo en el select
    public function updatedEquipoSeleccionado($equipoId)
    {
        $this->jugadoresEquipos = Jugador::with(['sanciones' => function ($q) {
            $q->where('cumplida', false);
        }])
            ->where('equipo_id', $equipoId)
            ->where('is_active', true)
            ->orderBy('apellido', 'asc')
            ->get();
    }

    // Exportar a Excel
    public function exportarJugadores()
    {
        $equipo = Equipo::find($this->equipoSeleccionado);
        $nombreTorneo = $this->campeonato->nombre;

        return Excel::download(
            new ListadoBuenaFeExport(
                $this->equipoSeleccionado,
                $nombreTorneo,
                $this->campeonatoId,
                $this->fecha
            ),
            'Fecha-' . $this->fecha . ' ' . Str::slug($equipo->nombre) . '.xlsx'
        );
    }

    // Actualizar sanciones
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

    public function render()
    {
        return view('livewire.equipo.listado-buena-fe');
    }
}
