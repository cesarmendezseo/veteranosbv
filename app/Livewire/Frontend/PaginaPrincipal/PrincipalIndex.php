<?php

namespace App\Livewire\Frontend\PaginaPrincipal;

use App\Models\Campeonato;
use App\Models\Configuracion;
use Livewire\Component;

class PrincipalIndex extends Component
{
    public $campeonato;
    public $campeonatoId;

    public function mount()
    {

        $this->campeonatoId = Configuracion::get('campeonato_principal');
        $this->campeonato = Campeonato::find($this->campeonatoId);
    }

    public function render()
    {
        return view('livewire.frontend.pagina-principal.principal-index');
    }
}
