<?php

namespace App\Livewire\AltasBajas;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Historial extends Component
{

    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // 🔎 FILTROS
    public $tipo = 'altas'; // altas | bajas
    public $campeonatoFiltro = null;
    public $fechaDesde = null;
    public $fechaHasta = null;

    // Para selects
    public $campeonatos = [];

    public function mount()
    {
        // Cargar campeonatos para el filtro
        $this->campeonatos = DB::table('campeonatos')
            ->orderBy('nombre')
            ->get();
    }

    // Resetear paginación al cambiar filtros
    public function updated($property)
    {
        if (in_array($property, [
            'tipo',
            'campeonatoFiltro',
            'fechaDesde',
            'fechaHasta'
        ])) {
            $this->resetPage();
        }
    }


    public function render()
    {
        $movimientos = DB::table('campeonato_jugador_equipo as cje')
            ->join('jugadors', 'cje.jugador_id', '=', 'jugadors.id')
            ->join('equipos', 'cje.equipo_id', '=', 'equipos.id')
            ->join('campeonatos', 'cje.campeonato_id', '=', 'campeonatos.id')

            ->select([
                'jugadors.nombre',
                'jugadors.apellido',
                'equipos.nombre as equipo',
                'campeonatos.nombre as campeonato',
                'cje.fecha_alta',
                'cje.fecha_baja',
            ])

            // 🔥 ALTAS o BAJAS
            ->when(
                $this->tipo === 'altas',
                fn($q) => $q->whereNotNull('cje.fecha_alta')
                    ->whereNull('cje.fecha_baja')
            )
            ->when(
                $this->tipo === 'bajas',
                fn($q) => $q->whereNotNull('cje.fecha_baja')
            )

            // 🎯 FILTROS
            ->when(
                $this->campeonatoFiltro,
                fn($q) =>
                $q->where('cje.campeonato_id', $this->campeonatoFiltro)
            )
            ->when(
                $this->fechaDesde,
                fn($q) =>
                $q->whereDate(
                    $this->tipo === 'altas'
                        ? 'cje.fecha_alta'
                        : 'cje.fecha_baja',
                    '>=',
                    $this->fechaDesde
                )
            )
            ->when(
                $this->fechaHasta,
                fn($q) =>
                $q->whereDate(
                    $this->tipo === 'altas'
                        ? 'cje.fecha_alta'
                        : 'cje.fecha_baja',
                    '<=',
                    $this->fechaHasta
                )
            )

            ->orderByDesc(
                $this->tipo === 'altas'
                    ? 'cje.fecha_alta'
                    : 'cje.fecha_baja'
            )

            ->paginate(15);

        return view('livewire.altas-bajas.historial', [
            'movimientos' => $movimientos,
            'campeonatos' => $this->campeonatos,
        ]);
    }
}
