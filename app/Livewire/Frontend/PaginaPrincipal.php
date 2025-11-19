<?php

namespace App\Livewire\Frontend;

use App\Models\Configuracion;
use Livewire\Component;

class PaginaPrincipal extends Component
{
    public function render()
    {
        return view('livewire.frontend.pagina-principal', [
            'campeonatoSeleccionado' => Configuracion::get('campeonato_principal'),
            'mostrarTabla'   => Configuracion::get('mostrar_tabla_posiciones'),
            'mostrarEncuentros' => Configuracion::get('mostrar_proximos_encuentros'),
            'tituloPrincipal' => Configuracion::get('titulo_principal'),
        ]);
    }
}
