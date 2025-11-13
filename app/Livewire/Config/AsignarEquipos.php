<?php

namespace App\Livewire\Config;

use App\Models\Campeonato;
use App\Models\Equipo;
use App\Models\Grupo;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class AsignarEquipos extends Component
{
    public $campeonatoId;
    public $campeonato;
    public $grupoSeleccionado;
    public $equiposSeleccionados = [];
    public $equiposDisponibles;
    public $grupoNombre;
    public $search = '';

    public function mount($campeonatoId)
    {
        $this->campeonatoId = $campeonatoId;
        $this->cargarCampeonato();
        $this->cargarEquiposDisponibles();
    }

    public function cargarCampeonato()
    {
        // Se mantiene igual
        $this->campeonato = Campeonato::with(['grupos', 'equipos'])->findOrFail($this->campeonatoId);
    }

    public function cargarEquiposDisponibles()
    {
        // Obtener los IDs de los equipos que ya están asignados al campeonato
        $equiposAsignadosIds = DB::table('campeonato_equipo')
            ->where('campeonato_id', $this->campeonatoId)
            ->pluck('equipo_id')
            ->toArray();

        $query = Equipo::whereNotIn('id', $equiposAsignadosIds);
        // APLICAR FILTRO DE BÚSQUEDA si $search tiene contenido
        if (!empty($this->search)) {
            // Filtra por nombre usando LIKE %valor%
            $query->where('nombre', 'like', '%' . $this->search . '%');
        }

        // Traer los equipos no asignados y filtrados, ordenados alfabéticamente
        $this->equiposDisponibles = $query->orderBy('nombre', 'asc')->get();
    }

    public function updatedSearch()
    {
        // Cuando el usuario escribe en el campo de búsqueda, Livewire actualizará $search 
        // y llamará a este método para recargar la lista de equipos disponibles.
        $this->cargarEquiposDisponibles();

        // Opcional: Limpiar las selecciones anteriores al buscar.
        $this->equiposSeleccionados = [];
    }

    public function updatedGrupoSeleccionado()
    {
        // Se mantiene igual
        $this->equiposSeleccionados = [];
    }

    public function asignarEquiposAGrupo()
    {
        if (empty($this->equiposSeleccionados)) {
            $this->dispatch('selecionarEquipos');
            return;
        }

        // 1. Definir los formatos que NO usan grupos
        $formatosSinGrupos = ['todos_contra_todos', 'eliminacion_simple', 'eliminacion_doble'];

        // 2. Comprobar si el formato actual NO usa grupos
        if (in_array($this->campeonato->formato, $formatosSinGrupos)) {
            // Lógica unificada para TODOS CONTRA TODOS y ELIMINACIÓN

            // VALIDACIÓN DEL LÍMITE DE EQUIPOS PARA ELIMINACIÓN
            // Asumiendo que la columna 'cantidad_equipos' contiene el límite para la eliminación.
            $limiteEquipos = $this->campeonato->total_equipos;
            $totalActual = DB::table('campeonato_equipo')
                ->where('campeonato_id', $this->campeonatoId)
                ->count();
            $totalAAgregar = count($this->equiposSeleccionados);

            if ($limiteEquipos > 0 && ($totalActual + $totalAAgregar) > $limiteEquipos) {
                session()->flash('error', "No puedes asignar más de {$limiteEquipos} equipos a este campeonato.");
                LivewireAlert::title('AtenciónError')
                    ->title('No puedes asignar más equipos a este campeonato, ya asigno todo.')
                    ->warning()
                    ->toast()
                    ->show();
                return;
            }

            foreach ($this->equiposSeleccionados as $equipoId) {
                // Validación: Si ya está asignado al campeonato (independientemente del grupo, que es null)
                $yaAsignado = DB::table('campeonato_equipo')
                    ->where('campeonato_id', $this->campeonatoId)
                    ->where('equipo_id', $equipoId)
                    ->exists();

                if ($yaAsignado) {
                    $equipo = Equipo::find($equipoId);
                    session()->flash('error', "El equipo '{$equipo->nombre}' ya está asignado al campeonato.");
                    return;
                }

                // Inserción: Se asigna al campeonato sin grupo
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
                $grupoNombre = ucwords($grupo->nombre);
                $this->dispatch('errorEquipoAsignado', $grupoNombre);
                return;
            }

            foreach ($this->equiposSeleccionados as $equipoId) {
                // Validación: Si ya está asignado al campeonato (en cualquier grupo)
                $yaAsignado = DB::table('campeonato_equipo')
                    ->where('campeonato_id', $this->campeonatoId)
                    ->where('equipo_id', $equipoId)
                    ->exists();

                if ($yaAsignado) {
                    $equipo = Equipo::find($equipoId);
                    session()->flash('error', "El equipo '{$equipo->nombre}' ya está asignado.");
                    return;
                }

                // Inserción: Se asigna al grupo seleccionado
                DB::table('campeonato_equipo')->insert([
                    'campeonato_id' => $this->campeonatoId,
                    'equipo_id' => $equipoId,
                    'grupo_id' => $grupo->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Finalización
        $this->equiposSeleccionados = [];
        $this->grupoSeleccionado = '';
        $this->cargarCampeonato();
        $this->cargarEquiposDisponibles();

        session()->flash('success', 'Equipos asignados correctamente.');
        $this->dispatch('equiposAsignados');
    }

    // El método removerEquipoDeGrupo() se mantiene igual ya que se usa 'grupo_id' en la eliminación.

    public function removerEquipoDeGrupo($equipoId, $grupoId = null)
    {
        // Se puede adaptar para que funcione con null (sin grupo) o con un ID de grupo
        $query = DB::table('campeonato_equipo')
            ->where('campeonato_id', $this->campeonatoId)
            ->where('equipo_id', $equipoId);

        // Si grupoId es 0 o null, buscamos donde grupo_id es null (para eliminación/TCT)
        if (empty($grupoId)) {
            $query->whereNull('grupo_id');
        } else {
            $query->where('grupo_id', $grupoId);
        }

        $query->delete();

        $this->cargarCampeonato();
        $this->cargarEquiposDisponibles();

        session()->flash('success', 'Equipo removido del campeonato.');
    }

    public function render()
    {
        return view('livewire.config.asignar-equipos');
    }
}
