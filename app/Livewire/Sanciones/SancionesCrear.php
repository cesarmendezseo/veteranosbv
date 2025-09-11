<?php

namespace App\Livewire\Sanciones;

use App\Models\Campeonato;
use App\Models\Encuentro;
use App\Models\Jugador;
use App\Models\Sanciones;
use Livewire\Component;

class SancionesCrear extends Component
{
    public $jugador_id;
    public $campeonato_id;
    public $campeonatoId;
    public $fecha_sancion;
    public $partidos_sancionados = 1;
    public $nombreJugador;

    public $observacion;
    public $motivo;

    public $jugadores;
    public $campeonatos;
    public $jugadorBuscadoSancion;
    public $buscarJugador;

    public function mount($campeonatoId)
    {
        $this->campeonatoId = $campeonatoId;

        $this->jugadores = [];
    }

    /*   public function index()
    {
        $this->jugadores = Jugador::with('equipo')->get();
    } */

    //=========================================================
    public function buscarJugadorSancion()
    {

        if (!empty($this->buscarJugador)) {

            $this->jugadores = Jugador::where(function ($query) {
                $query->where('documento', 'like', '%' . trim($this->buscarJugador) . '%')
                    ->orWhere('apellido', 'like', '%' . trim($this->buscarJugador) . '%')
                    ->orWhere('nombre', 'like', '%' . trim($this->buscarJugador) . '%');
            })->get();
        } else {

            $this->jugadores = [];
        }
    }

    //======================================================================
    public function guardar()
    {
        try {
            $this->validate([
                'jugador_id' => 'required|exists:jugadors,id',

                'fecha_sancion' => 'required|integer|min:1',
                'partidos_sancionados' => 'required|integer|min:1',
                'motivo' => 'required|string',
                'observacion' => 'nullable|string'
            ]);

            Sanciones::create([
                'jugador_id' => $this->jugador_id,
                'campeonato_id' => $this->campeonatoId,
                'fecha_sancion' => $this->fecha_sancion,
                'partidos_sancionados' => $this->partidos_sancionados,
                'partidos_cumplidos' => 0,
                'cumplida' => false,
                'motivo' => $this->motivo,
                'observacion' => $this->observacion,
            ]);

            $this->dispatch('guardarSancion');

            $this->reset([
                'jugador_id',

                'fecha_sancion',
                'partidos_sancionados',
                'motivo',
                'observacion'
            ]);
            $this->jugadores = [];
        } catch (\Illuminate\Validation\ValidationException $e) {
            dd($e);
            // Errores de validación (ya los maneja Livewire automáticamente)
            $this->setErrorBag($e->validator->getMessageBag());
        }
    }

    //======================================================
    public function actualizarCumplimientosSanciones()
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



    //=====================================================
    public function render()
    {
        return view('livewire.sanciones.sanciones-crear');
    }
}
