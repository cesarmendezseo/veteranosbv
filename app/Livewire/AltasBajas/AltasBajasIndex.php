<?php

namespace App\Livewire\AltasBajas;

use App\Models\Jugador;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;


use Livewire\WithPagination;

class AltasBajasIndex extends Component
{
    use WithPagination;

    public $dni = '';
    public $mostrarAlta = false;
    public $jugadorSeleccionado;
    public $equipoSeleccionado;
    public $historial = [];
    public $equipos;

    protected $paginationTheme = 'tailwind';



    public function updatedDni()
    {
        $this->resetPage(); // IMPORTANTE: resetea la paginación al cambiar filtro
        $this->historial = [];
    }

    public function buscar()
    {
        // Este método ya no es necesario, puedes eliminarlo o usarlo sólo para otras cosas,
        // porque la paginación debe hacerse en render()
    }

    public function mostrarFormularioAlta($jugadorId)
    {

        // 1️⃣ Buscar el jugador
        $this->jugadorSeleccionado = Jugador::with('equipo')->find($jugadorId);

        // 2️⃣ Obtener todos los equipos disponibles (sin filtrar por deleted_at si no existe la columna)
        $this->equipos = DB::table('equipos')->get();

        // 3️⃣ Verificar si el jugador no tiene equipo o su equipo es "Sin equipo"
        if (!$this->jugadorSeleccionado->equipo || $this->jugadorSeleccionado->equipo->nombre === 'sin equipo') {
            $this->equipoSeleccionado = $this->jugadorSeleccionado->equipo?->nombre ?? null;
            $this->mostrarAlta = true;
        } else {
            // 4️⃣ Si ya tiene equipo asignado distinto de "Sin equipo"
            $this->dispatch('equipo-existe');
        }
    }

    public function darDeAlta()
    {
        if ($this->jugadorSeleccionado && $this->equipoSeleccionado) {

            // 🔹 Buscar el equipo llamado "Sin equipo"
            $equipoSinEquipo = DB::table('equipos')->where('nombre', 'Sin equipo')->first();

            if ($equipoSinEquipo) {
                // 🔹 Marcar fecha_baja del equipo "Sin equipo" si existe para este jugador

                DB::table('jugador_equipos')
                    ->where('jugador_id', $this->jugadorSeleccionado->id)
                    ->where('equipo_id', $equipoSinEquipo->id)
                    ->whereNull('fecha_baja')
                    ->update(['fecha_baja' => now()->toDateString()]);
            } else {
                // 🔹 Si no existe el equipo "Sin equipo", disparar evento o mensaje
                $this->dispatch('equipo-sin-equipo-falta');
                return; // cancelar la operación
            }

            // 🔹 Insertar el nuevo registro en jugador_equipos
            DB::table('jugador_equipos')->insert([
                'jugador_id' => $this->jugadorSeleccionado->id,
                'equipo_id' => $this->equipoSeleccionado,
                'fecha_alta' => now()->toDateString(),
                'fecha_baja' => null,
            ]);


            // 🔹 Actualizar el campo equipo_id en jugadors
            DB::table('jugadors')
                ->where('id', $this->jugadorSeleccionado->id)
                ->update(['equipo_id' => $this->equipoSeleccionado]);

            // 🔹 Limpiar variables y reset
            $this->reset(['mostrarAlta', 'equipoSeleccionado', 'jugadorSeleccionado']);
            $this->dispatch('darDeAlta');
            $this->resetPage();
        }
    }


    #[On('dar-de-baja')]
    public function darDeBaja($jugadorId)
    {
        // Buscar el equipo "Sin equipo"
        $equipoPorDefecto = DB::table('equipos')->where('nombre', 'Sin equipo')->first();

        if (!$equipoPorDefecto) {
            // Si no existe, mostrar mensaje y salir
            $this->dispatch('error', 'Debe crear un equipo llamado "Sin equipo" antes de dar de baja.');
            return;
        }

        $equipoId = $equipoPorDefecto->id;

        // Cerrar la relación actual en historial
        DB::table('jugador_equipos')
            ->where('jugador_id', $jugadorId)
            ->whereNull('fecha_baja')
            ->update(['fecha_baja' => now()->toDateString()]);

        // Insertar nuevo registro en historial con el equipo por defecto
        DB::table('jugador_equipos')->insert([
            'jugador_id' => $jugadorId,
            'equipo_id' => $equipoId,
            'fecha_alta' => now()->toDateString(),
            'fecha_baja' => null,
        ]);

        // Actualizar el jugador
        DB::table('jugadors')
            ->where('id', $jugadorId)
            ->update(['equipo_id' => $equipoId]);

        $this->dispatch('Baja');
    }

    public function verHistorial($jugadorId)
    {
        $this->historial = DB::table('jugador_equipos')
            ->join('equipos', 'jugador_equipos.equipo_id', '=', 'equipos.id')
            ->where('jugador_equipos.jugador_id', $jugadorId)
            ->orderByRaw('fecha_baja IS NOT NULL, fecha_baja DESC')
            ->get([
                'equipos.nombre as equipo',
                'jugador_equipos.fecha_alta',
                'jugador_equipos.fecha_baja',
            ]);
    }

    public function render()
    {

        $jugadores = Jugador::with('equipo')
            ->when($this->dni, fn($q) => $q->where('documento', 'like', '%' . $this->dni . '%'))
            ->paginate(10);

        return view('livewire.altas-bajas.altas-bajas-index', compact('jugadores'));
    }
}
