<?php

namespace App\Livewire\Equipo;

use App\Models\Equipo;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class EquipoEditar extends Component
{


    public $equipoId;
    public $nombre, $ciudad, $provincia, $cod_pos, $descripcion;

    public function mount($equipoId)
    {
        $equipo = Equipo::findOrFail($equipoId);
        $this->equipoId = $equipo->id;
        $this->nombre = $equipo->nombre;
        $this->ciudad = $equipo->ciudad;
        $this->provincia = $equipo->provincia;
        $this->cod_pos = $equipo->cod_pos;
        $this->descripcion = $equipo->descripcion;
    }

    public function actualizarEquipo()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'ciudad' => 'required|string|max:255',
            'provincia' => 'required|string|max:255',
            'cod_pos' => 'nullable|string|max:10',
            'descripcion' => 'nullable|string|max:500',
        ]);

        $equipo = Equipo::findOrFail($this->equipoId);
        $equipo->update([
            'nombre' => $this->nombre,
            'ciudad' => $this->ciudad,
            'provincia' => $this->provincia,
            'cod_pos' => $this->cod_pos,
            'descripcion' => $this->descripcion,
        ]);

        LivewireAlert::title('Equipo Actualizado')
            ->text('El equipo se ha actualizado correctamente.')
            ->success()
            ->toast()
            ->position('top')

            ->show();

        /* return redirect()->route('equipo.index'); */
        // Emitir evento para redirigir

    }

    public function volver()
    {
        return redirect()->route('equipo.index');
    }



    public function render()
    {
        return view('livewire.equipo.equipo-editar');
    }
}
