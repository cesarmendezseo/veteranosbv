<?php

namespace App\Livewire\Categoria;

use Livewire\Component;

class CategoriaEdit extends Component

{
    public $categoriaId;
    public $nombre;
    public $descripcion;

    public function mount($categoriaId)
    {
        $this->categoriaId = $categoriaId;
        $categoria = \App\Models\Categoria::find($this->categoriaId);
        if ($categoria) {
            $this->nombre = $categoria->nombre;
            $this->descripcion = $categoria->descripcion;
        } else {
            session()->flash('error', 'Categoría no encontrada.');
            return redirect()->route('categoria.index');
        }
    }

    public function actualizarCategoria()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
        ]);

        $categoria = \App\Models\Categoria::find($this->categoriaId);
        if ($categoria) {
            $categoria->update([
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
            ]);
            session()->flash('message', 'Categoría actualizada correctamente.');
            return redirect()->route('categoria.index');
        } else {
            session()->flash('error', 'Categoría no encontrada.');
            return redirect()->route('categoria.index');
        }
    }

    public function render()
    {
        return view('livewire.categoria.categoria-edit');
    }
}
