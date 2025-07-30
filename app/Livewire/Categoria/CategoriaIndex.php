<?php

namespace App\Livewire\Categoria;

use App\Models\Categoria;
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
        if ($categoria) {
            $categoria->delete();
            $this->categorias = Categoria::all();
            session()->flash('success', 'Categoría eliminada correctamente.');
        } else {
            session()->flash('error', 'Categoría no encontrada.');
        }
    }

    public function render()
    {
        return view('livewire.categoria.categoria-index');
    }
}
