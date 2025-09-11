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

class EncuentrosExport {}
/* {
    protected $encuentros;
    protected $torneoNombre;
    protected $fechaEncuentro;
    protected $fecha;
    protected $fechaPrimerEncuentro;

    public function __construct($encuentros, $torneoNombre, $fecha, $fechaPrimerEncuentro)
    {
        $this->encuentros = $encuentros;
        $this->torneoNombre = $torneoNombre;

        $this->fecha = $fecha; //date
        $this->fechaPrimerEncuentro = $fechaPrimerEncuentro; //fecha jornada
    }


    public function collection()
    {

        $data = new Collection();

        // Agregar el nombre del torneo
        $data->push([$this->torneoNombre]);

        // Agregar la "Fecha N°" y la fecha real del encuentro en la misma fila
        //$fechaDate = is_numeric($this->fechaEncuentro) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($this->fechaEncuentro)->format('d-m-Y') : (\is_string($this->fechaEncuentro) ? \Carbon\Carbon::parse($this->fechaEncuentro)->format('d-m-Y') : '');

        $fechaFormateada = '';
        if ($this->fecha) {
            $fechaFormateada = \Carbon\Carbon::parse($this->fecha)->format('d/m/Y');
        }
        $data->push(['Fecha N°: ' . $this->fechaPrimerEncuentro . '- Día: ' . $fechaFormateada]);



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

        foreach ($canchasReversed as $canchaNombre) { // Usamos $canchaNombre aquí
            $data->push([strtoupper($canchaNombre)]);
            $data = $data->concat($encuentrosPorCancha[$canchaNombre]); // Usamos $canchaNombre aquí
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
        // Fusionar celdas para centrar el título
        $sheet->mergeCells('A1:D1');
        // Aplicar estilos después de la fusión
        $sheet->getStyle('A1:D1')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => ['rgb' => 'FFFFFF'],
                'endColor' => ['rgb' => '76C2F6'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Fusionar celdas para centrar la fecha
        $sheet->mergeCells('A2:D2');
        // Aplicar estilo de degradado a la fecha
        $sheet->getStyle('A2:D2')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => ['rgb' => 'FFFFFF'],
                'endColor' => ['rgb' => '76C2F6'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        $rowIndex = 3; // Los nombres de cancha empiezan después del título y la fecha

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
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
            ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK));

        $rowIndex = 1;
        foreach ($sheet->getDelegate()->toArray() as $row) {
            if (count(array_filter($row, fn($value) => $value !== null && $value !== '')) === 1) {
                $sheet->mergeCells("A{$rowIndex}:D{$rowIndex}"); // Ajustamos el merge a 4 columnas
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
} */
