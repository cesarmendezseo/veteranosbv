<?php

namespace App\Livewire\Jugadore;


use Illuminate\Validation\ValidationException;
use Livewire\Attributes\On;
use Livewire\Component;

class JugadoresEditar extends Component
{

    public $jugadorId;
    public $jugador;
    public $documento;
    public $tipo_documento;
    public $nombre;
    public $apellido;
    public $nacimiento;
    public $num_socio;
    public $telefono;
    public $email;
    public $direccion;
    public $ciudad;
    public $provincia;
    public $cod_pos;
    public $foto;
    public $activo;


    public function mount($jugadorId)
    {

        $this->jugadorId = $jugadorId;
        $this->jugador = \App\Models\Jugador::findOrFail($jugadorId);

        $this->documento = $this->jugador->documento;
        $this->nombre = $this->jugador->nombre;
        $this->apellido = $this->jugador->apellido;
        $this->telefono = $this->jugador->telefono;
        $this->email = $this->jugador->email;
        $this->ciudad = $this->jugador->ciudad;
        $this->nacimiento = $this->jugador->fecha_nac;
        $this->direccion = $this->jugador->direccion;
        $this->provincia = $this->jugador->provincia;
        $this->cod_pos = $this->jugador->cod_pos;
        $this->num_socio = $this->jugador->num_socio;
        $this->tipo_documento = $this->jugador->tipo_documento;
        $this->activo = $this->jugador->is_active;
        // Asignar la foto del jugador si existe
        $this->foto = $this->jugador->foto;
    }

    public function actualizarJugador()
    {
        try {
            $this->validate([
                'documento' => 'required|string|max:20',
                'tipo_documento' => 'required|string|max:20',
                'nombre' => 'nullable|string|max:255',
                'apellido' => 'nullable|string|max:255',
                'nacimiento' => 'nullable|date',
                'num_socio' => 'nullable|integer|unique:jugadors,num_socio,' . $this->jugadorId,
                'telefono' => 'nullable|string|max:15',
                'email' => 'nullable|email|max:255',
                'direccion' => 'nullable|string|max:255',
                'ciudad' => 'nullable|string|max:255',
                'provincia' => 'nullable|string|max:255',
                'cod_pos' => 'nullable|string|max:10',
                'foto' => 'nullable|image|max:2048',
                'activo' => 'boolean'
            ], [
                'documento.required' => 'El campo documento es obligatorio.',
                'documento.max' => 'El documento no debe tener más de 20 caracteres.',
                'tipo_documento.required' => 'Debe seleccionar un tipo de documento.',
                'num_socio.unique' => 'El número de socio ya está en uso.',
                'email.email' => 'El formato del correo no es válido.',
                'foto.image' => 'La foto debe ser una imagen.',
                'foto.max' => 'La foto no debe superar los 2 MB.',
            ]);
        } catch (ValidationException $e) {
            $errores = collect($e->validator->errors()->all())->implode("\n");
            $this->dispatch('alertaError', message: $errores);
            throw $e; // opcional: si querés que también se muestren los errores normales
        }

        $jugador = \App\Models\Jugador::findOrFail($this->jugadorId);
        $jugador->update([
            'documento' => $this->documento,
            'tipo_documento' => $this->tipo_documento,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'fecha_nac' => $this->nacimiento,
            'num_socio' => $this->num_socio,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'direccion' => $this->direccion,
            'ciudad' => $this->ciudad,
            'provincia' => $this->provincia,
            'cod_pos' => $this->cod_pos,
            'is_active' => $this->activo,
            // Actualizar la foto si se ha subido una nueva
            // Aquí deberías manejar la lógica de subida de archivos
        ]);

        $this->dispatch('actualizado');
    }

    #[On('index')]
    public function index()
    {
        return redirect()->route('jugadores.index');
    }

    public function render()
    {
        return view('livewire.jugadore.jugadores-editar');
    }
}
