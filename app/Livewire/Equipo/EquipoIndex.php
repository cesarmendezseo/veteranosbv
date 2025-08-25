<?php

namespace App\Livewire\Equipo;

use App\Models\Equipo;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class EquipoIndex extends Component
{

    public $equipos = [];

    public function mount()
    {
        $this->equipos = Equipo::orderBy('nombre')->get();
    }

    public function borrar($equipoId)
    {

        LivewireAlert::title('ATENCION')
            ->text('Esta por ELIMINAR un equipo, lo confirma?')
            ->asConfirm()
            ->confirmButtonText('Sí, Eliminar ')
            ->cancelButtonText('No, Cancelar')
            ->confirmButtonColor('#00A321') // Verde (Tailwind green-600)
            ->cancelButtonColor('#FF6600')  // Rojo (Tailwind red-600)
            ->warning()
            ->onConfirm('deleteItem', ['id' => $equipoId])
            ->onDeny('keepItem', ['id' => $equipoId])
            ->show();
    }

    public function deleteItem($data)
    {
        $itemId = $data['id'];

        $equipo = Equipo::find($itemId);

        if (!$equipo) {
            LivewireAlert::title('Error')
                ->text('Equipo no encontrado.')
                ->error()
                ->toast()
                ->position('top')
                ->show();
            return;
        }

        // Verificar si el equipo tiene jugadores asociados
        if ($equipo->jugadores()->exists()) {
            LivewireAlert::title('Atención')
                ->text('No se puede eliminar el equipo porque tiene jugadores asociados.')
                ->error()
                ->toast()
                ->position('top')
                ->show();
            return;
        }

        // Si no tiene jugadores, se elimina
        $equipo->delete();

        LivewireAlert::title('')
            ->text('El equipo se ha eliminado correctamente.')
            ->warning()
            ->toast()
            ->position('top')
            ->timer(15000)
            ->show();

        $this->equipos = Equipo::orderBy('nombre')->get(); // refrescar la lista
    }


    public function keepItem($data)
    {
        $itemId = $data['id'];
        $this->equipos = Equipo::orderBy('nombre')->get(); // Refresh the list of equipos */
        // Keep logic
    }





    public function render()
    {
        return view('livewire.equipo.equipo-index');
    }
}
