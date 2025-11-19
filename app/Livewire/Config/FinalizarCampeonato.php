<?php

namespace App\Livewire\Config;

use App\Models\Campeonato;
use App\Models\Jugador;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class FinalizarCampeonato extends Component
{
    public $campeonatos;
    public $campeonatoId;

    public function mount()
    {
        $this->campeonatos = Campeonato::with('grupos', 'categoria')->get();
    }

    public function finalizarCampeonato($id)
    {
        $this->campeonatoId = $id;

        // Obtener id del equipo “Sin equipo”
        $idSinEquipo = DB::table('equipos')
            ->whereRaw('LOWER(nombre) = ?', ['sin equipo'])
            ->value('id');

        DB::transaction(function () use ($idSinEquipo) {

            // Dar de baja todos los jugadores del campeonato
            DB::table('campeonato_jugador_equipo')
                ->where('campeonato_id', $this->campeonatoId)
                ->whereNull('fecha_baja')
                ->update([
                    'fecha_baja' => now(),
                    'updated_at' => now(),
                ]);

            // Mover jugadores al equipo "Sin equipo"
            Jugador::whereIn(
                'id',
                DB::table('campeonato_jugador_equipo')
                    ->where('campeonato_id', $this->campeonatoId)
                    ->pluck('jugador_id')
            )->update([
                'equipo_id' => $idSinEquipo
            ]);
        });

        LivewireAlert::title('Campeonato Finalizado')
            ->text('Todos los jugadores fueron dados de baja y movidos al equipo SIN EQUIPO.')
            ->success()
            ->toast()
            ->position('top')
            ->show();
    }


    public function render()
    {
        return view('livewire.config.finalizar-campeonato');
    }
}
