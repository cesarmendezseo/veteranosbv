<?php

namespace App\Livewire\Fixture;

use App\Exports\EncuentrosExport;
use App\Models\Campeonato;
use App\Models\Canchas;
use App\Models\Encuentro;
use App\Models\Grupo;
use App\Services\EncuentroExportService;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

use Maatwebsite\Excel\Facades\Excel;


class FixtureVer extends Component
{
    use WithPagination;
    public $campeonatoId;
    public $campeonato_id;
    public $jornadas;
    public $campeonatos;
    public $anioSeleccionado;
    public $aniosDisponibles = [];
    public $fechaFiltro, $estadoFiltro, $grupoFiltro;
    public $grupos = [];
    public $sortField = 'fecha';
    public $sortDirection = 'asc';
    public $jornadaFiltro;
    public $equipoLocalFiltro;
    public $equipoVisitanteFiltro;
    public $goles_local = [];
    public $goles_visitante = [];
    public $encuentroEditandoId = null;
    public $editFecha, $editHora, $editEstado, $editCanchaId;
    public $showEditModal = false;
    public $canchas = [];
    public $campeonatoSeleccionado;
    public $formato;
    public $encuentro;
    public $encuentrosPorCancha = [];

    public function mount($campeonatoId)
    {

        $this->canchas = Canchas::all(); // Cargar todas las canchas
        // Obtener los años disponibles

        $this->campeonato_id = $campeonatoId;
        $this->campeonatos = $this->campeonatoId;
        $this->updatedCampeonatoId();
    }

    //==================================0ORDENAMIENTO========================================

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    // =================================0FILTRADO========================================
    public function updatedAnioSeleccionado()
    {
        // Actualizar los campeonatos al seleccionar un año
        $this->campeonatos = Campeonato::whereYear('created_at', $this->anioSeleccionado)
            ->orderByDesc('created_at')
            ->get();
    }

    //=================================0CAMPEONATO========================================

    public function updatedCampeonatoId()
    {

        // Resetear filtros al cambiar el campeonato
        $this->resetPage();
        $this->grupoFiltro = null;
        $this->fechaFiltro = null;
        $this->estadoFiltro = null;
        $this->equipoLocalFiltro = null;
        $this->equipoVisitanteFiltro = null;
        $this->jornadaFiltro = null;

        // Obtener grupos relacionados al campeonato
        $this->grupos = $this->campeonato_id
            ? Grupo::where('campeonato_id', $this->campeonato_id)->get()
            : [];

        $this->formato = $this->campeonato_id
            ? Campeonato::find($this->campeonato_id)->formato
            : null;
        $this->jornadas = Encuentro::where('campeonato_id', $this->campeonato_id)
            ->distinct()
            ->orderBy('fecha_encuentro')
            ->pluck('fecha_encuentro');
    }

    public function guardarGoles($encuentroId)
    {
        // Obtener el encuentro
        $encuentro = Encuentro::find($encuentroId);

        if ($encuentro) {
            // Actualizar los goles
            $encuentro->gol_local = $this->goles_local[$encuentroId] ?? 0;
            $encuentro->gol_visitante = $this->goles_visitante[$encuentroId] ?? 0;

            // Cambiar estado a 'Jugado'
            $encuentro->estado = 'Jugado';

            // Guardar el encuentro
            $encuentro->save();

            // Mensaje de éxito
            session()->flash('message', 'Goles guardados y estado actualizado a "Jugado".');
        }
    }
    // ==================================0EDITAR ENCUENTRO========================================
    public function editEncuentro($id)
    {
        $encuentro = Encuentro::findOrFail($id);

        $this->encuentroEditandoId = $id;
        $this->editFecha = $encuentro->fecha;
        $this->editHora = $encuentro->hora;
        $this->editEstado = $encuentro->estado;
        $this->editCanchaId = $encuentro->cancha_id;
        $this->canchas = Canchas::all();

        $this->showEditModal = true;
    }

    public function guardarEdicion()
    {
        $encuentro = Encuentro::findOrFail($this->encuentroEditandoId);

        $encuentro->update([
            'fecha' => $this->editFecha,
            'hora' => $this->editHora,
            'estado' => $this->editEstado,
            'cancha_id' => $this->editCanchaId,
        ]);

        $this->reset(['showEditModal', 'encuentroEditandoId', 'editFecha', 'editHora', 'editEstado', 'editCanchaId']);
    }

    #[On('eliminar-encuentro')]
    public function eliminarEncuentro($encuentroId) // The parameter name should match the key in your dispatch payload (Id)
    {

        $encuentro = Encuentro::findOrFail($encuentroId);

        if ($encuentro->estado === 'Jugado') {
            $this->dispatch('Baja');
        } else {
            $encuentro->delete();
            $this->dispatch('eliminado');
        };
    }

    //=============exportar a exel ===============

    public function exportar(EncuentroExportService $servicio)

    {

        if (!$this->campeonato_id || !$this->jornadaFiltro) {
            session()->flash('error', 'Debes seleccionar un campeonato y una jornada para exportar.');
            return;
        }

        return $servicio->exportarPorCampeonatoYFecha($this->campeonato_id, $this->jornadaFiltro);
    }

    public function render()
    {
        // Si no hay campeonato seleccionado, retorna vista vacía
        if (!$this->campeonato_id) {
            return view('livewire.fixture.fixture-index', [
                'encuentrosAgrupados' => collect()
            ]);
        }


        // Obtener los encuentros filtrados
        $encuentros = Encuentro::query()
            ->with(['equipoLocal', 'equipoVisitante', 'cancha', 'grupo', 'campeonato']) // Agregado campeonato
            ->when($this->campeonato_id, fn($q) => $q->where('campeonato_id', $this->campeonato_id))
            ->when($this->fechaFiltro, fn($q) => $q->whereDate('fecha', $this->fechaFiltro))
            ->when($this->equipoLocalFiltro, fn($q) => $q->whereHas('equipoLocal', fn($query) =>
            $query->where('nombre', 'like', '%' . $this->equipoLocalFiltro . '%')))
            ->when($this->jornadaFiltro, fn($q) => $q->where('fecha_encuentro', $this->jornadaFiltro))
            ->when($this->equipoVisitanteFiltro, fn($q) => $q->whereHas('equipoVisitante', fn($query) =>
            $query->where('nombre', 'like', '%' . $this->equipoVisitanteFiltro . '%')))
            ->when($this->grupoFiltro, fn($q) => $q->where('grupo_id', $this->grupoFiltro))
            ->when($this->estadoFiltro, fn($q) => $q->where('estado', $this->estadoFiltro))
            ->orderBy('fecha')
            ->orderBy('cancha_id')
            ->orderBy('hora')
            ->get();






        // Agrupar los encuentros por cancha y obtener el nombre del grupo
        $encuentrosAgrupados = $encuentros->groupBy(function ($encuentro) {
            $nombreCancha = $encuentro->cancha->nombre ?? 'Sin cancha';
            $nombreGrupo = $encuentro->grupo?->nombre ?? '';

            // Puedes retornar un string combinado si quieres agrupar por ambos, por ejemplo:
            //return $nombreCancha . ' _ ' . $nombreGrupo;

            // O simplemente devolver un array para luego usar ambos nombres
            return $nombreCancha;
        });


        // Pasar los goles almacenados en la base de datos al componente
        foreach ($encuentros as $encuentro) {
            $this->goles_local[$encuentro->id] = $encuentro->gol_local;
            $this->goles_visitante[$encuentro->id] = $encuentro->gol_visitante;
        }


        return view('livewire.fixture.fixture-ver', compact('encuentrosAgrupados'));
    }
}
