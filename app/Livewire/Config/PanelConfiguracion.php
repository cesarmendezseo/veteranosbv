<?php

namespace App\Livewire\Config;

use App\Models\Configuracion;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert as FacadesLivewireAlert;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithFileUploads;
use Livewire\Component;

class PanelConfiguracion extends Component
{


    public $mostrarTablaPosiciones;
    public $mostrarProximosEncuentros;
    public $mostrarGoleadores;
    public $mostrarSanciones;
    public $tituloPrincipal;
    public $leyendaPrincipal1;
    public $leyendaPrincipal2;
    public $leyendaPrincipal3;
    public $leyendaPrincipalColor1;
    public $leyendaPrincipalColor2;
    public $leyendaPrincipalColor3;
    public $leyendaPrincipalFuente1;
    public $leyendaPrincipalFuente2;
    public $leyendaPrincipalFuente3;
    public $campeonatoPrincipal;
    public $titulo;
    public $logo;
    public $textoFooter;
    public $tituloFuente;
    public $tituloColor;
    public $fuentes_disponibles = [
        'sans' => 'Sans',
        'front' => 'Front',
        'titulo' => 'Titulo',
        'select' => 'Select ',
        'leyenda' => 'Leyenda',
        'titan' => 'Titan',
        'passion' => 'Pasion',
        'cookie' => 'Cookie',
        'asul' => 'Asul',
        'gelasio' => 'Gelasio',
    ];
    public $tituloSize;
    public $tituloWeight;
    public $tituloSizeMovil;
    public $tituloWeightMovil;

    public $leyendaSize1;
    public $leyendaWeight1;


    public $leyendaSize2;
    public $leyendaWeight2;

    public $leyendaSize3;
    public $leyendaWeight3;

    public $fondoPaginaPrincipal;

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
        $this->textoFooter = Configuracion::get('texto_footer', 'Derechos Reservados');
        $this->leyendaPrincipal1 = Configuracion::get('leyenda_principal1', 'Sistema de Gestión de Torneos Deportivos');
        $this->leyendaPrincipal2 = Configuracion::get('leyenda_principal2', 'Organiza y administra tus torneos fácilmente');
        $this->leyendaPrincipal3 = Configuracion::get('leyenda_principal3', '¡Únete a la comunidad deportiva hoy mismo!');
        $this->tituloFuente = Configuracion::get('titulo_fuente', 'titulo');
        $this->tituloColor = Configuracion::get('titulo_color', '#FFFFFF');
        $this->leyendaPrincipalColor1 = Configuracion::get('leyenda_principal_color_1', '#FFFFFF');
        $this->leyendaPrincipalColor2 = Configuracion::get('leyenda_principal_color_2', '#FFFFFF');
        $this->leyendaPrincipalColor3 = Configuracion::get('leyenda_principal_color_3', '#FFFFFF');
        $this->leyendaPrincipalFuente1 = Configuracion::get('leyenda_principal_fuente_1', 'leyenda');
        $this->leyendaPrincipalFuente2 = Configuracion::get('leyenda_principal_fuente_2', 'leyenda');
        $this->leyendaPrincipalFuente3 = Configuracion::get('leyenda_principal_fuente_3', 'leyenda');


        $this->titulo = Configuracion::get('titulo', 'Sistema de Torneo');
        $this->logo   = Configuracion::get('logo', null);
        $this->fondoPaginaPrincipal   = Configuracion::get('fondo_pagina_principal', null);

        $this->tituloSize = Configuracion::get('titulo_size', '40px');
        $this->tituloWeight = Configuracion::get('titulo_weight', '700');

        $this->leyendaSize1 = Configuracion::get('leyenda_size_1', '20px');
        $this->leyendaWeight1 = Configuracion::get('leyenda_weight_1', '400');

        $this->leyendaSize2 = Configuracion::get('leyenda_size_2', '20px');
        $this->leyendaWeight2 = Configuracion::get('leyenda_weight_2', '400');

        $this->leyendaSize3 = Configuracion::get('leyenda_size_3', '20px');
        $this->leyendaWeight3 = Configuracion::get('leyenda_weight_3', '400');
    }

    public function guardar()
    {


        // 1. Al guardar, convierte los booleanos de los checkboxes a string ('1' o '0')
        $tablaPosicionesDB     = $this->mostrarTablaPosiciones ? '1' : '0';
        $proximosEncuentrosDB  = $this->mostrarProximosEncuentros ? '1' : '0';
        $mostrarGoleadoresDB   = $this->mostrarGoleadores ? '1' : '0';
        $mostrarSancionesDB    = $this->mostrarSanciones ? '1' : '0';

        Configuracion::set('mostrar_tabla_posiciones', $tablaPosicionesDB);
        Configuracion::set('mostrar_proximos_encuentros', $proximosEncuentrosDB);
        Configuracion::set('mostrar_goleadores', $mostrarGoleadoresDB);
        Configuracion::set('mostrar_sanciones', $mostrarSancionesDB);

        // 2. Los demás valores se guardan directamente
        Configuracion::set('titulo_principal', $this->tituloPrincipal);
        Configuracion::set('campeonato_principal', $this->campeonatoPrincipal);
        Configuracion::set('texto_footer', $this->textoFooter);
        Configuracion::set('titulo_fuente', $this->tituloFuente);
        Configuracion::set('titulo_color', $this->tituloColor);
        Configuracion::set('leyenda_principal1', $this->leyendaPrincipal1);
        Configuracion::set('leyenda_principal2', $this->leyendaPrincipal2);
        Configuracion::set('leyenda_principal3', $this->leyendaPrincipal3);
        Configuracion::set('leyenda_principal_color_1', $this->leyendaPrincipalColor1);
        Configuracion::set('leyenda_principal_color_2', $this->leyendaPrincipalColor2);
        Configuracion::set('leyenda_principal_color_3', $this->leyendaPrincipalColor3);
        Configuracion::set('leyenda_principal_fuente_1', $this->leyendaPrincipalFuente1);
        Configuracion::set('leyenda_principal_fuente_2', $this->leyendaPrincipalFuente2);
        Configuracion::set('leyenda_principal_fuente_3', $this->leyendaPrincipalFuente3);

        Configuracion::set('titulo_size', $this->tituloSize);
        Configuracion::set('titulo_weight', $this->tituloWeight);

        Configuracion::set('leyenda_size_1', $this->leyendaSize1);
        Configuracion::set('leyenda_weight_1', $this->leyendaWeight1);

        Configuracion::set('leyenda_size_2', $this->leyendaSize2);
        Configuracion::set('leyenda_weight_2', $this->leyendaWeight2);

        Configuracion::set('leyenda_size_3', $this->leyendaSize3);
        Configuracion::set('leyenda_weight_3', $this->leyendaWeight3);

        // 🔹 Aquí faltaba guardar el título y el logo
        Configuracion::set('titulo', $this->titulo);
        Configuracion::set('logo', $this->logo);
        Configuracion::set('fondo_pagina_principal', $this->fondoPaginaPrincipal);

        FacadesLivewireAlert::title('Configuración guardada correctamente.')
            ->text('Los cambios han sido aplicados.')
            ->toast()
            ->success()
            ->position('center')
            ->show();
    }



    public function render()
    {
        return view('livewire.config.panel-configuracion');
    }
}
