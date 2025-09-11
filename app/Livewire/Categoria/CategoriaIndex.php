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


    public function mount()
    {
        $this->categorias = Categoria::all();
    }

    public function crear()
    {
        dd('gola');
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
        // Eliminar si no tiene campeonatos asociados
        $categoria->delete();

        $this->categorias = Categoria::all();
        LivewireAlert::title('Ok')
            ->text('Categoría eliminada correctamente.')
            ->success()
            ->toast()
            ->position('top')
            ->show();
    }

    public function render()
    {
        return view('livewire.categoria.categoria-index');
    }
}
