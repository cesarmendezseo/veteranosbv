<?php

namespace App\Livewire\Campeonato;

use App\Models\Campeonato;
use App\Models\Categoria;
use App\Models\Criterios_desempate;
use App\Models\Grupo;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class CampeonatoCrear2 extends Component
{
    public $tipo_fixture = 'automatico'; // Por defecto automático

    public $nombre;
    public $formato = '';
    public $cantidad_grupos;
    public $equipos_por_grupo;
    public $total_equipos;
    public $categoria_id;
    public $categorias;

    // Puntos
    public $puntos_ganado = 3;
    public $puntos_empatado = 1;
    public $puntos_perdido = 0;
    public $puntos_tarjeta_amarilla = -5;
    public $puntos_doble_amarilla = -3;
    public $puntos_tarjeta_roja = -1;
    public $intergrupos_para_libres = false;

    // Criterios de desempate
    public $criterios = [
        'puntos',
        'diferencia_goles',
        'goles_favor',
        'fairplay',
    ];

    // Liguilla
    public $tiene_liguilla = false;
    public $config_liguilla = [
        'equipos_superiores' => 16,
        'equipos_inferiores' => 0,
        'formato_superior' => 'eliminacion_simple',
        'formato_inferior' => 'eliminacion_simple',
        'criterio_clasificacion' => '',
        'cantidad_por_grupo' => 2,
        'cantidad_terceros' => 0,
    ];

    // Wizard
    public $paso_actual = 1;

    public function mount()
    {
        $this->categorias = Categoria::all();
    }

    protected function rules()
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

        if ($this->formato === 'grupos') {
            $rules['cantidad_grupos'] = 'required|integer|min:1';
            $rules['equipos_por_grupo'] = 'required|integer|min:1';
        } else {
            $rules['total_equipos'] = 'required|integer|min:2';
        }

        if ($this->tiene_liguilla) {
            $rules['config_liguilla.equipos_superiores'] = 'required|integer|min:2';
            $rules['config_liguilla.formato_superior'] = 'required|string';
        }

        return $rules;
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            $campeonato = Campeonato::create([
                'nombre' => $this->nombre,
                'formato' => $this->formato,
                'categoria_id' => $this->categoria_id,
                'puntos_ganado' => $this->puntos_ganado,
                'puntos_empatado' => $this->puntos_empatado,
                'puntos_perdido' => $this->puntos_perdido,
                // Nuevos campos de fairplay
                'puntos_tarjeta_amarilla' => $this->puntos_tarjeta_amarilla,
                'puntos_doble_amarilla' => $this->puntos_doble_amarilla,
                'puntos_tarjeta_roja' => $this->puntos_tarjeta_roja,
                'cantidad_grupos' => $this->formato === 'grupos' ? $this->cantidad_grupos : 1,
                'cantidad_equipos_grupo' => $this->formato === 'grupos' ? $this->equipos_por_grupo : $this->total_equipos,
                'total_equipos' => $this->total_equipos ?? ($this->cantidad_grupos * $this->equipos_por_grupo),
                'status' => $this->formato,
                'formato' => $this->formato,

            ]);

            // Criterios de desempate (Prioridad)
            foreach ($this->criterios as $index => $criterio) {
                Criterios_desempate::create([
                    'campeonato_id' => $campeonato->id,
                    'criterio' => $criterio,
                    'orden' => $index + 1
                ]);
            }

            if ($this->formato === 'grupos') {
                for ($i = 1; $i <= $this->cantidad_grupos; $i++) {
                    $campeonato->grupos()->create([
                        'nombre' => "Grupo " . chr(64 + $i),
                        'cantidad_equipos' => $this->equipos_por_grupo,
                    ]);
                }
            }

            $this->crearFases($campeonato);
        });

        LivewireAlert::success()
            ->title('Perfecto')
            ->text('Campeonato creado correctamente')
            ->toast()
            ->show();

        return redirect()->route('campeonato.index');
    }


    private function crearFases($campeonato)
    {
        $orden = 1;

        // FASE PRINCIPAL
        $fasePrincipal = $campeonato->fases()->create([
            'nombre' => match ($campeonato->formato) {
                'grupos' => 'Fase de Grupos',
                'todos_contra_todos' => 'Fase Regular',
                default => 'Eliminación Directa',
            },
            'tipo_fase' => $campeonato->formato,
            'orden' => $orden,
            'reglas_clasificacion' => null,
            'estado' => 'activa',
        ]);

        $campeonato->update(['fase_actual_id' => $fasePrincipal->id]);

        // LIGUILLA
        if ($this->tiene_liguilla) {
            $orden++;

            $campeonato->fases()->create([
                'nombre' => 'Copa de Oro / Fase Superior',
                'tipo_fase' => $this->config_liguilla['formato_superior'],
                'orden' => $orden,
                'reglas_clasificacion' => [
                    'rango' => 'superior',
                    'cantidad' => $this->config_liguilla['equipos_superiores'],
                    'fase_padre_id' => $fasePrincipal->id,
                    'criterio' => $this->config_liguilla['criterio_clasificacion'],
                    'cantidad_por_grupo' => $this->config_liguilla['cantidad_por_grupo'],
                    'cantidad_terceros' => $this->config_liguilla['cantidad_terceros'],
                ],
                'estado' => 'pendiente',
            ]);

            // FASE O COPA DE PLATA (Inferior - Si aplica)
            if ($this->config_liguilla['equipos_inferiores'] > 0) {
                $campeonato->fases()->create([
                    'nombre' => 'Copa de Plata / Fase Inferior',
                    'tipo_fase' => $this->config_liguilla['formato_inferior'],
                    'orden' => $orden, // Misma jerarquía temporal que la de oro
                    'reglas_clasificacion' => [
                        'rango' => 'inferior',
                        'cantidad' => $this->config_liguilla['equipos_inferiores'],
                    ],
                    'estado' => 'pendiente',
                ]);
            }
        }
    }

    public function render()
    {
        return view('livewire.campeonato.campeonato-crear2', [
            'categorias' => $this->categorias,
        ]);
    }
}
