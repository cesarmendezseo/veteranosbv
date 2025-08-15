<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
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
    protected $highestRow;


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

        return collect($this->posiciones)->values()->map(function ($posicion, $index) {
            return [
                $index + 1, // Posicion
                $posicion['equipo'],
                $posicion['jugados'],
                $posicion['ganados'],
                $posicion['empatados'],
                $posicion['perdidos'] . '',
                $posicion['goles_favor'],
                $posicion['goles_contra'],
                $posicion['diferencia_goles'],
                $posicion['fair_play'],
                $posicion['puntos'],

            ];
        });
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // 1. Configurar el título en A1 (combinado)
                $sheet->mergeCells('A1:K1');
                $sheet->setCellValue('A1', $this->nombreCampeonato);
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    //aplicamos degradado a la fila del titulo del campeonato
                    'fill' => [
                        'fillType' => Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startColor' => ['rgb' => '8FAADC'],
                        'endColor' => ['rgb' => '5B9BD5'],
                    ],

                ]);
                // EXCEPCIÓN: Columna B (Equipo) alineada a la izquierda
                $sheet->getRowDimension(1)->setRowHeight(30);

                // 2. Mover los datos a partir de la fila 3
                $data = $this->collection()->toArray();
                $sheet->fromArray($data, null, 'A3');

                // 3. Configurar encabezados en fila 2
                $headings = [
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
                    'PTS.'
                ];
                $sheet->fromArray($headings, null, 'A2');

                // 4. Aplicar todos los estilos
                $this->applyStyles($sheet);
            },
        ];
    }


    private function applyStyles(Worksheet $sheet)
    {
        // Primero obtenemos la última fila con datos
        $highestRow = $sheet->getHighestRow();

        // Estilo para encabezados (fila 2)
        $sheet->getStyle('A2:K2')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F81BD'],
            ],
        ]);

        // Estilo base para datos (filas 3 en adelante) - centrado por defecto
        $sheet->getStyle('A3:K' . $highestRow)->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Ajuste especial para columna B (Equipo) - alineación izquierda
        $sheet->getStyle('B3:B' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // Estilos específicos por columna (gradientes de color)
        $this->setColumnStyle($sheet, 'B', 'FFB0C4DE', 'FFE0FFFF', $highestRow);
        $this->setColumnStyle($sheet, 'C', 'FFFF99', 'FFFF66', $highestRow);
        $this->setColumnStyle($sheet, 'D', 'D0CECE', 'AEAAAA', $highestRow);
        $this->setColumnStyle($sheet, 'E', 'FFCC99', 'FF9966', $highestRow);
        $this->setColumnStyle($sheet, 'F', '3CB6EC', '6EBAEE', $highestRow);
        $this->setColumnStyle($sheet, 'G', 'C6E0B4', '92D050', $highestRow);
        $this->setColumnStyle($sheet, 'H', 'D0CECE', 'AEAAAA', $highestRow);
        $this->setColumnStyle($sheet, 'I', 'FFFF99', 'FFFF66', $highestRow);
        $this->setColumnStyle($sheet, 'J', '92D050', '99FF66', $highestRow);

        // Columna K especial
        $sheet->getStyle('K3:K' . $highestRow)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '171717'],
            ],
        ]);

        // Bordes para toda la tabla
        $sheet->getStyle('A1:K' . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        // Autoajustar columnas
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    private function setColumnStyle($sheet, $column, $startColor, $endColor, $highestRow)
    {
        $sheet->getStyle("{$column}3:{$column}{$highestRow}")->getFill()->setFillType(Fill::FILL_GRADIENT_LINEAR);
        $sheet->getStyle("{$column}3:{$column}{$highestRow}")->getFill()->setRotation(90);
        $sheet->getStyle("{$column}3:{$column}{$highestRow}")->getFill()->setStartColor(new Color($startColor));
        $sheet->getStyle("{$column}3:{$column}{$highestRow}")->getFill()->setEndColor(new Color($endColor));
    }
}
