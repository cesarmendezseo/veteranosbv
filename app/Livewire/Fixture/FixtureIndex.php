<?php

namespace App\Livewire\Fixture;

use App\Models\Campeonato;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class FixtureIndex extends Component
{
    public $campeonatos;
    public $campeonatoSeleccionado;
    public $formato;
    public $campeonatoId;

    public function mount()
    {
        $this->campeonatos = Campeonato::with('grupos', 'categoria')
            ->where('finalizado', 0)
            ->get();
    }

    public function crear($campeonatoId)
    {
        $campeonato = Campeonato::findOrFail($campeonatoId);

        if ($campeonato->formato === 'todos_contra_todos' || $campeonato->formato === 'grupos') {

            return redirect()->route('fixture.crear', ['campeonatoId' => $campeonato->id]);
        } else {
            return redirect()->route('fixture.eliminatoria', ['campeonatoId' => $campeonato->id]);
        }
    }

    public function ver($campeonatoId)
    {
        $campeonato = Campeonato::findOrFail($campeonatoId);

        if ($campeonato->formato === 'todos_contra_todos' || $campeonato->formato === 'grupos') {

            return redirect()->route('fixture.ver', ['campeonatoId' => $campeonato->id]);
        } else {
            return redirect()->route('fixture.eliminatoria.ver', ['campeonatoId' => $campeonato->id]);
        }
    }

    //

    public function render()
    {

        return view('livewire.fixture.fixture-index');
    }
}
