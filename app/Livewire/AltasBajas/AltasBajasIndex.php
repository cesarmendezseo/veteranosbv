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
        $this->jugadorSeleccionado = Jugador::find($jugadorId);
        $this->equipos = DB::table('equipos')->whereNull('deleted_at')->get();

        if ($this->jugadorSeleccionado->equipo_id === null) {
            $this->equipoSeleccionado = null;
            $this->mostrarAlta = true;
        } else {
            $this->dispatch('equipo-existe');
        }
    }

    public function darDeAlta()
    {
        if ($this->jugadorSeleccionado && $this->equipoSeleccionado) {
            DB::table('jugador_equipos')->insert([
                'jugador_id' => $this->jugadorSeleccionado->id,
                'equipo_id' => $this->equipoSeleccionado,
                'fecha_alta' => now()->toDateString(),
                'fecha_baja' => null,
            ]);

            DB::table('jugadors')
                ->where('id', $this->jugadorSeleccionado->id)
                ->update(['equipo_id' => $this->equipoSeleccionado]);

            $this->reset(['mostrarAlta', 'equipoSeleccionado', 'jugadorSeleccionado']);
            $this->dispatch('darDeAlta');
            $this->resetPage();
        }
    }

    #[On('dar-de-baja')]
    public function darDeBaja($jugadorId)
    {
        DB::table('jugador_equipos')
            ->where('jugador_id', $jugadorId)
            ->whereNull('fecha_baja')
            ->update(['fecha_baja' => now()->toDateString()]);

        DB::table('jugadors')
            ->where('id', $jugadorId)
            ->update(['equipo_id' => null]);

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
