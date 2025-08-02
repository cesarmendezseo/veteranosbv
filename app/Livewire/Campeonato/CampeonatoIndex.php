<?php

namespace App\Livewire\Campeonato;


use App\Models\Campeonato;
use Livewire\Attributes\On;
use Livewire\Component;
use SweetAlert2\Laravel\Swal;

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
        //mensaje de otras paginas
        $this->campeonatos = Campeonato::with('grupos', 'categoria')->get();
    }


    #[On('eliminar-campeonato')]
    public function eliminarCampeonato($id)
    {

        Campeonato::findOrFail($id)->delete();

        $this->dispatch('baja');
        $this->dispatch('refresh');
    }

    #[On('refresh')]
    public function refresh()
    {
        // No hace falta que pongas nada acá, con solo tener este método, Livewire vuelve a renderizar
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
