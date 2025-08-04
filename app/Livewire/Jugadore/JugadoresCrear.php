<?php

namespace App\Livewire\Jugadore;

use App\Models\Jugador;
use Illuminate\Validation\ValidationException;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\On;
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
    public $equipo; // Default value, can be changed as needed
    public $equipos = [];
    public $equipo_seleccionado;

    public function mount()
    {
        $this->equipos = \App\Models\Equipo::orderBy('nombre')->get();
    }

    public function guardar()
    {

        try {
            $this->validate([
                'nombre' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'documento' => 'required|numeric|unique:jugadors,documento',
                'tipo_documento' => 'required|string|max:50',
                'socio' => 'nullable|numeric|unique:jugadors,num_socio',
                'telefono' => 'nullable|numeric',
                'email' => 'nullable|email|max:255',
                'direccion' => 'nullable|string|max:255',
                'ciudad' => 'nullable|string|max:255',
                'provincia' => 'nullable|string|max:255',
                'nacimiento' => 'nullable|date',
                'cod_pos' => 'nullable|string|max:10',
                'equipo_seleccionado' => 'required|exists:equipos,id',

            ], [
                'documento.unique' => 'El número de documento ya está registrado.',
                'nombre.required' => 'El nombre es obligatorio.',
                'apellido.required' => 'El apellido es obligatorio.',
                'documento.required' => 'El número de documento es obligatorio.',
                'documento.numeric' => 'El documento debe ser un número.',
                'socio.unique' => 'El número de socio ya está registrado.',
                'tipo_documento.required' => 'Debe seleccionar un tipo de documento.',
                'email.email' => 'El correo electrónico no es válido.',
                'equipo_seleccionado.required' => 'Debe seleccionar un equipo.',
                'equipo_seleccionado.exists' => 'El equipo seleccionado no es válido.',
            ]);
        } catch (ValidationException $e) {
            $errores = collect($e->validator->errors()->all())->implode("\n");
            $this->dispatch('alertaError', message: $errores);
            throw $e; // opcional: si querés que también se muestren los errores normales
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
            'is_active' => $this->activo,
            'equipo_id' => $this->equipo_seleccionado, // Assuming you have a relation to the team
        ]);

        $this->reset(); // Limpia todo
        $this->dispatch('creado');
        // Dispatch an event to notify other components

    }

    #[On('index')]
    public function index()
    {
        return redirect()->route('jugadores.index');
    }

    public function render()
    {
        return view('livewire.jugadore.jugadores-crear');
    }
}
