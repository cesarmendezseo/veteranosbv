<?php

namespace App\Livewire\Jugadore;

use App\Models\Jugador;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class JugadoresIndex extends Component
{
    public $jugadores;
    public $jugadorSeleccionado;
    protected $listeners = ['static-modal' => 'abrirModal'];

    public function mount()
    {
        // Aquí podrías cargar los jugadores desde la base de datos o cualquier otra fuente
        $this->jugadores = Jugador::all(); // Reemplaza esto con la lógica para obtener los jugadores
    }
    public function abrirModal()
    {
        // Aquí no hace falta nada porque $jugadorSeleccionado ya está cargado
    }

    public function borrar($jugadorId)
    {

        LivewireAlert::title('ATENCION')
            ->text('Esta por ELIMINAR un jugador, lo confirma?')
            ->asConfirm()
            ->confirmButtonText('Sí, Eliminar ')
            ->cancelButtonText('No, Cancelar')
            ->confirmButtonColor('#00A321') // Verde (Tailwind green-600)
            ->cancelButtonColor('#FF6600')  // Rojo (Tailwind red-600)
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
            ->timer(15000)
            ->show();

        $this->jugadores = Jugador::orderBy('nombre')->get(); // Refresh the list of equipos */
    }

    public function keepItem($data)
    {
        $itemId = $data['id'];
        $this->jugadores = Jugador::orderBy('nombre')->get(); // Refresh the list of jugadores */
        // Keep logic
    }

    public function verJugador($jugadorId)
    {
        $this->jugadorSeleccionado = \App\Models\Jugador::with('equipo')->findOrFail($jugadorId);
        $this->dispatch('static-modal');
    }

    public function render()
    {
        return view('livewire.jugadore.jugadores-index');
    }
}
