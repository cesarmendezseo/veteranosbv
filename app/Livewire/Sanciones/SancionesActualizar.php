<?php

namespace App\Livewire\Sanciones;

use App\Models\Campeonato;
use App\Models\Sanciones;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class SancionesActualizar extends Component
{
    use WithPagination;

    public $jugador_id;
    public $campeonato_id;
    public $search = '';
    public $campeonatos = [];
    public $filtroCumplidas = 'todas'; // valores posibles: todas, cumplidas, pendientes


    public function mount()
    {
        $this->campeonatos = \App\Models\Campeonato::orderBy('created_at', 'desc')->get();
    }


    public function sumarFecha()
    {
        // Asegurate de tener definido el campeonato activo
        if (!$this->campeonato_id) {
            LivewireAlert::title('Selecciona un campeonato activo')
                ->warning()
                ->toast()
                ->show();
            return;
        }

        Sanciones::where('campeonato_id', $this->campeonato_id)
            ->whereColumn('partidos_cumplidos', '<', 'partidos_sancionados')
            ->increment('partidos_cumplidos');

        LivewireAlert::title('Fechas sumadas con éxito')
            ->success()
            ->toast()
            ->show();
    }

    public function restarFecha()
    {
        if (!$this->campeonato_id) {
            LivewireAlert::title('Selecciona un campeonato activo')
                ->warning()
                ->toast()
                ->show();
            return;
        }

        DB::statement("
        UPDATE sanciones
        SET partidos_cumplidos = partidos_cumplidos - 1
        WHERE campeonato_id = ?
          AND partidos_cumplidos > 0
    ", [$this->campeonato_id]);

        LivewireAlert::title('Fechas restadas con éxito')
            ->success()
            ->toast()
            ->show();
    }
    public function sumarFechaJugador($jugadorId)
    {


        // Buscar la sanción del jugador dentro del campeonato actual
        $sancion = Sanciones::where('campeonato_id', $this->campeonato_id)
            ->where('jugador_id', $jugadorId)
            ->first();

        if (!$sancion) {
            LivewireAlert::title('Sanción no encontrada')
                ->warning()
                ->toast()
                ->show();
            return;
        }

        // Solo suma si no supera los partidos sancionados
        if ($sancion->partidos_cumplidos < $sancion->partidos_sancionados) {
            $sancion->increment('partidos_cumplidos');
            LivewireAlert::title('Se sumó un partido cumplido')
                ->success()
                ->toast()
                ->show();
        } else {
            LivewireAlert::title('Ya cumplió todos los partidos sancionados')
                ->info()
                ->toast()
                ->show();
        }
    }

    public function restarFechaJugador($jugadorId)
    {
        // Buscar la sanción del jugador dentro del campeonato actual
        $sancion = Sanciones::where('campeonato_id', $this->campeonato_id)
            ->where('jugador_id', $jugadorId)
            ->first();

        if (!$sancion) {
            LivewireAlert::title('Sanción no encontrada')
                ->warning()
                ->toast()
                ->show();
            return;
        }

        // Solo resta si tiene al menos 1 partido cumplido
        if ($sancion->partidos_cumplidos > 0) {
            $sancion->decrement('partidos_cumplidos');
            LivewireAlert::title('Se restó un partido cumplido')
                ->success()
                ->toast()
                ->show();
        } else {
            LivewireAlert::title('No hay partidos cumplidos para restar')
                ->info()
                ->toast()
                ->show();
        }
    }

    public function updatedCampeonatoId($value)
    {

        $this->campeonato_id = $value;
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {

        $sanciones = Sanciones::query()
            /* ->where('campeonato_id', $this->campeonato_id) */
            ->when($this->filtroCumplidas === 'cumplidas', fn($q) =>
            $q->whereColumn('partidos_cumplidos', '>=', 'partidos_sancionados'))
            ->when($this->filtroCumplidas === 'pendientes', fn($q) =>
            $q->whereColumn('partidos_cumplidos', '<', 'partidos_sancionados'))
            ->paginate(20);

        return view('livewire.sanciones.sanciones-actualizar', [
            'sanciones' => $sanciones,
        ]);
    }
}
