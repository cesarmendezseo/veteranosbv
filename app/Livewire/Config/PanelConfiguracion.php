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
    public $mostrarGoleadores;
    public $mostrarSanciones;
    public $tituloPrincipal;
    public $campeonatoPrincipal;

    public function mount()
    {
        // 1. Castea los valores a booleano para los checkboxes
        $this->mostrarTablaPosiciones = (bool) Configuracion::get('mostrar_tabla_posiciones', '1');
        $this->mostrarProximosEncuentros = (bool) Configuracion::get('mostrar_proximos_encuentros', '1');
        $this->mostrarGoleadores = (bool) Configuracion::get('mostrar_goleadores', '1');
        $this->mostrarSanciones = (bool) Configuracion::get('mostrar_sanciones', '1');

        // 2. Los demás valores pueden seguir como strings
        $this->tituloPrincipal = Configuracion::get('titulo_principal', 'Bienvenidos al Torneo');
        $this->campeonatoPrincipal = Configuracion::get('campeonato_principal');
    }

    public function guardar()
    {
        // 1. Al guardar, convierte los booleanos de los checkboxes a string ('1' o '0')
        $tablaPosicionesDB = $this->mostrarTablaPosiciones ? '1' : '0';
        $proximosEncuentrosDB = $this->mostrarProximosEncuentros ? '1' : '0';
        $mostrarGoleadoresDB = $this->mostrarGoleadores ? '1' : '0';
        $mostrarSancionesDB = $this->mostrarSanciones ? '1' : '0';

        Configuracion::set('mostrar_tabla_posiciones', $tablaPosicionesDB);
        Configuracion::set('mostrar_proximos_encuentros', $proximosEncuentrosDB);
        Configuracion::set('mostrar_goleadores', $mostrarGoleadoresDB);
        Configuracion::set('mostrar_sanciones', $mostrarSancionesDB);

        // 2. Los demás valores se guardan directamente
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
