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
        $this->campeonatoId = $campeonatoId;

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

        if (!collect($this->jugadoresSeleccionados)->contains('id', $jugador->id)) {
            $this->jugadoresSeleccionados[] = [
                'id' => $jugador->id,
                'nombre' => $jugador->nombre,
                'apellido' => $jugador->apellido,
                'documento' => $jugador->documento,
            ];
        }

        $this->buscar = '';
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

        // Obtener ID del equipo "Sin equipo"
        $idSinEquipo = DB::table('equipos')
            ->whereRaw('LOWER(nombre) = ?', ['sin equipo'])
            ->value('id');

        $jugadoresIds = collect($this->jugadoresSeleccionados)->pluck('id');

        //------------------------------------------
        // 1️⃣ Verificar si ya pertenecen a otro equipo ACTIVO
        //------------------------------------------
        $jugadoresYaAsignados = DB::table('campeonato_jugador_equipo')
            ->where('campeonato_id', $this->campeonatoId)
            ->whereIn('jugador_id', $jugadoresIds)
            ->whereNull('fecha_baja')                 // activo
            ->where('equipo_id', '!=', $this->equipoSeleccionado)
            ->where('equipo_id', '!=', $idSinEquipo)
            ->pluck('jugador_id')
            ->toArray();

        if (!empty($jugadoresYaAsignados)) {
            $nombres = Jugador::whereIn('id', $jugadoresYaAsignados)
                ->get(['nombre', 'apellido'])
                ->map(fn($j) => "{$j->apellido} {$j->nombre}")
                ->implode(', ');

            LivewireAlert::title('Error')
                ->text("Los siguientes jugadores ya están asignados a otro equipo en este campeonato:  $nombres")
                ->error()->asConfirm()->toast()->position('top')->show();

            return;
        }

        //------------------------------------------
        // 2️⃣ Jugadores con is_active = 1 NO pueden cargarse
        //------------------------------------------
        $jugadoresActivos = Jugador::whereIn('id', $jugadoresIds)
            ->where('is_active', 0)      // activo = prohibido cargar
            ->pluck('id')
            ->toArray();

        if (!empty($jugadoresActivos)) {
            $nombresActivos = Jugador::whereIn('id', $jugadoresActivos)
                ->get(['nombre', 'apellido'])
                ->map(fn($j) => "{$j->apellido} {$j->nombre}")
                ->implode(', ');

            LivewireAlert::title('Error')
                ->text("Los siguientes jugadores están marcados como ACTIVOS y no se pueden agregar: $nombresActivos")
                ->error()->asConfirm()->toast()->position('top')->show();

            return;
        }

        //------------------------------------------
        // 3️⃣ Guardar historial
        // Cada alta crea un registro nuevo
        //------------------------------------------
        DB::transaction(function () {
            foreach ($this->jugadoresSeleccionados as $jugador) {

                // Verificar si ya está ACTIVO en este equipo
                $existe = DB::table('campeonato_jugador_equipo')
                    ->where('campeonato_id', $this->campeonatoId)
                    ->where('equipo_id', $this->equipoSeleccionado)
                    ->where('jugador_id', $jugador['id'])
                    ->whereNull('fecha_baja')
                    ->first();

                // Si no está activo → crear historial nuevo
                if (!$existe) {
                    DB::table('campeonato_jugador_equipo')->insert([
                        'campeonato_id' => $this->campeonatoId,
                        'equipo_id' => $this->equipoSeleccionado,
                        'jugador_id' => $jugador['id'],
                        'categoria_id' => $this->categoria_id,
                        'fecha_alta' => now(),
                        'fecha_baja' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Actualiza el equipo actual del jugador
                Jugador::where('id', $jugador['id'])
                    ->update(['equipo_id' => $this->equipoSeleccionado]);
            }
        });

        $this->reset(['jugadoresSeleccionados', 'buscar', 'jugadores']);

        LivewireAlert::title('Éxito')
            ->text('La planilla de buena fe se guardó correctamente.')
            ->success()->toast()->position('top')->show();
    }

    public function render()
    {
        return view('livewire.equipo.listado-buena-fe-crear');
    }
}
