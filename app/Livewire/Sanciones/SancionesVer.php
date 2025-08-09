<?php

namespace App\Livewire\Sanciones;

use App\Models\Campeonato;
use App\Models\Sanciones;
use Livewire\Component;
use Livewire\WithPagination;

class SancionesVer extends Component
{
    use WithPagination;

    public $jugador_id;
    public $campeonato_id;
    public $campeonatos;
    public $search;



    public function mount()
    {
        $this->campeonatos = Campeonato::all();

        // Obtener el ultimo campeonato agregado
        $ultimoCampeonato = Campeonato::latest()->first();

        // Si hay un campeonato, asigna su ID a la propiedad para que se seleccione
        if ($ultimoCampeonato) {
            $this->campeonato_id = $ultimoCampeonato->id;
        } else {
            // Si no hay campeonatos, asigna null o una cadena vacía para que el select no tenga un valor inválido
            $this->campeonato_id = null; // O $this->campeonato_id = '';
        }
    }

    public function updateSearch() {}

    // Resetea la paginación cuando se actualiza la búsqueda
    public function updatingSearch()
    {
        $this->resetPage();
    }

    //==========================================================0
    public function render()
    {
        $sanciones = collect();

        if ($this->campeonato_id) {
            $sanciones = Sanciones::query()
                ->where('campeonato_id', $this->campeonato_id)
                ->when($this->search, function ($query) {
                    $query->whereHas('jugador', function ($subQuery) {
                        $subQuery->whereRaw("CONCAT(apellido, ' ', nombre) LIKE ?", ['%' . $this->search . '%'])
                            ->orWhere('documento', 'like', '%' . $this->search . '%');
                    });
                })
                ->orderBy('fecha_sancion', 'desc')
                ->paginate(20);
        }


        return view('livewire.sanciones.sanciones-ver', [
            'sanciones' => $sanciones,

        ]);
    }
}
