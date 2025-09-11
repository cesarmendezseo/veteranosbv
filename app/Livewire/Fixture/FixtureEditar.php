<?php

namespace App\Livewire\Fixture;

use App\Models\Campeonato;
use App\Models\Canchas;
use App\Models\Encuentro;
use App\Models\Grupo;
use Livewire\Attributes\On;
use Livewire\Component;

class FixtureEditar extends Component
{

    public $aniosDisponiblesEditar;
    public $anioSeleccionadoEditar;
    public $campeonato_id_Editar;
    public $campeonatosEditar;
    public $gruposEditar;
    public $jornadaFiltroEditar;
    public $encuentros = [];
    public $encuentrosAgrupados = [];
    public $goles_local;
    public $goles_visitante;
    public $encuentroEditId = null;
    public $canchas; // Lista de canchas disponibles

    // Campos editables
    public $fecha_edit;
    public $hora_edit;
    public $fecha_encuentro_edit;
    public $cancha_id_edit;


    public function mount()
    {
        $this->canchas = Canchas::all(); // Cargar todas las canchas
        // Obtener los años disponibles
        $this->aniosDisponiblesEditar = Campeonato::selectRaw('YEAR(created_at) as anio')
            ->distinct()
            ->orderByDesc('anio')
            ->pluck('anio')
            ->toArray();
    }


    public function updatedAnioSeleccionadoEditar()
    {
        // Actualizar los campeonatos al seleccionar un año
        $this->campeonatosEditar = Campeonato::whereYear('created_at', $this->anioSeleccionadoEditar)->get();
        $this->campeonato_id_Editar = null;
    }

    public function updatedCampeonatoId()
    {

        // Obtener grupos relacionados al campeonato
        $this->gruposEditar = $this->campeonato_id_Editar
            ? Grupo::where('campeonato_id', $this->campeonato_id_Editar)->get()
            : [];
    }

    public function updatedJornadaFiltroEditar()
    {
        $encuentros = Encuentro::query()
            ->with(['equipoLocal', 'equipoVisitante', 'cancha', 'grupo'])
            ->when($this->campeonato_id_Editar, fn($q) => $q->where('campeonato_id', $this->campeonato_id_Editar))
            ->when($this->jornadaFiltroEditar, fn($q) => $q->where('fecha_encuentro', $this->jornadaFiltroEditar))
            ->orderBy('fecha')
            ->orderBy('cancha_id')
            ->orderBy('hora')
            ->get();

        // Convierte a array para evitar problemas de serialización
        $this->encuentrosAgrupados = $encuentros->groupBy('cancha.nombre')->toArray();
        $this->encuentros = $encuentros;
    }


    public function editEncuentro($id)
    {

        $encuentro = Encuentro::findOrFail($id);
        $this->encuentroEditId = $id;

        //asignar valores actuales
        $this->fecha_edit = $encuentro->fecha;
        $this->hora_edit = $encuentro->hora;
        $this->fecha_encuentro_edit = $encuentro->fecha_encuentro;
        $this->cancha_id_edit = $encuentro->cancha_id;
    }


    public function updateEncuentro()
    {

        $this->validate([
            'fecha_edit' => 'required|date',
            'hora_edit' => 'required|date_format:H:i',
            'fecha_encuentro_edit' => 'required|integer', // Asumiendo que es número de jornada
            'cancha_id_edit' => 'required|exists:canchas,id',
        ]);

        Encuentro::find($this->encuentroEditId)->update([
            'fecha' => $this->fecha_edit,
            'hora' => $this->hora_edit,
            'fecha_encuentro' => $this->fecha_encuentro_edit,
            'cancha_id' => $this->cancha_id_edit,
        ]);
        $this->dispatch('swal', [
            'title' => 'Ok!!',
            'text' => 'Los datos se guardaron correctamente!',
            'icon' => 'success'
        ]);

        $this->resetEdit();
        $this->encuentros = Encuentro::with(['equipoLocal', 'equipoVisitante', 'cancha'])->get();
    }



    public function resetEdit()
    {
        $this->encuentroEditId = null;
        $this->fecha_edit = null;
        $this->hora_edit = null;
        $this->fecha_encuentro_edit = null;
        $this->cancha_id_edit = null;
    }




    //=======================DELETE
    #[On('confirm-delete')]
    public function deleteRecord($id)
    {

        $registro = Encuentro::find($id);

        if ($registro) {
            $registro->delete();
            $this->updatedJornadaFiltroEditar();
            $this->dispatch('deleted');
        }
    }

    //===================
    public function render()
    {
        if (!$this->campeonato_id_Editar) {
            return view('livewire.fixture.editar', [
                'encuentrosAgrupados' => [], // Usa un array vacío en lugar de `collect()`
            ]);
        }
        return view('livewire.fixture.fixture-editar', [
            'encuentros' => $this->encuentros,
            'encuentrosAgrupados' => $this->encuentrosAgrupados // Pasa la variable encuentros a la vista
        ]);
    }
}
