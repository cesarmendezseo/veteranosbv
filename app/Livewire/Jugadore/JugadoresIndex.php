<?php

namespace App\Livewire\Jugadore;

use App\Models\Jugador;
use Carbon\Carbon;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class JugadoresIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $jugadorSeleccionado;

    protected $listeners = ['static-modal' => 'abrirModal'];

    protected $updatesQueryString = ['search'];

    public function abrirModal()
    {
        // Aquí no hace falta nada porque $jugadorSeleccionado ya está cargado
    }

    //calculamos la eddad
    public function getEdadAttribute()
    {
        return Carbon::parse($this->fecha_nac)->age;
    }

    public function borrar($jugadorId)
    {
        LivewireAlert::title('ATENCION')
            ->text('Esta por ELIMINAR un jugador, lo confirma?')
            ->asConfirm()
            ->confirmButtonText('Sí, Eliminar ')
            ->cancelButtonText('No, Cancelar')
            ->confirmButtonColor('#00A321')
            ->cancelButtonColor('#FF6600')
            ->warning()
            ->onConfirm('deleteItem', ['id' => $jugadorId])
            ->onDeny('keepItem', ['id' => $jugadorId])
            ->show();
    }

    public function deleteItem($data)
    {
        $itemId = $data['id'];
        $equipo = Jugador::find($itemId);

        if (!$equipo) {
            LivewireAlert::title('Error')
                ->text('Jugador no encontrado.')
                ->error()
                ->toast()
                ->position('top')
                ->show();
            return;
        }

        $equipo->delete();

        LivewireAlert::title('')
            ->text('El Jugador se ha eliminado correctamente.')
            ->warning()
            ->toast()
            ->position('top')
            ->show();
    }

    public function keepItem()
    {
        $this->resetPage();
    }

    public function verJugador($jugadorId)
    {
        $this->jugadorSeleccionado = Jugador::find($jugadorId);
        $this->dispatch('static-modal');
    }

    // Método para buscar y resetear la paginación
    public function buscar()
    {
        $this->resetPage();
    }

    public function render()
    {
        $jugadores = Jugador::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('apellido', 'like', '%' . $this->search . '%')
                        ->orWhere('nombre', 'like', '%' . $this->search . '%')
                        ->orWhere('documento', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('apellido')
            ->paginate(10);

        return view('livewire.jugadore.jugadores-index', [
            'jugadores' => $jugadores,
        ]);
    }
}
