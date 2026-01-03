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
    public $filtroCumplidas = 'todas'; // todas | cumplidas | pendientes

    public function mount()
    {
        $this->campeonatos = Campeonato::orderBy('created_at', 'desc')->get();
    }

    /* =========================================================
     *  SUMAR FECHA (MASIVO)
     * ========================================================= */
    public function sumarFecha()
    {


        // 1️⃣ Incrementamos 1 partido cumplido a TODAS las sanciones que aún no terminaron
        Sanciones::where('cumplida', false)
            ->whereColumn('partidos_cumplidos', '<', 'partidos_sancionados')
            ->increment('partidos_cumplidos');

        // 2️⃣ Marcamos como 'cumplida' cualquier sanción que, tras el incremento, alcanzó o superó el tope
        Sanciones::where('cumplida', false)
            ->whereColumn('partidos_cumplidos', '>=', 'partidos_sancionados')
            ->update(['cumplida' => true]);

        LivewireAlert::title('Fechas sumadas con éxito')
            ->success()->toast()->show();
    }

    /* =========================================================
     *  RESTAR FECHA (MASIVO)
     * ========================================================= */
    public function restarFecha()
    {


        // 1️⃣ Restar 1 partido cumplido a TODAS las sanciones que tengan al menos 1 cumplido
        DB::statement("
    UPDATE sanciones 
    SET partidos_cumplidos = partidos_cumplidos - 1 
    WHERE partidos_cumplidos > 0 
      AND cumplida = 1
");

        // 2️⃣ Marcar como NO cumplidas las que, al restar, ya no alcanzan el tope (Global)
        Sanciones::where('cumplida', true)
            ->whereColumn('partidos_cumplidos', '<', 'partidos_sancionados')
            ->update(['cumplida' => false]);

        LivewireAlert::title('Fechas restadas con éxito')
            ->success()->toast()->show();
    }

    /* =========================================================
     *  SUMAR FECHA (INDIVIDUAL)
     * ========================================================= */
    public function sumarFechaJugador($sancionId)
    {
        $sancion = Sanciones::find($sancionId);

        if (!$sancion) {
            LivewireAlert::title('Sanción no encontrada')
                ->warning()->toast()->show();
            return;
        }

        if ($sancion->partidos_cumplidos < $sancion->partidos_sancionados) {
            $sancion->increment('partidos_cumplidos');
            $sancion->refresh();

            if ($sancion->partidos_cumplidos >= $sancion->partidos_sancionados) {
                $sancion->cumplida = true;
                $sancion->save();

                LivewireAlert::title('¡Sanción cumplida completamente!')
                    ->success()->toast()->show();
            } else {
                LivewireAlert::title('Se sumó un partido cumplido')
                    ->success()->toast()->show();
            }
        } else {
            LivewireAlert::title('La sanción ya está cumplida')
                ->info()->toast()->show();
        }
    }

    /* =========================================================
     *  RESTAR FECHA (INDIVIDUAL)
     * ========================================================= */
    public function restarFechaJugador($sancionId)
    {
        $sancion = Sanciones::find($sancionId);

        if (!$sancion) {
            LivewireAlert::title('Sanción no encontrada')
                ->warning()->toast()->show();
            return;
        }

        if ($sancion->partidos_cumplidos > 0) {
            $sancion->decrement('partidos_cumplidos');
            $sancion->refresh();

            if ($sancion->partidos_cumplidos < $sancion->partidos_sancionados) {
                $sancion->cumplida = false;
                $sancion->save();
            }

            LivewireAlert::title('Se restó un partido cumplido')
                ->success()->toast()->show();
        } else {
            LivewireAlert::title('No hay partidos cumplidos para restar')
                ->info()->toast()->show();
        }
    }

    /* =========================================================
     *  FILTROS LIVEWIRE
     * ========================================================= */
    public function updatedCampeonatoId()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    /* =========================================================
     *  RENDER
     * ========================================================= */
    public function render()
    {
        $sanciones = Sanciones::query()
            ->with('jugador')
            ->when($this->search, function ($q) {
                $q->whereHas('jugador', function ($query) {
                    $query->where('nombre', 'like', '%' . $this->search . '%')
                        ->orWhere('apellido', 'like', '%' . $this->search . '%')
                        ->orWhere('documento', 'like', '%' . $this->search . '%');
                });
            })
            ->when(
                $this->campeonato_id,
                fn($q) =>
                $q->where('campeonato_id', $this->campeonato_id)
            )
            ->when(
                $this->filtroCumplidas === 'cumplidas',
                fn($q) =>
                $q->whereColumn('partidos_cumplidos', '>=', 'partidos_sancionados')
            )
            ->when(
                $this->filtroCumplidas === 'pendientes',
                fn($q) =>
                $q->whereColumn('partidos_cumplidos', '<', 'partidos_sancionados')
            )
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('livewire.sanciones.sanciones-actualizar', [
            'sanciones' => $sanciones,
        ]);
    }
}
