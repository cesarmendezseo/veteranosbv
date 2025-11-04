<?php

namespace App\Livewire\Fixture;


use App\Models\Campeonato;
use App\Models\Canchas;
use App\Models\Eliminatoria;
use App\Models\Grupo;
use App\Models\Sanciones;
use App\Services\EncuentroExportService;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

use Maatwebsite\Excel\Facades\Excel;


class EliminatoriaVer extends Component
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
    public $encuentroEditandoId;
    public $editFecha, $editHora, $editEstado, $editCanchaId;
    public $showEditModal = false;
    public $canchas = [];
    public $campeonatoSeleccionado;
    public $formato;
    public $encuentro;
    public $encuentrosPorCancha = [];
    public $penal_visitante = [];
    public $penal_local = [];
    public $faseFiltro;
    public $fases;
    public $mostrarFiltros = false;
    public $encuentroId;
    public $estadoOriginal;



    public function mount($campeonatoId)
    {

        $this->canchas = Canchas::all(); // Cargar todas las canchas
        // Obtener los años disponibles

        $this->campeonato_id = $campeonatoId;
        $this->campeonatos = $this->campeonatoId;
        $this->updatedCampeonatoId();
        $this->fases = Eliminatoria::where('campeonato_id', $this->campeonato_id)
            ->distinct()
            ->pluck('fase')
            ->toArray();
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
        $this->jornadas = Eliminatoria::where('campeonato_id', $this->campeonato_id)
            ->distinct()
            ->orderBy('fecha')
            ->pluck('fecha');
    }


    public function guardarGoles($encuentroId)
    {
        $encuentro = Eliminatoria::find($encuentroId);

        if ($encuentro) {
            // Actualizar goles y estado
            $encuentro->goles_local = $this->goles_local[$encuentroId] ?? 0;
            $encuentro->goles_visitante = $this->goles_visitante[$encuentroId] ?? 0;
            $encuentro->penales_local = $this->penal_local[$encuentroId] ?? '0';
            $encuentro->penales_visitante = $this->penal_visitante[$encuentroId] ?? '0';
            $encuentro->estado = 'Jugado';
            $encuentro->save();

            // 🔄 Actualizar sanciones
            $this->actualizarSancionesCumplidas($encuentro);

            // Mensaje
            LivewireAlert::title('Éxito')
                ->text('Goles guardados y sanciones actualizadas.')
                ->success()
                ->asConfirm('Ok', '#3085d6')
                ->toast()
                ->timer(3000)
                ->show();
        }
    }


    public function actualizarSancionesCumplidas($encuentro)
    {
        // Obtener jugadores sancionados en este campeonato
        $sanciones = Sanciones::where('campeonato_id', $encuentro->campeonato_id)
            ->where('cumplida', false)
            ->get();

        foreach ($sanciones as $sancion) {
            $jugador = $sancion->jugador_id;




            $sancion->partidos_cumplidos += 1;

            // Si ya cumplió todos los partidos, marcar como cumplida
            if ($sancion->partidos_cumplidos >= $sancion->partidos_sancionados) {
                $sancion->cumplida = true;
            }

            $sancion->save();
        }
    }
    //=============================================0
    /*    public function jugadorParticipó($jugadorId, $encuentro)
    {
        // Suponiendo que cada encuentro tiene jugadores relacionados
        return $encuentro->jugadores()->where('id', $jugadorId)->exists();
    } */

    // ==================================0EDITAR ENCUENTRO========================================
    public function editEncuentro($id)
    {
        $encuentro = Eliminatoria::findOrFail($id);

        $this->encuentroEditandoId = $id;
        $this->editFecha = $encuentro->fecha;
        $this->editHora = $encuentro->hora;

        $this->editEstado = $encuentro->estado;

        $this->editCanchaId = $encuentro->cancha;
        $this->canchas = Canchas::all();
        $this->estadoOriginal = $encuentro;
        $this->showEditModal = true;
    }

    //==============================================================
    public function guardarEdicion()
    {

        /*  $encuentro = Eliminatoria::findOrFail($this->encuentroEditandoId); */
        $encuentro = Eliminatoria::find($this->encuentroEditandoId);
        // Verificar si el estado cambió de Jugado a otro
        if ($this->estadoOriginal === 'Jugado' && $this->editEstado !== 'Jugado') {
            $this->revertirSancionesCumplidas($encuentro);
        }
        if (!$encuentro) {
            LivewireAlert::title('Error')
                ->text('No se encontro el encuentro a editar.')
                ->error()
                ->asConfirm('Ok', '#3085d6')
                ->toast()
                ->timer(3000)
                ->show();
            return;
        }

        $encuentro->update([
            'fecha' => $this->editFecha,
            'hora' => $this->editHora,
            'estado' => $this->editEstado,
            'cancha' => $this->editCanchaId,
        ]);

        $this->reset(['showEditModal', 'encuentroEditandoId', 'editFecha', 'editHora', 'editEstado', 'editCanchaId']);
        // Mensaje de éxito
        LivewireAlert::title('Éxito')
            ->text('Actualización exitosa.')
            ->success()
            ->asConfirm('Ok', '#3085d6')
            ->toast()
            ->timer(3000)
            ->show();
    }

    //=======================================================================
    public function revertirSancionesCumplidas($encuentro)
    {
        $sanciones = Sanciones::where('campeonato_id', $encuentro->campeonato_id)
            ->where('partidos_cumplidos', '>', 0)
            ->get();

        foreach ($sanciones as $sancion) {
            $jugador = $sancion->jugador_id;

            if ($this->jugadorParticipó($jugador, $encuentro)) {
                $sancion->partidos_cumplidos -= 1;

                if ($sancion->partidos_cumplidos < $sancion->partidos_sancionados) {
                    $sancion->cumplida = false;
                }

                $sancion->save();
            }
        }
    }
    //=====================================================================

    public function eliminarEncuentro($encuentroId) // The parameter name should match the key in your dispatch payload (Id)
    {

        $encuentro = Eliminatoria::findOrFail($encuentroId);

        $this->encuentroId = $encuentroId;
        if ($encuentro->estado === 'Jugado') {
            // Mensaje de éxito
            LivewireAlert::title('Error')
                ->text('No se puede borrar el encuentro porque el mismo ya se encuentra jugado".')
                ->warning()
                ->asConfirm('Ok', '#3085d6')
                ->toast()

                ->show();
        } else {

            LivewireAlert::title('Atención !!! ')
                ->text('Esta seguro de borrar el encuentro?')
                ->asConfirm()
                ->onConfirm('deleteItem', ['id' => $this->encuentroId])
                ->onDeny('keepItem', ['id' => $this->encuentroId])
                ->show();
        };
    }

    public function deleteItem($data)
    {

        $itemId = $data['id'];
        $encuentro = Eliminatoria::findOrFail($itemId);

        $encuentro->delete();

        // Mensaje de éxito
        LivewireAlert::title('Éxito')
            ->text('Encuentro eliminado correctamente.')
            ->success()
            ->asConfirm('Ok', '#3085d6')
            ->toast()
            ->timer(3000)
            ->show();
        // Cancel logic
    }

    public function keepItem($data)
    {
        $itemId = $data['id'];
        // Keep logic
    }

    //=============exportar a exel ===============

    public function exportar(EncuentroExportService $servicio)

    {


        if (!$this->campeonato_id || !$this->faseFiltro) {
            LivewireAlert::title('Atencion')
                ->text('Debe seleccionar una FASE.')
                ->error()
                ->asConfirm('Ok', '#3085d6')
                ->toast()
                ->timer(3000)
                ->show();
            return;
        }

        return $servicio->exportarEliminatoria($this->campeonato_id, $this->faseFiltro);
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
        $encuentros = Eliminatoria::query()
            ->with(['equipoLocal', 'equipoVisitante', 'canchas', 'grupo', 'campeonato']) // Agregado campeonato
            ->when($this->campeonato_id, fn($q) => $q->where('campeonato_id', $this->campeonato_id))
            ->when($this->fechaFiltro, fn($q) => $q->whereDate('fecha', $this->fechaFiltro))
            ->when($this->equipoLocalFiltro, fn($q) => $q->whereHas('equipoLocal', fn($query) =>
            $query->where('nombre', 'like', '%' . $this->equipoLocalFiltro . '%')))
            ->when($this->jornadaFiltro, fn($q) => $q->where('fecha', $this->jornadaFiltro))
            ->when($this->equipoVisitanteFiltro, fn($q) => $q->whereHas('equipoVisitante', fn($query) =>
            $query->where('nombre', 'like', '%' . $this->equipoVisitanteFiltro . '%')))
            ->when($this->grupoFiltro, fn($q) => $q->where('grupo_id', $this->grupoFiltro))
            ->when($this->estadoFiltro, fn($q) => $q->where('estado', $this->estadoFiltro))
            ->when($this->faseFiltro, fn($q) => $q->where('fase', $this->faseFiltro))
            ->orderBy('fecha')
            ->orderBy('cancha')
            ->orderBy('hora')
            ->get();



        $encuentrosAgrupados = $encuentros->groupBy(function ($encuentro) {
            return ($encuentro->canchas instanceof \App\Models\Canchas)
                ? $encuentro->canchas->nombre
                : 'Sin cancha';
        });




        // Pasar los goles almacenados en la base de datos al componente
        foreach ($encuentros as $encuentro) {
            $this->goles_local[$encuentro->id] = $encuentro->goles_local;
            $this->goles_visitante[$encuentro->id] = $encuentro->goles_visitante;
            $this->penal_local[$encuentro->id] = $encuentro->penales_local;
            $this->penal_visitante[$encuentro->id] = $encuentro->penales_visitante;
        }



        return view('livewire.fixture.eliminatoria-ver', compact('encuentrosAgrupados'));
    }
}
