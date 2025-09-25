<?php

namespace App\Livewire\Canchas;

use App\Models\Canchas;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class CanchasIndex extends Component
{



    public $canchas;
    public $nombre;
    public $direccion;
    public $canchaSeleccionada;
    public $canchaId;
    public $provincia;
    public $cod_pos;
    public $otros;
    public $itemId;


    public function mount()
    {
        $this->canchas = Canchas::all();
    }

    public function crear()
    {
        return redirect()->route('canchas.crear');
    }


    public function verEstadio($canchaId)
    {

        $this->canchaSeleccionada = \App\Models\Canchas::find($canchaId);
        $this->dispatch('static-modal');
    }


    /* #[On('eliminar-estadio')] */





    public function eliminarEstadio($itemId)
    {
        $this->itemId = $itemId;
        LivewireAlert::title('Borrar Cancha')
            ->text('Estas seguro de querer borrar esta cancha?')
            ->asConfirm()
            ->onConfirm('deleteItem', ['id' => $this->itemId])
            ->onDeny('keepItem', ['id' => $this->itemId])
            ->show();
    }
    public function deleteItem($data)
    {
        $itemId = $data['id'];
        // Delete logic
        $cancha = Canchas::find($itemId);

        if (!$cancha) {
            LivewireAlert::title('Error')
                ->text('La cancha no existe..')
                ->error()
                ->toast()
                ->position('top')
                ->show();

            return;
        }
        // Verificar si la cancha está asociada a encuentros
        if ($cancha->encuentros()->exists()) {
            LivewireAlert::title('Error')
                ->text('No se puede eliminar la cancha porque está asociada a uno o más encuentros...')
                ->error()
                ->toast()
                ->position('top')
                ->show();

            return;
        }

        // Si no está asociada, eliminar
        $cancha->delete();

        LivewireAlert::title('Ok')
            ->text('Cancha eliminada correctament')
            ->success()
            ->toast()
            ->position('top')
            ->show();

        return redirect()->route('canchas.index'); // recarga la página
    }

    public function keepItem($data)
    {
        $itemId = $data['id'];
        // Keep logic
    }




    public function render()
    {
        return view('livewire.canchas.canchas-index');
    }
}
