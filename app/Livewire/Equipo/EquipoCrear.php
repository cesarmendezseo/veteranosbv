<?php

namespace App\Livewire\Equipo;

use App\Models\Equipo;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\TemporaryUploadedFile;
use Illuminate\Validation\ValidationException;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Jantinnerezo\LivewireAlert\Enums\Position;

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

        $existe = Equipo::where('nombre', $this->nombre)->exists();

        if ($existe) {


            throw ValidationException::withMessages(
                [
                    'nombre' => 'El equipo ya existe en la base de datos.',
                    LivewireAlert::title('Error!')
                        ->text('El equipo ya existe en la base de datos..')
                        ->error()
                        ->toast()
                        ->position('top')
                        /* ->timer(1500) */
                        ->show()
                ]
            );
        }


        Equipo::create([
            'nombre' => $this->nombre,
            'ciudad' => $this->ciudad,
            'provincia' => $this->provincia,
            'cod_pos' => $this->cod_pos,
            'descripcion' => $this->descripcion,

        ]);

        $this->reset(); // Limpia todo
        LivewireAlert::title('Equipo Creado')
            ->text('El equipo se ha creado correctamente.')
            ->success()
            ->toast()
            ->position('top')
            ->show();
    }

    public function render()
    {
        return view('livewire.equipo.equipo-crear');
    }
}
