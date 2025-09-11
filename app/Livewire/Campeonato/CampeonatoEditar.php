<?php

namespace App\Livewire\Campeonato;

use App\Models\Campeonato;
use App\Models\Categoria;
use Livewire\Component;
use App\Models\Criterios_desempate;
use SweetAlert2\Laravel\Swal;

class CampeonatoEditar extends Component
{
    public $nombre;
    public $formato = '';
    public $cantidad_grupos;
    public $cantidad_equipos_grupo;
    public $puntos_victoria;
    public $puntos_empate;
    public $puntos_derrota;
    public $puntos_tarjeta_amarilla;
    public $puntos_doble_amarilla;
    public $puntos_tarjeta_roja;
    public $total_equipos;
    public $equipos_por_grupo;
    public $categoria_id;
    public $categorias;
    public $criterios;
    public $campeonatoId;
    public $Criterios_desempate;

    public function mount($campeonatoId)
    {
        $campeonato = Campeonato::with('grupos', 'categoria', 'criterioDesempate')
            ->findOrFail($campeonatoId);

        $this->nombre = $campeonato->nombre;
        $this->formato = $campeonato->formato;
        $this->cantidad_grupos = $campeonato->cantidad_grupos;
        $this->equipos_por_grupo = $campeonato->cantidad_equipos_grupo;
        $this->puntos_victoria = $campeonato->puntos_ganado;
        $this->puntos_empate = $campeonato->puntos_empatado;
        $this->puntos_derrota = $campeonato->puntos_perdido;
        $this->puntos_tarjeta_amarilla = $campeonato->puntos_tarjeta_amarilla;
        $this->puntos_doble_amarilla = $campeonato->puntos_doble_amarilla;
        $this->puntos_tarjeta_roja = $campeonato->puntos_tarjeta_roja;

        $this->categoria_id = $campeonato->categoria_id;
        $this->categorias = Categoria::all();

        if ($campeonato->grupos->isEmpty()) {
            $this->total_equipos = 0; // Si no hay grupos, no hay equipos
        } else {
            foreach ($campeonato->grupos as $grupo) {
                $this->total_equipos = $grupo->cantidad_equipos;
            }
        }

        // Si querés guardar los criterios para usar en la vista también:
        $this->criterios = $campeonato->criterioDesempate;
    }


    public function moveCriterioUp($index)
    {
        if ($index > 0) {
            $temp = $this->criterios[$index - 1];
            $this->criterios[$index - 1] = $this->criterios[$index];
            $this->criterios[$index] = $temp;
        }
    }

    public function moveCriterioDown($index)
    {
        if ($index < count($this->criterios) - 1) {
            $temp = $this->criterios[$index + 1];
            $this->criterios[$index + 1] = $this->criterios[$index];
            $this->criterios[$index] = $temp;
        }
    }


    public function editar()
    {


        $this->validate([
            'nombre' => 'required|string|max:255',
            'formato' => 'required|string',
            'cantidad_grupos' => 'required|integer|min:1',
            'cantidad_equipos_grupo' => 'nullable|integer|min:1',
            'puntos_victoria' => 'required|integer|min:0',
            'puntos_empate' => 'required|integer|min:0',
            'puntos_derrota' => 'required|integer|min:0',
            'puntos_tarjeta_amarilla' => 'required|integer',
            'puntos_doble_amarilla' => 'required|integer',
            'puntos_tarjeta_roja' => 'required|integer',
            'total_equipos' => 'nullable|integer|min:1',
            'equipos_por_grupo' => 'nullable|integer|min:1',
            'categoria_id' => 'required|exists:categorias,id'
        ]);

        // Logic to update the championship in the database goes here
        $campeonato = Campeonato::findOrFail($this->campeonatoId);
        $campeonato->update([
            'nombre' => $this->nombre,
            'formato' => $this->formato,
            'cantidad_grupos' => $this->cantidad_grupos,
            'cantidad_equipos_grupo' => $this->equipos_por_grupo,
            'puntos_ganado' => $this->puntos_victoria,
            'puntos_empatado' => $this->puntos_empate,
            'puntos_perdido' => $this->puntos_derrota,
            'puntos_tarjeta_amarilla' => $this->puntos_tarjeta_amarilla,
            'puntos_doble_amarilla' => $this->puntos_doble_amarilla,
            'puntos_tarjeta_roja' => $this->puntos_tarjeta_roja,
            'categoria_id' => $this->categoria_id,
        ]);
        // Eliminar criterios anteriores del campeonato
        Criterios_desempate::where('campeonato_id', $campeonato->id)->delete();

        // Crear los nuevos criterios con su orden
        foreach ($this->criterios as $index => $criterio) {
            Criterios_desempate::create([
                'campeonato_id' => $campeonato->id,
                'criterio' => is_array($criterio) ? $criterio['criterio'] : $criterio->criterio,
                'orden' => $index + 1,
            ]);
        }


        // Flash message to indicate success
        Swal::toast([
            'title' => 'Campeonato actualizado correctamente!',

            'icon' => 'success',
            'confirmButtonText' => 'OK'
        ]);

        return redirect()->route('campeonato.index');
    }


    public function render()
    {
        return view('livewire.campeonato.campeonato-editar');
    }
}
