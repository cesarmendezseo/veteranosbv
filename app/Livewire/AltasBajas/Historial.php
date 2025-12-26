<?php

namespace App\Livewire\AltasBajas;

use App\Exports\AltasBajasExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Historial extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // 🔎 FILTROS
    public $tipo = 'altas';
    public $campeonatoFiltro = null;
    public $fechaDesde = null;
    public $fechaHasta = null;

    public $aplicarFiltros = false;

    // Para selects
    public $campeonatos = [];

    public function mount()
    {
        $this->campeonatos = DB::table('campeonatos')
            ->orderBy('nombre')
            ->get();
    }

    // 🟢 BOTÓN FILTRAR
    public function filtrar()
    {

        $this->aplicarFiltros = true;
        $this->resetPage();
    }

    // 🔴 BOTÓN LIMPIAR
    public function limpiarFiltros()
    {
        $this->reset([
            'tipo',
            'campeonatoFiltro',
            'fechaDesde',
            'fechaHasta',
            'aplicarFiltros',
        ]);

        $this->tipo = 'altas';
        $this->resetPage();
    }

    public function exportarExcel()
    {
        return Excel::download(
            new AltasBajasExport(
                $this->tipo,
                $this->campeonatoFiltro,
                $this->fechaDesde,
                $this->fechaHasta
            ),
            'Altas_Bajas.xlsx'
        );
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

            ->when($this->aplicarFiltros, function ($q) {

                // 🔥 ALTAS / BAJAS
                if ($this->tipo === 'altas') {
                    $q->whereNotNull('cje.fecha_alta');
                } else {
                    $q->whereNotNull('cje.fecha_baja');
                }

                // 🎯 CAMPEONATO
                if ($this->campeonatoFiltro) {
                    $q->where('cje.campeonato_id', $this->campeonatoFiltro);
                }

                // 📅 FECHAS
                if ($this->fechaDesde && $this->fechaHasta) {

                    $columnaFecha = $this->tipo === 'altas'
                        ? 'cje.fecha_alta'
                        : 'cje.fecha_baja';

                    $q->whereBetween(
                        DB::raw("DATE($columnaFecha)"),
                        [
                            Carbon::parse($this->fechaDesde)->toDateString(),
                            Carbon::parse($this->fechaHasta)->toDateString(),
                        ]
                    );
                }
            })

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
