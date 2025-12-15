<?php

namespace App\Livewire\Frontend;

use App\Models\Campeonato;
use App\Models\Configuracion;
use Livewire\Component;

class PaginaPrincipal extends Component
{
    public $campeonato;

    public function mount()
    {
        $this->campeonato = Campeonato::where('finalizado', false)->get();

        // Aquí puedes inicializar cualquier lógica necesaria al montar el componente
    }


    public function render()
    {
        // 1. Define las variables que se pasarán a la vista
        $data = [
            'campeonatoSeleccionado' => Configuracion::get('campeonato_principal'),
            'mostrarTabla'           => Configuracion::get('mostrar_tabla_posiciones'),
            'mostrarEncuentros'      => Configuracion::get('mostrar_proximos_encuentros'),
            'tituloPrincipal'        => Configuracion::get('titulo_principal'),
            'mostrarGoleadores'      => Configuracion::get('mostrar_goleadores'),
            'mostrarSanciones'       => Configuracion::get('mostrar_sanciones'),
        ];

        // 2. Utiliza dd() para inspeccionar el array de datos
        //    Esto detendrá la ejecución y mostrará el contenido de $data


        // 3. Si no usas dd() (o lo comentas/eliminas), se devuelve la vista con los datos
        return view('livewire.frontend.pagina-principal', $data);
    }
}
