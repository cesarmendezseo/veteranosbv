<?php

namespace App\Livewire\Campeonato;

use App\Models\Campeonato;
use App\Models\Categoria;
use App\Models\Criterios_desempate;
use App\Models\FaseCampeonato;
use App\Models\Grupo;
use Illuminate\Support\Facades\DB;
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

        // 1. Mapeo de formato a tipo de fase
        $isGrupos = $this->formato === 'grupos';
        $isEliminacion = in_array($this->formato, ['eliminacion_simple', 'eliminacion_doble']);

        $statusInicial = $isGrupos ? 'fase_de_grupos' : ($isEliminacion ? 'eliminacion_directa' : 'todos_contra_todos');

        // El tipo de fase para el modelo FaseCampeonato
        $tipoFase = $this->formato;

        try {
            DB::transaction(function () use ($isGrupos, $statusInicial, $tipoFase) {

                // 2. Crear el Campeonato primero
                $campeonato = \App\Models\Campeonato::create([
                    'nombre' => $this->nombre,
                    'formato' => $this->formato,
                    'cantidad_grupos' => $isGrupos ? (int) $this->cantidad_grupos : 1,
                    'cantidad_equipos_grupo' => $isGrupos ? (int) $this->equipos_por_grupo : 0,
                    'total_equipos' => intval($this->total_equipos),
                    'puntos_ganado' => $this->puntos_ganado,
                    'puntos_empatado' => $this->puntos_empatado,
                    'puntos_perdido' => $this->puntos_perdido,
                    'categoria_id' => (int) $this->categoria_id,
                    'status' => $statusInicial,
                    'config_sancion' => 'cada_7dias',
                    // 'fase_actual_id' se queda null momentáneamente
                ]);

                // 3. Crear la Fase Inicial usando tu modelo FaseCampeonato
                $fase = \App\Models\FaseCampeonato::create([
                    'campeonato_id' => $campeonato->id,
                    'nombre' => $isGrupos ? 'Fase de Grupos' : 'Fase Regular',
                    'tipo_fase' => $tipoFase,
                    'orden' => 1,
                    'estado' => 'activa', // Usando el string que espera tu lógica
                    'reglas_clasificacion' => [], // Array vacío por el cast
                ]);

                // 4. AHORA vinculamos la fase al campeonato (CORRECCIÓN DEL NULL)
                $campeonato->update([
                    'fase_actual_id' => $fase->id
                ]);

                // 5. Criterios de desempate
                if ($this->formato === 'todos_contra_todos' || $isGrupos) {
                    foreach ($this->criterios as $index => $criterio) {
                        \App\Models\Criterios_desempate::create([
                            'campeonato_id' => $campeonato->id,
                            'criterio' => $criterio,
                            'orden' => $index + 1,
                        ]);
                    }
                }

                // 6. Lógica de Grupos
                if ($isGrupos) {
                    $letras = range('A', 'Z');
                    for ($i = 0; $i < $this->cantidad_grupos; $i++) {
                        \App\Models\Grupo::create([
                            'campeonato_id' => $campeonato->id,
                            'nombre' => 'Grupo ' . $letras[$i],
                            'cantidad_equipos' => $this->equipos_por_grupo,
                        ]);
                    }
                }
            });

            $this->alert('success', '¡Campeonato y Fase inicial creados!');
            return redirect()->route('campeonato.index');
        } catch (\Exception $e) {
            // En caso de error, LivewireAlert o un log
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }




    public function render()
    {
        $categorias = Categoria::all(); // Obtiene todas las categorías de la base de datos en cada render
        return view('livewire.campeonato.campeonato-crear', ['categorias' => $categorias]);
    }
}
