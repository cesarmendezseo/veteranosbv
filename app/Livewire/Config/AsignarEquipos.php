<?php

namespace App\Livewire\Config;

use App\Models\Campeonato;
use App\Models\Equipo;
use App\Models\Grupo;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AsignarEquipos extends Component
{
    public $campeonatoId;
    public $campeonato;
    public $grupoSeleccionado;
    public $equiposSeleccionados = [];
    public $equiposDisponibles;

    public function mount($campeonatoId)
    {
        $this->campeonatoId = $campeonatoId;
        $this->cargarCampeonato();
        $this->cargarEquiposDisponibles();
    }

    public function cargarCampeonato()
    {
        $this->campeonato = Campeonato::with(['grupos', 'equipos'])->findOrFail($this->campeonatoId);
    }

    public function cargarEquiposDisponibles()
    {
        $equiposAsignadosIds = DB::table('campeonato_equipo')
            ->where('campeonato_id', $this->campeonatoId)
            ->pluck('equipo_id')
            ->toArray();

        $this->equiposDisponibles = Equipo::whereNotIn('id', $equiposAsignadosIds)->get();
    }

    public function updatedGrupoSeleccionado()
    {
        $this->equiposSeleccionados = [];
    }

    public function asignarEquiposAGrupo()
    {
        if (empty($this->equiposSeleccionados)) {
            session()->flash('error', 'Por favor, selecciona al menos un equipo.');
            return;
        }

        // TODOS CONTRA TODOS
        if ($this->campeonato->formato === 'todos_contra_todos') {
            foreach ($this->equiposSeleccionados as $equipoId) {
                $yaAsignado = DB::table('campeonato_equipo')
                    ->where('campeonato_id', $this->campeonatoId)
                    ->where('equipo_id', $equipoId)
                    ->exists();

                if ($yaAsignado) {
                    $equipo = Equipo::find($equipoId);
                    session()->flash('error', "El equipo '{$equipo->nombre}' ya está asignado.");
                    return;
                }

                DB::table('campeonato_equipo')->insert([
                    'campeonato_id' => $this->campeonatoId,
                    'equipo_id' => $equipoId,
                    'grupo_id' => null, // sin grupo
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // FORMATO POR GRUPOS
        else {
            if (!$this->grupoSeleccionado) {
                session()->flash('error', 'Por favor, selecciona un grupo.');
                return;
            }

            $grupo = Grupo::findOrFail($this->grupoSeleccionado);

            $totalActual = DB::table('campeonato_equipo')
                ->where('grupo_id', $grupo->id)
                ->where('campeonato_id', $this->campeonatoId)
                ->count();

            $totalAAgregar = count($this->equiposSeleccionados);
            $limite = $this->campeonato->cantidad_equipos_grupo;

            if ($totalActual + $totalAAgregar > $limite) {
                session()->flash('error', "Este grupo solo permite un máximo de $limite equipos. Ya tiene $totalActual.");
                return;
            }

            foreach ($this->equiposSeleccionados as $equipoId) {
                $yaAsignado = DB::table('campeonato_equipo')
                    ->where('campeonato_id', $this->campeonatoId)
                    ->where('equipo_id', $equipoId)
                    ->exists();

                if ($yaAsignado) {
                    $equipo = Equipo::find($equipoId);
                    session()->flash('error', "El equipo '{$equipo->nombre}' ya está asignado.");
                    return;
                }

                DB::table('campeonato_equipo')->insert([
                    'campeonato_id' => $this->campeonatoId,
                    'equipo_id' => $equipoId,
                    'grupo_id' => $grupo->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->equiposSeleccionados = [];
        $this->grupoSeleccionado = '';
        $this->cargarCampeonato();
        $this->cargarEquiposDisponibles();

        session()->flash('success', 'Equipos asignados correctamente.');
    }


    public function removerEquipoDeGrupo($equipoId, $grupoId)
    {
        DB::table('campeonato_equipo')
            ->where('campeonato_id', $this->campeonatoId)
            ->where('grupo_id', $grupoId)
            ->where('equipo_id', $equipoId)
            ->delete();

        $this->cargarCampeonato();
        $this->cargarEquiposDisponibles();

        session()->flash('success', 'Equipo removido del grupo.');
    }

    public function render()
    {
        return view('livewire.config.asignar-equipos');
    }
}
