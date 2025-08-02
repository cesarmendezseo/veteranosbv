<?php

namespace App\Livewire\Campeonato;

use App\Models\Campeonato;
use App\Models\Categoria;
use App\Models\Criterios_desempate;
use App\Models\Grupo;
use SweetAlert2\Laravel\Swal;
use Livewire\Component;

class CampeonatoCrear extends Component
{
    public $nombre;
    public $formato = '';
    public $cantidad_grupos;
    public $cantidad_equipos_grupo;
    public $puntos_ganado = 3;
    public $puntos_empatado = 1;
    public $puntos_perdido = 0;
    public $puntos_tarjeta_amarilla = -5;
    public $puntos_doble_amarilla = -3;
    public $puntos_tarjeta_roja = -1;
    public $total_equipos;
    public $equipos_por_grupo;
    public $categoria_id;
    public $categorias;


    public $criterios = [
        'puntos',
        'diferencia_goles',
        'goles_favor',
        'fairplay',
    ];

    public function mount()
    {
        $this->categorias = Categoria::all();
    }


    public function rules()
    {

        $rules = [
            'nombre' => 'required|string|max:255',
            'formato' => 'required|in:todos_contra_todos,grupos',
            'categoria_id' => 'required|exists:categorias,id',
            'puntos_ganado' => 'required|integer',
            'puntos_empatado' => 'required|integer',
            'puntos_perdido' => 'required|integer',
            'puntos_tarjeta_amarilla' => 'required|integer',
            'puntos_doble_amarilla' => 'required|integer',
            'puntos_tarjeta_roja' => 'required|integer',

        ];

        if ($this->formato === 'todos_contra_todos') {
            $rules['total_equipos'] = 'required|integer|min:2';
        } elseif ($this->formato === 'grupos') {
            $rules['cantidad_grupos'] = 'required|integer|min:1';
            $rules['equipos_por_grupo'] = 'required|integer|min:1';
        }

        return $rules; // Ejecuta las reglas de validación definidas en el método rules()
    }

    public function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'string' => 'El campo :attribute debe contener texto.',
            'integer' => 'El campo :attribute debe ser un número entero.',
            'min' => 'El campo :attribute debe tener un valor mínimo de :min.',
            'in' => 'El campo :attribute contiene una opción no válida.',
            'exists' => 'La opción seleccionada en :attribute no es válida.',
        ];
    }

    public function attributes()
    {
        return [
            'nombre' => 'nombre del torneo',
            'formato' => 'formato del torneo',
            'categoria_id' => 'categoría',
            'puntos_ganado' => 'puntos por victoria',
            'puntos_empatado' => 'puntos por empate',
            'puntos_perdido' => 'puntos por derrota',
            'puntos_tarjeta_amarilla' => 'puntos por tarjeta amarilla',
            'puntos_doble_amarilla' => 'puntos por doble amarilla',
            'puntos_tarjeta_roja' => 'puntos por tarjeta roja',
            'total_equipos' => 'total de equipos',
            'cantidad_grupos' => 'cantidad de grupos',
            'equipos_por_grupo' => 'equipos por grupo',
        ];
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


    public function save()
    {

        $this->validate();

        $campeonato = Campeonato::create([
            'nombre' => $this->nombre,
            'formato' => $this->formato,
            'cantidad_grupos' => $this->formato === 'grupos' ? (int) $this->cantidad_grupos : 1,
            'cantidad_equipos_grupo' => $this->formato === 'grupos' ? (int) $this->equipos_por_grupo : null,

            'puntos_ganado' => $this->puntos_ganado,
            'puntos_empatado' =>  $this->puntos_empatado,
            'puntos_perdido' => $this->puntos_perdido,
            'puntos_tarjeta_amarilla' => $this->puntos_tarjeta_amarilla,
            'puntos_doble_amarilla' => $this->puntos_doble_amarilla,
            'puntos_tarjeta_roja' => $this->puntos_tarjeta_roja,
            'categoria_id' => (int) $this->categoria_id,
        ]);
        // Guardar criterios de desempate
        foreach ($this->criterios as $index => $criterio) {
            Criterios_desempate::create([
                'campeonato_id' => $campeonato->id,
                'criterio' => $criterio,
                'orden' => $index + 1,
            ]);
        }
        // Crear los grupos
        if ($this->formato === 'grupos') {
            $letras = range('A', 'Z'); // para nombres como Grupo A, B, C...

            for ($i = 0; $i < $this->cantidad_grupos; $i++) {
                Grupo::create([
                    'campeonato_id' => $campeonato->id,
                    'nombre' => 'Grupo ' . $letras[$i],
                    'cantidad_equipos' => $this->cantidad_equipos_grupo,
                ]);
            }
        } elseif ($this->formato === 'todos_contra_todos') {

            Grupo::create([

                'campeonato_id' => $campeonato->id,
                'nombre' => 'Todos contra todos',
                'cantidad_equipos' => $this->total_equipos ?? 0,
            ]);
        }


        Swal::toast([
            'title' => 'Perfecto!!',
            'text' => 'Campeonato creado correctamente.',
            'icon' => 'success',
            'confirmButtonText' => 'OK'
        ]);



        return redirect()->route('campeonato.index'); // Redirige a la lista de campeonatos
    }




    public function render()
    {
        $categorias = Categoria::all(); // Obtiene todas las categorías de la base de datos en cada render
        return view('livewire.campeonato.campeonato-crear', ['categorias' => $categorias]);
    }
}
