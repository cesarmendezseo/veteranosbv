<?php

namespace App\Livewire\Campeonato;

use App\Models\Campeonato;
use App\Models\Categoria;
use App\Models\Criterios_desempate;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class CampeonatoEditar extends Component
{
    public $campeonatoId;
    public $nombre;
    public $formato = '';
    public $cantidad_grupos;
    public $puntos_victoria;
    public $puntos_empate;
    public $puntos_derrota;
    public $puntos_tarjeta_amarilla;
    public $puntos_doble_amarilla;
    public $puntos_tarjeta_roja;
    public $total_equipos;
    public $equipos_por_grupo;
    public $categoria_id;

    // Colecciones y Arrays
    public $categorias;
    public $criterios = []; // Inicializar como array vacío

    public function mount($campeonatoId)
    {
        $this->campeonatoId = $campeonatoId;
        $campeonato = Campeonato::with(['categoria', 'criterioDesempate'])
            ->findOrFail($campeonatoId);

        $this->nombre = $campeonato->nombre;
        $this->formato = $campeonato->formato;
        $this->cantidad_grupos = $campeonato->cantidad_grupos;
        $this->equipos_por_grupo = $campeonato->cantidad_equipos_grupo;
        $this->total_equipos = $campeonato->total_equipos;

        $this->puntos_victoria = $campeonato->puntos_ganado;
        $this->puntos_empate = $campeonato->puntos_empatado;
        $this->puntos_derrota = $campeonato->puntos_perdido;
        $this->puntos_tarjeta_amarilla = $campeonato->puntos_tarjeta_amarilla;
        $this->puntos_doble_amarilla = $campeonato->puntos_doble_amarilla;
        $this->puntos_tarjeta_roja = $campeonato->puntos_tarjeta_roja;

        $this->categoria_id = $campeonato->categoria_id;
        $this->categorias = Categoria::all();

        // CORRECCIÓN AQUÍ: Convertir explícitamente a array asociativo simple
        $this->criterios = $campeonato->criterioDesempate()
            ->orderBy('orden')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'criterio' => $item->criterio,
                    'orden' => $item->orden
                ];
            })
            ->toArray();


        // Definimos cuáles deberían ser los criterios por defecto
        $criteriosBase = ['puntos', 'diferencia_goles', 'goles_favor', 'fair_play'];

        // Obtenemos los que ya existen en la BD
        $existentes = $campeonato->criterioDesempate()
            ->orderBy('orden')
            ->get();

        if ($existentes->count() > 0) {
            // Si ya existen, los cargamos
            $this->criterios = $existentes->map(function ($item) {
                return [
                    'id' => $item->id,
                    'criterio' => $item->criterio,
                    'orden' => $item->orden
                ];
            })->toArray();

            // OPCIONAL: Si solo trajo 3 pero quieres que siempre sean 4, 
            // buscamos cuál falta y lo agregamos al final
            $nombresExistentes = $existentes->pluck('criterio')->toArray();
            foreach ($criteriosBase as $base) {
                if (!in_array($base, $nombresExistentes)) {
                    $this->criterios[] = [
                        'id' => null,
                        'criterio' => $base,
                        'orden' => count($this->criterios) + 1
                    ];
                }
            }
        } else {
            // Si no tiene ninguno, creamos los 4 desde cero
            foreach ($criteriosBase as $index => $base) {
                $this->criterios[] = [
                    'id' => null,
                    'criterio' => $base,
                    'orden' => $index + 1
                ];
            }
        }
    }



    protected function rules()
    {
        return [
            'nombre' => 'required|string|max:255',
            'formato' => 'required|string',
            'categoria_id' => 'required|exists:categorias,id',
            'puntos_tarjeta_amarilla' => 'required|integer',
            'puntos_doble_amarilla' => 'required|integer',
            'puntos_tarjeta_roja' => 'required|integer',
            'total_equipos' => 'required_if:formato,todos_contra_todos,eliminacion_simple,eliminacion_doble',
            'cantidad_grupos' => 'required_if:formato,grupos',
            'equipos_por_grupo' => 'required_if:formato,grupos',
        ];
    }

    public function moveCriterioUp($index)
    {
        if ($index > 0) {
            $prevIndex = $index - 1;
            $temp = $this->criterios[$prevIndex];
            $this->criterios[$prevIndex] = $this->criterios[$index];
            $this->criterios[$index] = $temp;
        }
    }

    public function moveCriterioDown($index)
    {
        if ($index < count($this->criterios) - 1) {
            $nextIndex = $index + 1;
            $temp = $this->criterios[$nextIndex];
            $this->criterios[$nextIndex] = $this->criterios[$index];
            $this->criterios[$index] = $temp;
        }
    }

    public function editar()
    {
        $this->validate();
        $campeonato = Campeonato::findOrFail($this->campeonatoId);

        $data = [
            'nombre' => $this->nombre,
            'formato' => $this->formato,
            'categoria_id' => $this->categoria_id,
            'puntos_ganado' => $this->puntos_victoria,
            'puntos_empatado' => $this->puntos_empate,
            'puntos_perdido' => $this->puntos_derrota,
            'puntos_tarjeta_amarilla' => $this->puntos_tarjeta_amarilla,
            'puntos_doble_amarilla' => $this->puntos_doble_amarilla,
            'puntos_tarjeta_roja' => $this->puntos_tarjeta_roja,
        ];

        // Lógica de asignación de equipos
        if ($this->formato === 'grupos') {
            $data['cantidad_grupos'] = $this->cantidad_grupos;
            $data['cantidad_equipos_grupo'] = $this->equipos_por_grupo;
            $data['total_equipos'] = $this->cantidad_grupos * $this->equipos_por_grupo;
        } else {
            $data['cantidad_grupos'] = 1;
            $data['cantidad_equipos_grupo'] = $this->total_equipos;
            $data['total_equipos'] = $this->total_equipos;
        }

        // Lógica de puntos
        if (in_array($this->formato, ['todos_contra_todos', 'grupos'])) {
            $data['puntos_ganado'] = $this->puntos_victoria ?? 0;
            $data['puntos_empatado'] = $this->puntos_empate ?? 0;
            $data['puntos_perdido'] = $this->puntos_derrota ?? 0;
        }

        $campeonato->update($data);

        // ACTUALIZACIÓN DE CRITERIOS
        if (in_array($this->formato, ['todos_contra_todos', 'grupos'])) {
            // Eliminar antiguos y crear nuevos basándose en el orden del array
            Criterios_desempate::where('campeonato_id', $campeonato->id)->delete();

            foreach ($this->criterios as $index => $criterioData) {
                Criterios_desempate::create([
                    'campeonato_id' => $campeonato->id,
                    'criterio' => $criterioData['criterio'],
                    'orden' => $index + 1,
                ]);
            }
        } else {
            Criterios_desempate::where('campeonato_id', $campeonato->id)->delete();
        }

        $this->alert('success', 'Campeonato actualizado correctamente');
        return redirect()->route('campeonato.index');
    }

    public function render()
    {
        return view('livewire.campeonato.campeonato-editar');
    }
}
