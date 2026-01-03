<?php

namespace App\Livewire\Campeonato;

use App\Models\Campeonato;
use App\Models\Categoria;
use App\Models\Criterios_desempate;
use App\Models\Grupo;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
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
    public $total = 0;


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
            'formato' => 'required|in:todos_contra_todos,grupos,eliminacion_simple,eliminacion_doble',
            'categoria_id' => 'required|exists:categorias,id',
            'puntos_ganado' => 'required|integer',
            'puntos_empatado' => 'required|integer',
            'puntos_perdido' => 'required|integer',
            'puntos_tarjeta_amarilla' => 'required|integer',
            'puntos_doble_amarilla' => 'required|integer',
            'puntos_tarjeta_roja' => 'required|integer',

        ];

        if ($this->formato === 'todos_contra_todos') {
            $rules['total_equipos'] = 'required|integer|min:2'; // Total equipos para liga
        } elseif ($this->formato === 'grupos') {
            $rules['cantidad_grupos'] = 'required|integer|min:1';
            $rules['equipos_por_grupo'] = 'required|integer|min:1';
            // ¡NUEVO! Validación para formatos de Eliminación
        } elseif ($this->formato === 'eliminacion_simple' || $this->formato === 'eliminacion_doble') {
            // Necesitas el total de equipos para el bracket de eliminación
            $rules['total_equipos'] = 'required|integer|min:2';
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
        // Determinar los valores por defecto para los campos relacionados con grupos
        $isGrupos = $this->formato === 'grupos';
        $isEliminacion = in_array($this->formato, ['eliminacion_simple', 'eliminacion_doble']);
        $isTodosContraTodos = $this->formato === 'todos_contra_todos';

        // Determinar el status inicial
        $statusInicial = 'todos_contra_todos'; // Por defecto, el de la BD
        if ($isGrupos) {
            $statusInicial = 'fase_de_grupos';
        } elseif ($isEliminacion) {
            $statusInicial = 'eliminacion_directa';
        }

        $campeonato = Campeonato::create([

            'nombre' => $this->nombre,
            'formato' => $this->formato,
            // Asignar null o 1 a los campos de grupos si no es formato 'grupos'
            'cantidad_grupos' => $isGrupos ? (int) $this->cantidad_grupos : 1,
            'cantidad_equipos_grupo' => $isGrupos ? (int) $this->equipos_por_grupo : 0,
            // **CORRECCIÓN PRINCIPAL**: Asegurarse de que el total de equipos sea INT
            'total_equipos' => intval($this->total_equipos),

            'puntos_ganado' => $this->puntos_ganado,
            'puntos_empatado' =>  $this->puntos_empatado,
            'puntos_perdido' => $this->puntos_perdido,
            'categoria_id' => (int) $this->categoria_id,
            'status' => $statusInicial, // Asignar el status inicial
            'config_sancion' => 'cada_7dias', // Valor por defecto

        ]);

        // Guardar criterios de desempate (Solo son relevantes para liga/grupos)
        if ($isGrupos || $isTodosContraTodos) {
            foreach ($this->criterios as $index => $criterio) {
                Criterios_desempate::create([
                    'campeonato_id' => $campeonato->id,
                    'criterio' => $criterio,
                    'orden' => $index + 1,
                ]);
            }
        }


        // Crear los grupos/la "llave"
        if ($isGrupos) {
            // Lógica existente para crear N grupos (A, B, C...)
            $letras = range('A', 'Z');

            for ($i = 0; $i < $this->cantidad_grupos; $i++) {
                Grupo::create([
                    'campeonato_id' => $campeonato->id,
                    'nombre' => 'Grupo ' . $letras[$i],
                    'cantidad_equipos' => $this->cantidad_equipos_grupo,
                ]);
            }
        }

        // ... Alerta de éxito y redirección
        LivewireAlert::title('Perfecto!!')
            ->text('Campeonato creado correctamente')
            ->success()
            ->toast()
            ->show();



        return redirect()->route('campeonato.index'); // Redirige a la lista de campeonatos
    }




    public function render()
    {
        $categorias = Categoria::all(); // Obtiene todas las categorías de la base de datos en cada render
        return view('livewire.campeonato.campeonato-crear', ['categorias' => $categorias]);
    }
}
