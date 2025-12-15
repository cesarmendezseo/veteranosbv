<?php

namespace App\Livewire\Frontend\PaginaPrincipal;

use App\Models\Campeonato;
use Livewire\Component;

class PrincipalIndex extends Component
{
    public $campeonato;

    public function mount($id)
    {
        $this->campeonato = Campeonato::find($id);
    }

    public function render()
    {
        return view('livewire.frontend.pagina-principal.principal-index');
    }
}
