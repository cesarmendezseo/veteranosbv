<?php

namespace App\Livewire\Equipo;

use App\Models\Campeonato;
use App\Models\Equipo;
use App\Models\Jugador;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class ListadoBuenaFeCrear extends Component
{
    public $buscar = '';
    public $jugadores = [];
    public $jugadoresSeleccionados = [];
    public $equipoSeleccionado;
    public $campeonatoSeleccionado;

    public $equipos;
    public $campeonatos;
    public $campeonatoId;
    public $categoria_id;

    public function mount($campeonatoId)
    {

        $this->equipos = Equipo::orderBy('nombre')->get();
        $this->campeonatos = Campeonato::where('id', $campeonatoId)->get();
        $this->categoria_id = $this->campeonatos->first()->categoria_id;
    }

    public function updatedBuscar()
    {
        $this->jugadores = Jugador::where('nombre', 'like', "%{$this->buscar}%")
            ->orWhere('apellido', 'like', "%{$this->buscar}%")
            ->orWhere('documento', 'like', "%{$this->buscar}%")
            ->take(10)
            ->get();
    }

    public function agregarJugador($jugadorId)
    {
        $jugador = Jugador::find($jugadorId);

        if (!$jugador) return;

        // evitar duplicados
        if (!collect($this->jugadoresSeleccionados)->contains('id', $jugador->id)) {
            $this->jugadoresSeleccionados[] = [
                'id' => $jugador->id,
                'nombre' => $jugador->nombre,
                'apellido' => $jugador->apellido,
                'documento' => $jugador->documento,
            ];
        }
        // limpiar input Livewire si existe
        $this->buscar = '';

        // enviar evento al frontend (Livewire 3)
        $this->dispatch('focus-input');
    }

    public function quitarJugador($index)
    {
        unset($this->jugadoresSeleccionados[$index]);
        $this->jugadoresSeleccionados = array_values($this->jugadoresSeleccionados);
    }

    public function guardar()
    {
        $this->validate([
            'equipoSeleccionado' => 'required|exists:equipos,id',
            'jugadoresSeleccionados' => 'required|array|min:1',
        ]);

        // Verificación previa
        $jugadoresIds = collect($this->jugadoresSeleccionados)->pluck('id');

        $jugadoresYaAsignados = DB::table('campeonato_jugador_equipo')
            ->where('campeonato_id', $this->campeonatoId)
            ->whereIn('jugador_id', $jugadoresIds)
            ->where('equipo_id', '!=', $this->equipoSeleccionado)
            ->pluck('jugador_id') //lista de Ids en confilctos
            ->toArray();

        if (!empty($jugadoresYaAsignados)) {
            $nombres = Jugador::whereIn('id', $jugadoresYaAsignados)
                ->get(['nombre', 'apellido'])
                ->map(fn($j) => "{$j->apellido} {$j->nombre}")
                ->implode(', ');

            LivewireAlert::title('Error')
                ->text("Los siguientes jugadores ya están asignados a otro equipo en este campeonato:  $nombres")
                ->error()
                ->asConfirm()
                ->toast()
                ->position('top')
                ->show();

            return;
        }

        // Si todo está OK, guardamos
        DB::transaction(function () {
            foreach ($this->jugadoresSeleccionados as $jugador) {
                DB::table('campeonato_jugador_equipo')->updateOrInsert(
                    [
                        'campeonato_id' => $this->campeonatoId,
                        'equipo_id' => $this->equipoSeleccionado,
                        'jugador_id' => $jugador['id'],
                        'categoria_id' => $this->categoria_id,
                    ],
                    [
                        'fecha_alta' => now(),
                        'fecha_baja' => null,
                        'updated_at' => now(),
                    ]
                );

                Jugador::where('id', $jugador['id']) // nombres para mostrar en alerta
                    ->update(['equipo_id' => $this->equipoSeleccionado]);
            }
        });

        $this->reset(['jugadoresSeleccionados', 'buscar', 'jugadores']);

        LivewireAlert::title('Éxito')
            ->text('La planilla de buena fe se guardó correctamente.')
            ->success()
            ->toast()
            ->position('top')
            ->show();
    }


    public function render()
    {
        return view('livewire.equipo.listado-buena-fe-crear');
    }
}
