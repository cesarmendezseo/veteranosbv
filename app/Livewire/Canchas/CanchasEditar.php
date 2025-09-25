<?php

namespace App\Livewire\Canchas;

use App\Models\Canchas;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\On;
use Illuminate\Support\Str;
use Livewire\Component;

class CanchasEditar extends Component
{

    public $canchas;
    public $nombre;
    public $direccion;
    public $canchaSeleccionada;
    public $estadioId;
    public $ciudad;
    public $provincia;
    public $cod_pos;
    public $otros;
    public bool $redirigir = false;


    public function mount($estadioId)
    {

        $this->canchaSeleccionada = Canchas::find($estadioId);

        $this->nombre = $this->canchaSeleccionada->nombre;
        $this->direccion = $this->canchaSeleccionada->direccion;
        $this->ciudad = $this->canchaSeleccionada->ciudad;
        $this->provincia = $this->canchaSeleccionada->provincia;
        $this->cod_pos = $this->canchaSeleccionada->cod_pos;
        $this->otros = $this->canchaSeleccionada->otros;
        $this->estadioId = $this->canchaSeleccionada->id;
    }

    public function actualizar()
    {

        $this->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'nullable|string|max:500',
            'ciudad' => 'nullable|string|max:255',
            'provincia' => 'nullable|string|max:255',
            'cod_pos' => 'nullable|string|max:20',
            'otros' => 'nullable|string|max:255',
        ]);

        // Normalizar el nombre a minúsculas
        $nombre = Str::lower(trim($this->nombre));

        $this->canchaSeleccionada->update([
            'nombre' => $this->nombre,
            'direccion' => $this->direccion,
            'ciudad' => $this->ciudad,
            'provincia' => $this->provincia,
            'cod_pos' => $this->cod_pos,
            'otros' => $this->otros,
        ]);

        LivewireAlert::title('Ok')
            ->text('Se guardo correctamente la modificación.')
            ->success()
            ->toast()
            ->timer(2000)
            ->position('top')
            ->show();
        $this->reset(['nombre', 'direccion', 'ciudad', 'provincia', 'cod_pos', 'otros']);
        //redirigir a canchas index
        $this->redirigir = true;
    }

    public function verificarRedireccion()
    {
        if ($this->redirigir) {
            $this->redirigir = false;
            return redirect()->route('canchas.index');
        }
    }


    public function render()
    {
        return view('livewire.canchas.canchas-editar');
    }
}
