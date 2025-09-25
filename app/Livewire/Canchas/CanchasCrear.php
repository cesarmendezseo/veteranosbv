<?php

namespace App\Livewire\Canchas;

use App\Models\Canchas;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class CanchasCrear extends Component
{
    public $canchas;
    public $nombre;
    public $direccion;
    public $canchaSeleccionada;
    public $canchaId;
    public $ciudad;
    public $provincia;
    public $cod_pos;
    public $otros;
    public bool $redirigir = false;


    public function guardar()
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

        // Verificar si ya existe
        if (Canchas::where('nombre', $nombre)->exists()) {
            $this->addError('nombre', 'Ya existe una cancha con este nombre.');
            return;
        }

        Canchas::create([
            'nombre' => $this->nombre,
            'direccion' => $this->direccion,
            'ciudad' => $this->ciudad,
            'provincia' => $this->provincia,
            'cod_pos' => $this->cod_pos,
            'otros' => $this->otros,
        ]);


        LivewireAlert::title('Correcto')
            ->text('Cancha creada.')
            ->success()
            ->toast()
            ->timer(5000)
            ->position('top')
            ->show();
        $this->reset(['nombre', 'direccion', 'ciudad', 'provincia', 'cod_pos', 'otros']);

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
        return view('livewire.canchas.canchas-crear');
    }
}
