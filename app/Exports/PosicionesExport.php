<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PosicionesExport implements FromCollection, WithEvents, WithTitle
{
    protected $posiciones;
    protected $nombreCampeonato;
    protected $tituloHoja;

    public function __construct($posiciones, $nombreCampeonato, $tituloHoja)
    {
        $this->posiciones = $posiciones;
        $this->nombreCampeonato = $nombreCampeonato;
        $this->tituloHoja = $tituloHoja;
    }

    public function title(): string
    {
        return $this->tituloHoja;
    }

    public function collection()
    {
        return collect($this->posiciones)->values()->map(function ($p, $i) {

            return [
                'pos'  => $i + 1,
                'eq'   => $p[strtoupper('equipo')],
                'pj'   => (int)$p['jugados'],
                'g'    => (int)$p['ganados'],
                'e'    => (int)$p['empatados'],
                'p'    => (int)$p['perdidos'],
                'gf'   => (int)$p['goles_favor'],
                'gc'   => (int)$p['goles_contra'],
                'dg'   => (int)$p['diferencia_goles'],
                'fp'   => (int)$p['fair_play'],
                'pts'  => (int)$p['puntos'],
            ];
        });
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                /** 1) Título */
                $sheet->mergeCells('A1:K1');
                $sheet->setCellValue('A1', $this->nombreCampeonato);
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startColor' => ['rgb' => '8FAADC'],
                        'endColor'   => ['rgb' => '5B9BD5'],
                    ]
                ]);

                /** 2) Encabezados */
                $headers = [
                    'Pos.',
                    'Equipo',
                    'PJ',
                    'G',
                    'E',
                    'P',
                    'GF',
                    'GC',
                    'DG',
                    'FPlay',
                    'PTS'
                ];
                $sheet->fromArray($headers, null, 'A2');

                /** 3) Inserción de datos celda-por-celda con tipos correctos */
                $data = $this->collection()->toArray();
                $row = 3;

                foreach ($data as $fila) {

                    $col = 'A';

                    foreach ($fila as $value) {

                        if (is_numeric($value)) {
                            $sheet->setCellValueExplicit("$col$row", $value, DataType::TYPE_NUMERIC);
                        } else {
                            $sheet->setCellValueExplicit("$col$row", $value, DataType::TYPE_STRING);
                        }

                        $col++;
                    }

                    $row++;
                }

                /** 4) Estilos */
                $this->applyStyles($sheet);
            }
        ];
    }

    private function applyStyles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();

        /** Encabezado */
        $sheet->getStyle('A2:K2')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F81BD'],
            ],
        ]);

        /** Datos */
        $sheet->getStyle("A3:K$highestRow")->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        /** Columna Equipo */
        $sheet->getStyle("B3:B$highestRow")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);

        /** Aplicación de degradados */
        $this->setColumnStyle($sheet, 'B', 'FFB0C4DE', 'FFE0FFFF', $highestRow);
        $this->setColumnStyle($sheet, 'C', 'FFFF99', 'FFFF66', $highestRow);
        $this->setColumnStyle($sheet, 'D', 'D0CECE', 'AEAAAA', $highestRow);
        $this->setColumnStyle($sheet, 'E', 'FFCC99', 'FF9966', $highestRow);
        $this->setColumnStyle($sheet, 'F', '3CB6EC', '6EBAEE', $highestRow);
        $this->setColumnStyle($sheet, 'G', 'C6E0B4', '92D050', $highestRow);
        $this->setColumnStyle($sheet, 'H', 'D0CECE', 'AEAAAA', $highestRow);
        $this->setColumnStyle($sheet, 'I', 'FFFF99', 'FFFF66', $highestRow);
        $this->setColumnStyle($sheet, 'J', '92D050', '99FF66', $highestRow);

        /** Columna PTS estilo especial */
        $sheet->getStyle("K3:K$highestRow")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '171717']],
        ]);

        /** Bordes */
        $sheet->getStyle("A1:K$highestRow")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        /** Auto-size */
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    private function setColumnStyle($sheet, $column, $startColor, $endColor, $highestRow)
    {
        $sheet->getStyle("$column" . "3:$column$highestRow")->getFill()->setFillType(Fill::FILL_GRADIENT_LINEAR);
        $sheet->getStyle("$column" . "3:$column$highestRow")->getFill()->setRotation(90);
        $sheet->getStyle("$column" . "3:$column$highestRow")->getFill()->setStartColor(new Color($startColor));
        $sheet->getStyle("$column" . "3:$column$highestRow")->getFill()->setEndColor(new Color($endColor));
    }
}
