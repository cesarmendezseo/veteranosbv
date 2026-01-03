<?php

namespace App\Livewire\Config;

use App\Models\Campeonato;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ConfigCampeonato extends Component
{

    public Campeonato $campeonato;
    public $fases = [];

    public function mount($id)
    {
        $this->campeonato = Campeonato::with(['fases', 'equipos'])->findOrFail($id);

        if ($this->campeonato->fases->count() > 0) {
            $this->cargarFasesExistentes();
        } else {
            $this->inicializarConfiguracionPorDefecto();
        }
    }

    private function inicializarConfiguracionPorDefecto()
    {
        if ($this->campeonato->formato === 'todos_contra_todos') {
            $this->fases = [
                [
                    'nombre' => 'Fase Regular',
                    'tipo_fase' => 'todos_contra_todos',
                    'tiene_liguilla' => false,
                    'config_liguilla' => [
                        'habilitada' => false,
                        'equipos_superiores' => 16,
                        'equipos_inferiores' => 12,
                        'formato_superior' => 'eliminacion_simple',
                        'formato_inferior' => 'eliminacion_simple',
                    ]
                ]
            ];
        } elseif ($this->campeonato->formato === 'por_grupos') {
            $this->fases = [
                [
                    'nombre' => 'Fase de Grupos',
                    'tipo_fase' => 'por_grupos',
                    'tiene_liguilla' => false,
                    'config_liguilla' => [
                        'habilitada' => false,
                        'criterio_clasificacion' => 'mejores_2_por_grupo', // mejores_2_por_grupo, mejor_3ero, etc.
                        'equipos_clasifican' => 16,
                        'formato_liguilla' => 'eliminacion_simple',
                    ]
                ]
            ];
        }
    }

    private function cargarFasesExistentes()
    {
        foreach ($this->campeonato->fases as $fase) {
            $this->fases[] = [
                'id' => $fase->id,
                'nombre' => $fase->nombre,
                'tipo_fase' => $fase->tipo_fase,
                'tiene_liguilla' => false,
                'config_liguilla' => $fase->reglas_clasificacion ?? [],
            ];
        }
    }

    public function habilitarLiguilla($indiceFase)
    {
        $this->fases[$indiceFase]['tiene_liguilla'] = !$this->fases[$indiceFase]['tiene_liguilla'];

        if ($this->fases[$indiceFase]['tiene_liguilla']) {
            $this->fases[$indiceFase]['config_liguilla']['habilitada'] = true;
        }
    }

    public function guardarConfiguracion()
    {
        $this->validate([
            'fases.*.nombre' => 'required|string|max:255',
            'fases.*.tipo_fase' => 'required|string',
        ]);

        DB::transaction(function () {
            // Eliminar fases anteriores si existen
            $this->campeonato->fases()->delete();

            foreach ($this->fases as $orden => $datosFase) {
                $fase = $this->campeonato->fases()->create([
                    'nombre' => $datosFase['nombre'],
                    'tipo_fase' => $datosFase['tipo_fase'],
                    'orden' => $orden + 1,
                    'reglas_clasificacion' => null,
                    'estado' => $orden === 0 ? 'activa' : 'pendiente',
                ]);

                // Si es la primera fase, establecerla como actual
                if ($orden === 0) {
                    $this->campeonato->update(['fase_actual_id' => $fase->id]);
                }

                // Agregar todos los equipos a la primera fase
                if ($orden === 0) {
                    foreach ($this->campeonato->equipos as $equipo) {
                        $fase->equipos()->attach($equipo->id);
                    }
                }

                // Si tiene liguilla, crear las fases adicionales
                if ($datosFase['tiene_liguilla'] && $datosFase['config_liguilla']['habilitada']) {
                    $this->crearFasesLiguilla($fase, $datosFase['config_liguilla']);
                }
            }
        });

        session()->flash('mensaje', 'Configuración guardada correctamente');
        $this->dispatch('configuracion-guardada');
    }

    private function crearFasesLiguilla($faseAnterior, $config)
    {
        if ($this->campeonato->formato === 'todos_contra_todos' || $this->campeonato->formato === 'por_grupos') {
            // Liguilla Superior
            $this->campeonato->fases()->create([
                'nombre' => 'Liguilla Superior',
                'tipo_fase' => $config['formato_superior'] ?? $config['formato_liguilla'] ?? 'eliminacion_simple',
                'orden' => $faseAnterior->orden + 1,
                'reglas_clasificacion' => [
                    'fase_padre_id' => $faseAnterior->id,
                    'tipo_clasificacion' => 'mejores_equipos',
                    'cantidad_equipos' => $config['equipos_superiores'] ?? $config['equipos_clasifican'] ?? 16,
                    'criterio' => $config['criterio_clasificacion'] ?? 'posicion',
                ],
                'estado' => 'pendiente',
            ]);

            // Liguilla Inferior (solo si aplica)
            if (isset($config['equipos_inferiores']) && $config['equipos_inferiores'] > 0) {
                $this->campeonato->fases()->create([
                    'nombre' => 'Liguilla Inferior',
                    'tipo_fase' => $config['formato_inferior'] ?? 'eliminacion_simple',
                    'orden' => $faseAnterior->orden + 2,
                    'reglas_clasificacion' => [
                        'fase_padre_id' => $faseAnterior->id,
                        'tipo_clasificacion' => 'equipos_restantes',
                        'cantidad_equipos' => $config['equipos_inferiores'],
                    ],
                    'estado' => 'pendiente',
                ]);
            }
        }
    }
    public function render()
    {
        return view('livewire.config.config-campeonato');
    }
}
