<?php

namespace App\Livewire\Campeonato;

use App\Models\Campeonato;
use App\Models\Categoria;
use App\Models\Criterios_desempate;
use App\Models\Grupo;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert as FacadesLivewireAlert;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class CampeonatoCrear2 extends Component
{

    // Propiedades básicas del campeonato
    public $nombre;
    public $formato = '';
    public $cantidad_grupos;
    public $equipos_por_grupo;
    public $total_equipos;
    public $categoria_id;
    public $categorias;

    // Propiedades de puntuación
    public $puntos_ganado = 3;
    public $puntos_empatado = 1;
    public $puntos_perdido = 0;
    public $puntos_tarjeta_amarilla = -5;
    public $puntos_doble_amarilla = -3;
    public $puntos_tarjeta_roja = -1;

    // Criterios de desempate
    public $criterios = [
        'puntos',
        'diferencia_goles',
        'goles_favor',
        'fairplay',
    ];

    // NUEVO: Configuración de fases y liguillas
    public $tiene_liguilla = false;
    public $config_liguilla = [
        'equipos_superiores' => 16,
        'equipos_inferiores' => 12,
        'formato_superior' => 'eliminacion_simple',
        'formato_inferior' => 'eliminacion_simple',
        'criterio_clasificacion' => '',
        'cantidad_por_grupo' => 2,
        'cantidad_terceros' => 1,
    ];

    // Paso del wizard
    public $paso_actual = 1;

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
            $rules['total_equipos'] = 'required|integer|min:2';
        } elseif ($this->formato === 'grupos') {
            $rules['cantidad_grupos'] = 'required|integer|min:1';
            $rules['equipos_por_grupo'] = 'required|integer|min:1';
        } elseif ($this->formato === 'eliminacion_simple' || $this->formato === 'eliminacion_doble') {
            $rules['total_equipos'] = 'required|integer|min:2';
        }

        // Validaciones para liguilla (solo si está habilitada)
        if ($this->tiene_liguilla) {
            $rules['config_liguilla.equipos_superiores'] = 'required|integer|min:2';
            $rules['config_liguilla.formato_superior'] = 'required|string';

            if ($this->formato === 'todos_contra_todos') {
                $rules['config_liguilla.equipos_inferiores'] = 'nullable|integer|min:0';
                $rules['config_liguilla.formato_inferior'] = 'nullable|string';
            }
        }

        return $rules;
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
            'total_equipos' => 'total de equipos',
            'cantidad_grupos' => 'cantidad de grupos',
            'equipos_por_grupo' => 'equipos por grupo',
            'config_liguilla.equipos_superiores' => 'equipos en liguilla superior',
            'config_liguilla.equipos_inferiores' => 'equipos en liguilla inferior',
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

    public function siguientePaso()
    {
        // Validar según el paso actual
        if ($this->paso_actual === 1) {
            $this->validate([
                'nombre' => 'required|string|max:255',
                'formato' => 'required',
                'categoria_id' => 'required',
            ]);
        }

        $this->paso_actual++;
    }

    public function pasoAnterior()
    {
        $this->paso_actual--;
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            // Determinar valores y status inicial
            $isGrupos = $this->formato === 'grupos';
            $isEliminacion = in_array($this->formato, ['eliminacion_simple', 'eliminacion_doble']);
            $isTodosContraTodos = $this->formato === 'todos_contra_todos';

            $statusInicial = 'todos_contra_todos';
            if ($isGrupos) {
                $statusInicial = 'fase_de_grupos';
            } elseif ($isEliminacion) {
                $statusInicial = 'eliminacion_directa';
            }

            // Crear campeonato
            $campeonato = Campeonato::create([
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
            ]);

            // Guardar criterios de desempate
            if ($isGrupos || $isTodosContraTodos) {
                foreach ($this->criterios as $index => $criterio) {
                    Criterios_desempate::create([
                        'campeonato_id' => $campeonato->id,
                        'criterio' => $criterio,
                        'orden' => $index + 1,
                    ]);
                }
            }

            // Crear grupos si aplica
            if ($isGrupos) {
                $letras = range('A', 'Z');
                for ($i = 0; $i < $this->cantidad_grupos; $i++) {
                    Grupo::create([
                        'campeonato_id' => $campeonato->id,
                        'nombre' => 'Grupo ' . $letras[$i],
                        'cantidad_equipos' => $this->equipos_por_grupo,
                    ]);
                }
            }

            // NUEVO: Crear fases del campeonato
            $this->crearFases($campeonato);
        });

        FacadesLivewireAlert::title('Perfecto!!')
            ->text('Campeonato creado correctamente')
            ->success()
            ->toast()
            ->show();

        return redirect()->route('campeonato.index');
    }

    private function crearFases($campeonato)
    {
        $orden = 1;

        // Crear fase principal según el formato
        if ($campeonato->formato === 'todos_contra_todos') {
            $fasePrincipal = $campeonato->fases()->create([
                'nombre' => 'Fase Regular',
                'tipo_fase' => 'todos_contra_todos',
                'orden' => $orden,
                'reglas_clasificacion' => null,
                'estado' => 'activa',
            ]);

            $campeonato->update(['fase_actual_id' => $fasePrincipal->id]);

            // Si tiene liguilla configurada, crear las fases adicionales
            if ($this->tiene_liguilla) {
                $orden++;

                // Liguilla Superior
                $campeonato->fases()->create([
                    'nombre' => 'Liguilla Superior',
                    'tipo_fase' => $this->config_liguilla['formato_superior'],
                    'orden' => $orden,
                    'reglas_clasificacion' => [
                        'fase_padre_id' => $fasePrincipal->id,
                        'tipo_clasificacion' => 'mejores_equipos',
                        'cantidad_equipos' => $this->config_liguilla['equipos_superiores'],
                        'criterio' => 'posicion',
                    ],
                    'estado' => 'pendiente',
                ]);

                // Liguilla Inferior (si está configurada)
                if (isset($this->config_liguilla['equipos_inferiores']) && $this->config_liguilla['equipos_inferiores'] > 0) {
                    $orden++;
                    $campeonato->fases()->create([
                        'nombre' => 'Liguilla Inferior',
                        'tipo_fase' => $this->config_liguilla['formato_inferior'],
                        'orden' => $orden,
                        'reglas_clasificacion' => [
                            'fase_padre_id' => $fasePrincipal->id,
                            'tipo_clasificacion' => 'equipos_restantes',
                            'cantidad_equipos' => $this->config_liguilla['equipos_inferiores'],
                        ],
                        'estado' => 'pendiente',
                    ]);
                }
            }
        } elseif ($campeonato->formato === 'grupos') {
            $fasePrincipal = $campeonato->fases()->create([
                'nombre' => 'Fase de Grupos',
                'tipo_fase' => 'grupos',
                'orden' => $orden,
                'reglas_clasificacion' => null,
                'estado' => 'activa',
            ]);

            $campeonato->update(['fase_actual_id' => $fasePrincipal->id]);

            // Si tiene liguilla configurada
            if ($this->tiene_liguilla) {
                $orden++;

                // Calcular equipos que clasifican según el criterio
                $equiposClasifican = $this->calcularEquiposClasifican();

                $campeonato->fases()->create([
                    'nombre' => 'Fase Eliminatoria',
                    'tipo_fase' => $this->config_liguilla['formato_superior'],
                    'orden' => $orden,
                    'reglas_clasificacion' => [
                        'fase_padre_id' => $fasePrincipal->id,
                        'tipo_clasificacion' => 'clasificados_grupos',
                        'cantidad_equipos' => $equiposClasifican,
                        'criterio' => $this->config_liguilla['criterio_clasificacion'],
                        'cantidad_por_grupo' => $this->config_liguilla['cantidad_por_grupo'] ?? null,
                        'cantidad_terceros' => $this->config_liguilla['cantidad_terceros'] ?? null,
                    ],
                    'estado' => 'pendiente',
                ]);
            }
        }
    }

    private function calcularEquiposClasifican()
    {
        $criterio = $this->config_liguilla['criterio_clasificacion'];

        switch ($criterio) {
            case 'primero_cada_grupo':
                return intval($this->cantidad_grupos ?? 0);

            case 'mejores_por_grupo':
                $cantidadPorGrupo = intval($this->config_liguilla['cantidad_por_grupo'] ?? 2);
                return intval($this->cantidad_grupos ?? 0) * $cantidadPorGrupo;

            case 'mejores_terceros':
                $primeroSegundos = intval($this->cantidad_grupos ?? 0) * 2;
                $terceros = intval($this->config_liguilla['cantidad_terceros'] ?? 1);
                return $primeroSegundos + $terceros;

            default:
                return 16; // Valor por defecto
        }
    }
    public function render()
    {
        return view('livewire.campeonato.campeonato-crear2', [
            'categorias' => $this->categorias
        ]);
    }
}
