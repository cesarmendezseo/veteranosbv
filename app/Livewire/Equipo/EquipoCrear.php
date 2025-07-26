<?php

namespace App\Livewire\Equipo;

use App\Models\Equipo;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\TemporaryUploadedFile;
use Illuminate\Validation\ValidationException;

class EquipoCrear extends Component
{
    use WithFileUploads;

    public $nombre, $ciudad, $provincia, $cod_pos,  $descripcion;

    protected function rules()
    {
        return [
            'nombre' => 'required|string|min:3',
            'ciudad' => 'nullable|string',
            'provincia' => 'nullable|string',
            'cod_pos' => 'nullable|numeric',
            'descripcion' => 'nullable|string',

        ];
    }



    public function guardar()
    {
        $this->validate();


        Equipo::create([
            'nombre' => $this->nombre,
            'ciudad' => $this->ciudad,
            'provincia' => $this->provincia,
            'cod_pos' => $this->cod_pos,
            'descripcion' => $this->descripcion,

        ]);

        $this->reset(); // Limpia todo
        session()->flash('mensaje', 'Equipo creado correctamente.');
    }

    public function render()
    {
        return view('livewire.equipo.equipo-crear');
    }
}
