<?php

namespace App\Livewire\Estadistica;

use App\Models\EstadisticaJugadorEncuentro;
use Livewire\Component;

class TarjetasDobleAmarilla extends Component
{
    public $dobleAmarillas;
    public $jugadorBuscadoDobleAmarilla;
    public $dni;
    public $buscarDobleAmarillas;
    public $dniJugador;
    public $amarillas;
    public $jugadorBuscadoAmarilla;
    public $buscarAmarillas;
    public $search;

    public function mount()
    {
        $this->dobleAmarillas = EstadisticaJugadorEncuentro::with('jugador', 'encuentro')
            ->where('tarjeta_doble_amarilla', '>=', 1)
            ->orderByDesc('jugador_id')
            ->get();
    }

    public function buscar()
    {
        if (trim($this->search) === '') {
            // Si la búsqueda está vacía, simplemente se reinicia la paginación
            $this->resetPage();
        } else {
            // Si hay texto, también reinicia la página para mostrar resultados desde la primera página
            $this->resetPage();
        }
    }

    public function updatingSearch()
    {
        $this->resetPage(); // Resetea a la primera página cuando se cambia el filtro
    }

    public function buscarJugadorAmarilla()
    {
        if (!empty($this->buscarAmarillas)) {
            $this->jugadorBuscadoAmarilla = EstadisticaJugadorEncuentro::with('jugador')
                ->whereHas('jugador', function ($query) {
                    $query->where('documento', 'like', '%' . $this->buscarAmarillas . '%')
                        ->orWhere('apellido', 'like', '%' . $this->buscarAmarillas . '%')
                        ->orWhere('nombre', 'like', '%' . $this->buscarAmarillas . '%');
                })
                ->where('tarjeta_doble_amarilla', '>=', 1)
                ->get();
        } else {
            $this->jugadorBuscadoAmarilla = [];
        }
    }
    public function render()
    {
        return view('livewire.estadistica.tarjetas-doble-amarilla');
    }
}
