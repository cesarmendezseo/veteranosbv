<?php

namespace App\Livewire\Categoria;

use App\Models\Categoria;
use Livewire\Component;

class CategoriaCrear extends Component
{

    public $nombre;
    public $descripcion;

    public function guardar()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
        ]);

        Categoria::create([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
        ]);

        session()->flash('message', 'Categoría creada exitosamente.');
        return redirect()->route('categoria.index');
    }

    public function render()
    {
        return view('livewire.categoria.categoria-crear');
    }
}
