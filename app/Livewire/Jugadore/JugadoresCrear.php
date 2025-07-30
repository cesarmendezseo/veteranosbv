<?php

namespace App\Livewire\Jugadore;

use App\Models\Jugador;
use Illuminate\Validation\ValidationException;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class JugadoresCrear extends Component
{
    public $nombre;
    public $apellido;
    public $documento;
    public $socio;
    public $telefono;
    public $email;
    public $direccion;
    public $ciudad;
    public $provincia;
    public $nacimiento;
    public $cod_pos;
    public $activo = true;
    public $tipo_documento = 'DNI'; // Default value, can be changed as needed
    public $equipo = 1; // Default value, can be changed as needed
    public $equipos = [];
    public $equipo_seleccionado;

    public function mount()
    {
        $this->equipos = \App\Models\Equipo::orderBy('nombre')->get();
    }

    public function guardar()
    {


        $this->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'documento' => 'required|numeric',
            'tipo_documento' => 'required|string|max:50',
            'socio' => 'nullable|numeric',
            'telefono' => 'nullable|numeric',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:255',
            'provincia' => 'nullable|string|max:255',
            'nacimiento' => 'nullable|date',
            'cod_pos' => 'nullable|string|max:10',
            'equipo_seleccionado' => 'nullable|exists:equipos,id',
            'activo' => 'boolean',
        ]);

        // Logic to save the player data goes here
        $existe = Jugador::where('documento', $this->documento)->exists();
        if ($existe) {
            throw ValidationException::withMessages(
                [
                    'documento' => 'El jugador ya existe en la base de datos.',
                    LivewireAlert::title('Error!')
                        ->text('El jugador ya existe en la base de datos.')
                        ->error()
                        ->toast()
                        ->position('top')
                        /* ->timer(1500) */
                        ->show()
                ]
            );
        }

        Jugador::create([
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'documento' => $this->documento,
            'tipo_documento' => $this->tipo_documento, // Assuming a default type, adjust as necessary
            'num_socio' => $this->socio,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'direccion' => $this->direccion,
            'ciudad' => $this->ciudad,
            'provincia' => $this->provincia,
            'fecha_nac' => $this->nacimiento,
            'cod_pos' => $this->cod_pos,
            'activo' => $this->activo,
            'equipo_id' => $this->equipo_seleccionado, // Assuming you have a relation to the team
        ]);

        $this->reset(); // Limpia todo
        LivewireAlert::title('Jugador Creado')
            ->text('El jugador se ha creado correctamente.')
            ->success()
            ->toast()
            ->position('top')
            ->show();
    }

    public function render()
    {
        return view('livewire.jugadore.jugadores-crear');
    }
}
