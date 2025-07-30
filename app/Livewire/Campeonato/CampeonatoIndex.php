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

        $this->campeonatos = Campeonato::all();
    }



    public function render()
    {
        return view('livewire.campeonato.campeonato-index');
    }
}
