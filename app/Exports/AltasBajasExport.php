<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AltasBajasExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    ShouldAutoSize
{
    protected $tipo;
    protected $campeonato;
    protected $fechaDesde;
    protected $fechaHasta;

    public function __construct($tipo, $campeonato, $fechaDesde, $fechaHasta)
    {
        $this->tipo = $tipo;
        $this->campeonato = $campeonato;
        $this->fechaDesde = $fechaDesde;
        $this->fechaHasta = $fechaHasta;
    }

    /**
     * 🔹 DATOS CRUDOS (SIN FORMATO)
     */
    public function collection()
    {
        return DB::table('campeonato_jugador_equipo as cje')
            ->join('jugadors', 'cje.jugador_id', '=', 'jugadors.id')
            ->join('equipos', 'cje.equipo_id', '=', 'equipos.id')
            ->join('campeonatos', 'cje.campeonato_id', '=', 'campeonatos.id')

            ->select(
                'jugadors.apellido',
                'jugadors.nombre',
                'equipos.nombre as equipo',
                'campeonatos.nombre as campeonato',
                'cje.fecha_alta',
                'cje.fecha_baja'
            )

            ->when(
                $this->tipo === 'altas',
                fn($q) => $q->whereNotNull('cje.fecha_alta')
                    ->whereNull('cje.fecha_baja')
            )
            ->when(
                $this->tipo === 'bajas',
                fn($q) => $q->whereNotNull('cje.fecha_baja')
            )

            ->when(
                $this->campeonato,
                fn($q) => $q->where('cje.campeonato_id', $this->campeonato)
            )
            ->when(
                $this->fechaDesde,
                fn($q) => $q->whereDate(
                    $this->tipo === 'altas' ? 'cje.fecha_alta' : 'cje.fecha_baja',
                    '>=',
                    $this->fechaDesde
                )
            )
            ->when(
                $this->fechaHasta,
                fn($q) => $q->whereDate(
                    $this->tipo === 'altas' ? 'cje.fecha_alta' : 'cje.fecha_baja',
                    '<=',
                    $this->fechaHasta
                )
            )
            ->orderByDesc(
                $this->tipo === 'altas' ? 'cje.fecha_alta' : 'cje.fecha_baja'
            )
            ->get();
    }

    /**
     * ✅ ENCABEZADOS (SIEMPRE VISIBLES)
     */
    public function headings(): array
    {
        return [
            'APELLIDO',
            'NOMBRE',
            'EQUIPO',
            'CAMPEONATO',
            'FECHA ALTA',
            'FECHA BAJA',
        ];
    }

    /**
     * ✅ TODO EN MAYÚSCULAS (EXCEL NO LO CAMBIA)
     */
    public function map($row): array
    {
        return [
            mb_strtoupper($row->apellido ?? ''),
            mb_strtoupper($row->nombre ?? ''),
            mb_strtoupper($row->equipo ?? ''),
            mb_strtoupper($row->campeonato ?? ''),
            $row->fecha_alta ? date('d/m/Y', strtotime($row->fecha_alta)) : '',
            $row->fecha_baja ? date('d/m/Y', strtotime($row->fecha_baja)) : '',
        ];
    }

    /**
     * 🟦 ENCABEZADOS EN NEGRITA
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [ // fila 1 = encabezados
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
            ],
        ];
    }
}
