<?php

namespace App\Livewire\Frontend;

use App\Models\Configuracion;
use Livewire\Component;

class PaginaPrincipal extends Component
{
    public function render()
    {
        // 1. Define las variables que se pasarán a la vista
        $data = [
            'campeonatoSeleccionado' => Configuracion::get('campeonato_principal'),
            'mostrarTabla'           => Configuracion::get('mostrar_tabla_posiciones'),
            'mostrarEncuentros'      => Configuracion::get('mostrar_proximos_encuentros'),
            'tituloPrincipal'        => Configuracion::get('titulo_principal'),
        ];

        // 2. Utiliza dd() para inspeccionar el array de datos
        //    Esto detendrá la ejecución y mostrará el contenido de $data


        // 3. Si no usas dd() (o lo comentas/eliminas), se devuelve la vista con los datos
        return view('livewire.frontend.pagina-principal', $data);
    }
}
