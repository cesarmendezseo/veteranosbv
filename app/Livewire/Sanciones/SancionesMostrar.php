<?php

namespace App\Livewire\Sanciones;

use App\Models\Campeonato;
use App\Models\Encuentro;
use Livewire\Component;
use App\Models\Sanciones;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class SancionesMostrar extends Component
{
    use WithPagination;

    public $jugador_id;
    public $campeonato_id = '';
    public $search = '';
    public $soloPendientes = false;

    // Propiedades para la edición
    public $sancion_id;
    public $edit_partidos_sancionados;
    public $edit_fecha_fin;
    public $edit_observacion;
    public $edit_medida;
    public $modalEditVisible = false;
    public $id;
    public $nombreJugador;
    public $edit_encuentro_id;
    public $edit_fecha_inicio;
    public $edit_campeonato_id;


    // Resetear página al cambiar filtros
    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function updatedCampeonatoId()
    {
        $this->resetPage();
    }
    public function updatedSoloPendientes()
    {
        $this->resetPage();
    }

    public function editarSancion($id)
    {
        $sancion = Sanciones::findOrFail($id);
        $this->nombreJugador = $sancion->jugador->apellido . ', ' . $sancion->jugador->nombre;
        $this->sancion_id = $id;
        $this->edit_medida = $sancion->medida;
        $this->edit_partidos_sancionados = $sancion->partidos_sancionados;
        $this->edit_fecha_fin = $sancion->fecha_fin
            ? \Carbon\Carbon::parse($sancion->fecha_fin)->format('Y-m-d')
            : null;

        $this->edit_fecha_inicio = $sancion->fecha_inicio
            ? \Carbon\Carbon::parse($sancion->fecha_inicio)->format('Y-m-d')
            : null;

        $this->edit_encuentro_id = $sancion->encuentro_id;
        $this->edit_campeonato_id = $sancion->campeonato_id;
        $this->edit_observacion = $sancion->observacion;

        $this->modalEditVisible = true;
    }

    public function actualizarSancion()
    {
        $this->validate([
            'edit_partidos_sancionados' => 'required_if:edit_medida,partidos|integer|min:0',
            'edit_fecha_fin' => 'required_if:edit_medida,tiempo|nullable|date',
            'edit_observacion' => 'nullable|string'
        ]);

        $sancion = Sanciones::findOrFail($this->sancion_id);
        $sancion->update([
            'partidos_sancionados' => $this->edit_medida === 'partidos' ? $this->edit_partidos_sancionados : 0,
            'fecha_fin' => $this->edit_medida === 'tiempo' ? $this->edit_fecha_fin : null,
            'observacion' => $this->edit_observacion,
            // Recalcular si ya está cumplida
            'cumplida' => ($this->edit_medida === 'partidos' && $sancion->partidos_cumplidos >= $this->edit_partidos_sancionados)
        ]);

        $this->cerrarModal();
        LivewireAlert::title('Sanción actualizada')
            ->toast()
            ->success()
            ->show();
    }

    public function confirmarEliminacion($id)
    {

        LivewireAlert::title('Desea eliminar este sanción?')
            ->text('?')
            ->asConfirm()
            ->onConfirm('deleteItem', ['id' => $id])
            ->onDeny('keepItem', ['id' => $this->id])
            ->show();
    }
    public function deleteItem($data)
    {
        Sanciones::destroy($data['id']);

        // Delete logic
    }

    public function keepItem($data)
    {
        $itemId = $data['id'];
        // Keep logic
    }

    public function cerrarModal()
    {
        $this->reset([
            'sancion_id',
            'edit_partidos_sancionados',
            'edit_fecha_fin',
            'edit_observacion',
            'edit_medida',
            'modalEditVisible'
        ]);
    }

    public function render()
    {
        $campeonatos = Campeonato::orderBy('nombre', 'asc')->get();

        $sanciones = Sanciones::query()
            ->with(['jugador', 'campeonato', 'sancionable.equipoLocal', 'sancionable.equipoVisitante'])

            // 1. Filtro por Campeonato
            ->when($this->campeonato_id, function ($query) {
                $query->where('campeonato_id', $this->campeonato_id);
            })

            // 2. Filtro: Solo los que aún deben fechas
            ->when($this->soloPendientes, function ($query) {
                $query->where(function ($q) {
                    $q->whereColumn('partidos_cumplidos', '<', 'partidos_sancionados')
                        ->orWhere('fecha_fin', '>=', now());
                });
            })

            // 3. Filtro por Búsqueda de texto
            ->when($this->search, function ($query) {
                $query->whereHas('jugador', function ($subQuery) {
                    $subQuery->where(function ($q) {
                        $q->where('apellido', 'like', '%' . $this->search . '%')
                            ->orWhere('nombre', 'like', '%' . $this->search . '%');
                    })->orWhere('documento', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('etapa_sancion', 'desc')
            ->paginate(20);
        $encuentros = Encuentro::with(['equipoLocal', 'equipoVisitante'])
            ->orderBy('fecha', 'desc')
            ->get();

        return view('livewire.sanciones.sanciones-mostrar', [
            'sanciones' => $sanciones,
            'campeonatos' => $campeonatos,
            'encuentros' => $encuentros,
        ]);
    }
}
