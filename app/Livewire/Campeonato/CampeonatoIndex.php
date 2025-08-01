<?php

namespace App\Livewire\Campeonato;

use App\Models\Campeonato;
use Livewire\Component;

class CampeonatoIndex extends Component
{
    public $campeonatos;
    public $campeonatoSeleccionado;
    public $nombre;
    public $formato;
    public $cantidadEquipos;
    public $cantidadGrupos;
    public $categoria = 'A';
    public $puntosGanados;
    public $puntosEmpatados;
    public $puntosPerdidos;
    public $status;



    public function mount()
    {

        $this->campeonatos = Campeonato::with('grupos', 'categoria')->get();
    }

    public function borrar($campeonatoId)
    {
        $campeonato = Campeonato::find($campeonatoId);
        if ($campeonato) {
            $campeonato->delete();
            session()->flash('success', 'Campeonato eliminado correctamente.');
            $this->campeonatos = Campeonato::with('grupos')->get();
        } else {
            session()->flash('error', 'Campeonato no encontrado.');
        }
    }

    public function crear()
    {
        return redirect()->route('campeonato.crear');
    }

    public function verCampeonato($campeonatoId)
    {

        $this->campeonatoSeleccionado = \App\Models\Campeonato::with('grupos', 'criterioDesempate')->find($campeonatoId);

        $this->dispatch('static-modal');
    }

    public function render()
    {
        return view('livewire.campeonato.campeonato-index');
    }
}
