<?php

namespace App\Livewire\Campeonato;

use App\Models\Campeonato;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class CampeonatoIndex extends Component
{
    use WithPagination;

    public $campeonatoSeleccionado;
    public $search = '';
    protected $listeners = ['refresh' => '$refresh'];
    public $itemId;
    public $campeonato;

    // Cada vez que se actualiza search, resetea la página
    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function eliminarCampeonato($id)
    {

        $campeonato = Campeonato::find($id);

        if (!$campeonato) {

            LivewireAlert::title('Error')
                ->text('El campeonato no existe.')
                ->error()
                ->toast()
                ->position('top')
                ->show();
            return;
        }

        // Verifico si tiene equipos asignados
        if ($campeonato->equipos()->exists()) {
            LivewireAlert::title('Error')
                ->text('No se puede eliminar el campeonato porque tiene equipos asignados..')
                ->error()
                ->toast()
                ->position('top')
                ->show();

            return;
        }


        $this->eliminar($id);
    }

    public function eliminar($id)
    {


        $this->itemId = $id;

        LivewireAlert::title('Atención')
            ->text('Estas seguro de querer borrar el campeonato?')
            ->asConfirm()
            ->onConfirm('deleteItem', ['id' => $this->itemId])
            ->onDeny('keepItem', ['id' => $this->itemId])
            ->show();
    }

    public function deleteItem($data)
    {

        $campeonatoId = $data['id'];


        $campeonato = Campeonato::find($campeonatoId);
        $campeonato->delete();

        LivewireAlert::title('OK')
            ->text('El campeonato se borro correctamente')
            ->toast()
            ->success()
            ->show();
    }

    public function keepItem() {}

    public function crear()
    {
        return redirect()->route('campeonato.crear');
    }

    public function verCampeonato($campeonatoId)
    {
        $this->campeonatoSeleccionado = Campeonato::with('grupos', 'criterioDesempate')->find($campeonatoId);
        $this->dispatch('static-modal');
    }

    // Propiedad computada para obtener los campeonatos filtrados
    public function getCampeonatosProperty()
    {
        $query = Campeonato::with('categoria')->orderBy('nombre', 'asc');

        // Aplicar el filtro si $search tiene valor
        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';

            $query->where(function ($q) use ($searchTerm) {
                // Buscar por nombre del campeonato
                $q->where('nombre', 'like', $searchTerm)
                    // Buscar por año (asumiendo que 'nombre' o alguna columna similar contiene el año, o si tienes una columna 'anio')
                    // Si tienes una columna 'anio' (o similar):
                    // ->orWhere('anio', 'like', $searchTerm) 
                ;
            });
        }

        return $query->get();
    }

    public function render()
    {
        $campeonatos = Campeonato::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%')
                        ->orWhereYear('created_at', $this->search); // búsqueda por año
                });
            })
            ->orderBy('nombre')
            ->paginate(10);

        return view('livewire.campeonato.campeonato-index', [
            'campeonatos' => $campeonatos,
        ]);
    }
}
