<?php

namespace App\Livewire\Config;

use App\Models\Configuracion;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert as FacadesLivewireAlert;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class PanelConfiguracion extends Component
{
    public $mostrarTablaPosiciones;
    public $mostrarProximosEncuentros;
    public $tituloPrincipal;
    public $campeonatoPrincipal;

    public function mount()
    {
        $this->mostrarTablaPosiciones = Configuracion::get('mostrar_tabla_posiciones', '1');
        $this->mostrarProximosEncuentros = Configuracion::get('mostrar_proximos_encuentros', '1');
        $this->tituloPrincipal = Configuracion::get('titulo_principal', 'Bienvenidos al Torneo');
        $this->campeonatoPrincipal = Configuracion::get('campeonato_principal');
    }

    public function guardar()
    {
        Configuracion::set('mostrar_tabla_posiciones', $this->mostrarTablaPosiciones);
        Configuracion::set('mostrar_proximos_encuentros', $this->mostrarProximosEncuentros);
        Configuracion::set('titulo_principal', $this->tituloPrincipal);
        Configuracion::set('campeonato_principal', $this->campeonatoPrincipal);


        FacadesLivewireAlert::title('Configuración guardada correctamente.')
            ->text('Los cambios han sido aplicados.')
            ->toast()
            ->position('center')
            ->show();
    }
    public function render()
    {
        return view('livewire.config.panel-configuracion');
    }
}
