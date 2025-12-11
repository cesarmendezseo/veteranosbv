<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class EliminatoriaExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    protected $encuentros;
    protected $torneoNombre;
    protected $fechaEncuentro;
    protected $fecha;
    protected $fechaPrimerEncuentro;
    protected $fase;

    public function __construct($encuentros, $torneoNombre, $fechaPrimerEncuentro, $fase)
    {
        $this->encuentros = $encuentros;
        $this->torneoNombre = $torneoNombre;
        $this->fase = $fase;

        $this->fechaPrimerEncuentro = $fechaPrimerEncuentro; //fecha jornada
    }


    public function collection()
    {

        $data = new Collection();

        // Agregar el nombre del torneo
        $data->push([strtoupper($this->torneoNombre)]);

        // Agregar la "Fecha N°" y la fecha real del encuentro en la misma fila
        //$fechaDate = is_numeric($this->fechaEncuentro) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($this->fechaEncuentro)->format('d-m-Y') : (\is_string($this->fechaEncuentro) ? \Carbon\Carbon::parse($this->fechaEncuentro)->format('d-m-Y') : '');

        $fechaFormateada = '';
        if ($this->fechaPrimerEncuentro) {
            $fechaFormateada = \Carbon\Carbon::parse($this->fechaPrimerEncuentro)->format('d/m/Y');
        }
        $data->push(['Fase: ' . strtoupper($this->fase) . ' ' . '- Fecha: ' . $fechaFormateada]);



        $canchas = [];
        $currentCancha = null;
        $encuentrosPorCancha = [];

        foreach ($this->encuentros as $row) {
            if (is_array($row) && count($row) === 1 && trim($row[0]) === $this->torneoNombre) {
                continue;
            }
            if (count(array_filter($row, function ($value) {
                return $value !== null && $value !== '';
            })) === 1) {
                $currentCancha = strtolower(trim(reset($row)));
                $canchas[] = $currentCancha;
                $encuentrosPorCancha[$currentCancha] = [];
            } elseif ($currentCancha !== null) {
                $encuentrosPorCancha[$currentCancha][] = $row;
            }
        }

        $canchasReversed = array_reverse($canchas);

        foreach ($canchasReversed as $canchaNombre) {
            $data->push([strtoupper($canchaNombre)]);
            $data->push(['PENALES']);

            // Convertir nombres de equipos y cualquier texto a mayúsculas
            $mayusEncuentros = array_map(function ($fila) {
                return array_map(function ($valor) {
                    return is_string($valor) ? strtoupper($valor) : $valor;
                }, $fila);
            }, $encuentrosPorCancha[$canchaNombre]);

            $data = $data->concat($mayusEncuentros);
        }

        return $data;
    }

    public function headings(): array
    {
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        $styles = [];
        $coloresPorCancha = [
            'coliseo' => ['start' => 'FFC000', 'end' => 'FFE699', 'textColor' => '000000'],
            'complejo' => ['start' => '8FAADC', 'end' => '203764', 'textColor' => 'FFFFFF'],
            'maradona' => ['start' => 'FFC000', 'end' => 'FFE699', 'textColor' => '000000'],
            'morales' => ['start' => '8FAADC', 'end' => '203764', 'textColor' => 'FFFFFF'],
            'pepe sand' => ['start' => 'FFC000', 'end' => 'FFE699', 'textColor' => '000000'],
        ];

        $filtroValores = fn($value) => $value !== null && $value !== '';
        $canchaActual = null;
        $alignmentCenter = ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]];
        $alignmentLeft = ['alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER]];

        foreach ($this->collection() as $index => $row) {
            $rowNumber = $index + 1;


            if (count(array_filter($row, $filtroValores)) === 1) {
                $canchaActual = strtolower(trim(reset($row)));
                $colorConfig = $coloresPorCancha[$canchaActual] ?? ['start' => 'ADD8E6', 'end' => '4682B4', 'textColor' => '000000'];

                $styles[$rowNumber] = [
                    'font' => ['bold' => true, 'color' => ['rgb' => $colorConfig['textColor']]],
                    'fill' => [
                        'fillType' => Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startColor' => ['rgb' => $colorConfig['start']],
                        'endColor' => ['rgb' => $colorConfig['end']],
                    ],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                ];
            } elseif (count(array_filter($row, $filtroValores)) > 1) {
                if ($canchaActual !== null) {
                    $colorConfig = $coloresPorCancha[$canchaActual] ?? ['start' => 'FFFFFF', 'end' => 'DDDDDD', 'textColor' => '000000'];

                    $styles[$rowNumber] = [
                        'font' => ['bold' => true, 'color' => ['rgb' => $colorConfig['textColor']]],
                        'fill' => [
                            'fillType' => Fill::FILL_GRADIENT_LINEAR,
                            'rotation' => 90,
                            'startColor' => ['rgb' => $colorConfig['start']],
                            'endColor' => ['rgb' => $colorConfig['end']],
                        ],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                    ];
                }
            }
        }

        return $styles;
    }

    public static function afterSheet(AfterSheet $event)
    {
        $sheet = $event->sheet;

        // ===== Fila 1: Nombre del torneo =====
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 14,
            ],
            'fill' => [
                'fillType' => Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => ['rgb' => 'F54927'], // gris oscuro
                'endColor' => ['rgb' => 'F56A4E'],   // gris medio
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // ===== Fila 2: Fecha y fase =====
        $sheet->mergeCells('A2:F2');
        $sheet->getStyle('A2:F2')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'ffffff'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => ['rgb' => 'F56A4E'], // gris claro
                'endColor' => ['rgb' => 'E3A15F'],   // blanco
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);



        // ===== Alinear y aplicar bordes a todo =====
        $nonEmptyRowCount = 0;
        foreach ($sheet->getDelegate()->toArray() as $row) {
            if (count(array_filter($row, fn($value) => $value !== null && $value !== '')) > 0) {
                $nonEmptyRowCount++;
            }
        }

        $lastRow = $nonEmptyRowCount;
        $lastColumn = $sheet->getHighestColumn();

        $sheet->getStyle("A1:{$lastColumn}{$lastRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getStyle("A1:{$lastColumn}{$lastRow}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN)
            ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK));

        // ===== Fusionar filas de cancha =====
        $rowIndex = 1;
        foreach ($sheet->getDelegate()->toArray() as $row) {
            if (count(array_filter($row, fn($value) => $value !== null && $value !== '')) === 1) {
                $sheet->mergeCells("A{$rowIndex}:F{$rowIndex}");
            }
            $rowIndex++;
        }

        // ===== Estilizar las filas que contienen solo la palabra "PENALES" =====
        $lastRow = $sheet->getHighestRow();

        for ($rowIndex = 3; $rowIndex <= $lastRow; $rowIndex++) {
            // Obtener el valor de la celda A (la primera columna) para la fila actual
            $cellValue = $sheet->getCell('A' . $rowIndex)->getValue();

            // Verificar si la celda A contiene exactamente 'PENALES' y si es una fila de celda fusionada/única
            if (trim(strtoupper($cellValue)) === 'PENALES') {

                // 1. Fusionar celdas (si no están ya fusionadas por otro proceso)
                // Esto asume que la fusión es de A a F, como en el resto del documento
                $sheet->mergeCells("A{$rowIndex}:F{$rowIndex}");

                // 2. Aplicar el estilo deseado para la fila "PENALES"
                $sheet->getStyle("A{$rowIndex}:F{$rowIndex}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'], // Texto en blanco
                        'size' => 11,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '203764'], // ⬅️ Azul Oscuro para la fila de Penales
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
            }
        }

        // ===== Aplicar color a columnas C y D de todos los encuentros que son los resultados de los penales =====
        $rowIndex = 3; // Empieza desde la fila 3 (encabezados ya estilizados)

        while ($rowIndex <= $lastRow) {
            $rowValues = $sheet->getDelegate()->rangeToArray("A{$rowIndex}:F{$rowIndex}")[0];

            // Detectar si es una fila de encuentro (más de una celda con contenido)
            $nonEmpty = array_filter($rowValues, fn($value) => $value !== null && $value !== '');
            if (count($nonEmpty) > 1) {
                $sheet->getStyle("C{$rowIndex}:D{$rowIndex}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'ffffff'],
                        'size' => 12,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '203764'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
            }
            $rowIndex++;
        }
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => [self::class, 'afterSheet'],
        ];
    }
}
