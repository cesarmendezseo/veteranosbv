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
    public $jugadores;
    public $jugadoresSeleccionados = [];
    public $equipoSeleccionado;
    public $campeonatoSeleccionado;
    public $jugadorSeleccionado;
    public $jugador_id;
    public $nombreJugador;
    public $buscarJugador;

    public $equipos;
    public $campeonatos;
    public $campeonatoId;
    public $categoria_id;
    public $campeonato;

    public function mount($campeonatoId)
    {
        $this->campeonatoId = $campeonatoId;

        // 1. OBTENER EL MODELO SINGULAR: Usa findOrFail o find.
        // Esto te da el objeto Campeonato, no una Colección.
        $campeonato = Campeonato::findOrFail($campeonatoId);

        // Almacenamos el modelo singular en la propiedad
        $this->campeonato = $campeonato;

        // 2. ACCEDER A LA RELACIÓN: Esto solo funciona en el modelo singular.
        // Acceder a la relación 'equipos' del campeonato.
        $this->equipos = $campeonato->equipos;

        // 3. Obtener el resto de datos

        $this->categoria_id = $campeonato->categoria_id;
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
        // Verificar que $jugadores sea colección y no esté vacía
        if ($this->jugadores->isEmpty()) {
            return;
        }

        // Buscar el jugador en la colección filtrada
        $jugador = $this->jugadores->firstWhere('id', $jugadorId);

        if (!$jugador) {
            return;
        }

        // Evitar agregar jugador ya seleccionado (duplicado)
        if (collect($this->jugadoresSeleccionados)->pluck('id')->contains($jugadorId)) {
            return;
        }

        // Agregar el jugador seleccionado a la lista de seleccionados (como array)
        $this->jugadoresSeleccionados[] = $jugador->toArray();

        // Obtener el nombre del equipo o "Sin Equipo"
        $nombreEquipo = $jugador->equipo?->nombre ?? 'Sin Equipo';

        // Construir nombre completo para mostrar (opcional)
        $this->nombreJugador = $jugador->nombreCompleto . ' - ' . $nombreEquipo;

        // Limpiar el campo de búsqueda y la lista filtrada para ocultar resultados
        $this->buscar = '';
        $this->jugadores = collect();
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

        $idSinEquipo = DB::table('equipos')
            ->whereRaw('LOWER(nombre) = ?', ['sin equipo'])
            ->value('id');

        $jugadoresIds = collect($this->jugadoresSeleccionados)->pluck('id');

        // 1️⃣ Verificar si ya pertenecen a otro equipo ACTIVO
        $jugadoresYaAsignados = DB::table('campeonato_jugador_equipo')
            ->where('campeonato_id', $this->campeonatoId)
            ->whereIn('jugador_id', $jugadoresIds)
            ->whereNull('fecha_baja')
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

        // 2️⃣ Verificar jugadores inactivos
        $jugadoresActivos = Jugador::whereIn('id', $jugadoresIds)
            ->where('is_active', 0)
            ->pluck('id')
            ->toArray();

        if (!empty($jugadoresActivos)) {
            $nombresActivos = Jugador::whereIn('id', $jugadoresActivos)
                ->get(['nombre', 'apellido'])
                ->map(fn($j) => "{$j->apellido} {$j->nombre}")
                ->implode(', ');

            LivewireAlert::title('Error')
                ->text("Los siguientes jugadores no se pueden agregar por que estan INACTIVOS: $nombresActivos")
                ->error()->asConfirm()->toast()->position('top')->show();

            return;
        }

        // 3️⃣ Guardar historial
        DB::transaction(function () {
            foreach ($this->jugadoresSeleccionados as $jugador) {
                $existe = DB::table('campeonato_jugador_equipo')
                    ->where('campeonato_id', $this->campeonatoId)
                    ->where('equipo_id', $this->equipoSeleccionado)
                    ->where('jugador_id', $jugador['id'])
                    ->whereNull('fecha_baja')
                    ->first();

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
