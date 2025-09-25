<?php

namespace App\Livewire\Categoria;

use App\Models\Categoria;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class CategoriaIndex extends Component
{
    public $categorias;
    public $nombre;
    public $descripcion;
    public $categoriaSeleccionada;
    public $categoriaId;
    public $itemId;


    public function mount()
    {
        $this->categorias = Categoria::all();
    }

    public function crear()
    {

        return redirect()->route('categoria.crear');
    }


    public function verCategoria($categoriaId)
    {
        $this->categoriaSeleccionada = \App\Models\Categoria::find($categoriaId);
        $this->dispatch('static-modal');
    }

    public function borrar($categoriaId)
    {
        $categoria = Categoria::find($categoriaId);

        if (!$categoria) {
            LivewireAlert::title('Error')
                ->text('Categoría no encontrada.')
                ->error()
                ->toast()
                ->position('top')
                ->show();

            return;
        }

        // Verificar si está asociada a campeonatos
        if ($categoria->campeonatos()->exists()) {
            LivewireAlert::title('Error')
                ->text('No se puede eliminar la categoría porque está asociada a un campeonato.')
                ->error()
                ->toast()
                ->position('top')
                ->show();

            return;
        }

        $this->itemId = $categoriaId;
        LivewireAlert::title('Borrar Categoria')
            ->text('Estas seguro de querer borrar esta la categoria?')
            ->asConfirm()
            ->onConfirm('deleteItem', ['id' => $this->itemId])
            ->onDeny('keepItem', ['id' => $this->itemId])
            ->show();
    }

    public function deleteItem($data)
    {
        $categoriaId = $data['id'];
        // Delete logic
        // Eliminar si no tiene campeonatos asociados
        $categoria = Categoria::find($categoriaId);
        $categoria->delete();

        $this->categorias = Categoria::all();

        LivewireAlert::title('Ok')
            ->text('Categoría eliminada correctamente.')
            ->success()
            ->toast()
            ->position('top')
            ->show();
    }

    public function keepItem() {}

    public function render()
    {
        return view('livewire.categoria.categoria-index');
    }
}
