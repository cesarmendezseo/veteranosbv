<?php

namespace App\Livewire\Estadistica;

use App\Models\EstadisticaJugadorEncuentro;
use Livewire\Component;
use Livewire\WithPagination;

class TarjetasAmarilla extends Component
{
    public $amarillas;
    public $jugadorBuscadoAmarilla;
    public $dni;
    public $buscarAmarillas;
    public $dniJugador;
    public $search;

    use WithPagination;

    public function mount()
    {
        $this->amarillas = EstadisticaJugadorEncuentro::with('jugador', 'encuentro')
            ->where('tarjeta_amarilla', '>=', 1)
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
                ->where('tarjeta_amarilla', '>=', 1)
                ->get();
        } else {
            $this->jugadorBuscadoAmarilla = [];
        }
    }

    public function render()
    {
        return view('livewire.estadistica.tarjetas-amarilla');
    }
}
