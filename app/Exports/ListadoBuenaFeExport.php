<?php

namespace App\Exports;

use App\Models\CampeonatoJugadorEquipo;
use App\Models\Jugador;
use App\Models\Sanciones;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooter;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;

class ListadoBuenaFeExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithEvents,
    ShouldAutoSize
{
    protected $equipoId;
    protected $equipoNombre;
    private int $rowNumber = 1;
    private $campeonato_id;
    private $torneoNombre;
    private $fecha;

    public function __construct($equipoId, $torneoNombre, $campeonatoId, $fecha)
    {
        $this->equipoId = $equipoId;
        $this->equipoNombre = \App\Models\Equipo::find($equipoId)->nombre ?? 'Equipo';
        $this->torneoNombre = $torneoNombre ?? 'Torneo Oficial';
        $this->campeonato_id = $campeonatoId;
        $this->fecha = $fecha;
    }

    public function collection()
    {
        return CampeonatoJugadorEquipo::with(['jugador'])
            ->where('campeonato_id', $this->campeonato_id)
            ->where('equipo_id', $this->equipoId)
            ->whereNull('fecha_baja')
            ->get()
            ->sortBy(fn($registro) => $registro->jugador->apellido)
            ->values();
    }

    public function headings(): array
    {
        return [
            '#',
            'N°',
            'DNI',
            'Apellido',
            'Nombre',
            'Firmas',
            'Gol',
            'Tarj.',
            'Sanción'
        ];
    }

    public function map($registro): array
    {
        $jugador = $registro->jugador;

        $sancionActiva = Sanciones::where('jugador_id', $jugador->id)
            ->where('cumplida', false)
            ->first();

        $leyenda = '';
        $firma = '';

        if ($sancionActiva) {
            // Marcar como SUSPENDIDO en la columna de firmas
            $firma = 'SUSPENDIDO';

            // 🟥 SANCIÓN POR FECHAS
            if ($sancionActiva->fecha_inicio && $sancionActiva->fecha_fin) {
                $leyenda = $this->calcularPeriodoSancion(
                    $sancionActiva->fecha_inicio,
                    $sancionActiva->fecha_fin
                );
            } else {
                // 🟨 SANCIÓN POR PARTIDOS
                $motivo = strtolower(trim($sancionActiva->motivo ?? ''));

                if (str_contains($motivo, 'amarilla')) {
                    $leyenda = 'PAGA';
                } else {
                    $pendientes = $sancionActiva->partidos_sancionados - $sancionActiva->partidos_cumplidos;
                    $leyenda = $pendientes . ' FECHAS';
                }
            }
        }

        return [
            $this->rowNumber++,
            '',
            strtoupper($jugador->documento ?? ''),
            strtoupper($jugador->apellido ?? ''),
            strtoupper($jugador->nombre ?? ''),
            strtoupper($firma),      // 👈 FIRMA (SUSPENDIDO si tiene sanción)
            '',
            '',
            strtoupper($leyenda),    // 👈 SANCIÓN
        ];
    }

    /**
     * Calcular el período de sanción basado en fechas
     */
    private function calcularPeriodoSancion($fechaInicio, $fechaFin)
    {
        if (!$fechaInicio || !$fechaFin || $fechaInicio === '' || $fechaFin === '') {
            return '';
        }

        try {
            $inicio = \Carbon\Carbon::parse($fechaInicio);
            $fin = \Carbon\Carbon::parse($fechaFin);

            $diffAnios = $inicio->diffInYears($fin);
            $diffMeses = $inicio->copy()->addYears($diffAnios)->diffInMonths($fin);

            $resultado = [];

            if ($diffAnios > 0) {
                $resultado[] = $diffAnios . ($diffAnios == 1 ? ' año' : ' años');
            }

            if ($diffMeses > 0) {
                $resultado[] = $diffMeses . ($diffMeses == 1 ? ' mes' : ' meses');
            }

            return !empty($resultado) ? implode(' y ', $resultado) : 'Menos de 1 mes';
        } catch (\Exception $e) {
            return '';
        }
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LEGAL);

                $margins = $sheet->getPageMargins();
                $margins->setBottom(0.5);
                $margins->setLeft(0.5);
                $margins->setRight(0.5);
                $margins->setTop(1);

                $sheet->getHeaderFooter()
                    ->setOddHeader('&L&G&R&"Arial,Bold"&12ASOCIACIÓN CIVIL' . "\n" . 'DE FÚTBOL DE VETERANOS' . "\n" . 'BELLA VISTA - CORRIENTES');

                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooterDrawing();
                $drawing->setName('Logo');
                $drawing->setPath(public_path('images/logo.jpeg'));
                $drawing->setHeight(36);
                $sheet->getHeaderFooter()->addImage($drawing, HeaderFooter::IMAGE_HEADER_LEFT);

                $sheet->insertNewRowBefore(1, 4);

                $sheet->mergeCells('A1:I1');
                $sheet->setCellValue('A1', strtoupper($this->torneoNombre));

                $sheet->mergeCells('B2:C2');
                $sheet->setCellValue('B2', 'FECHA: ' .  $this->fecha);

                $sheet->mergeCells('B3:C3');
                $sheet->setCellValue('B3', 'CANCHA: ');

                $sheet->mergeCells('B4:D4');
                $sheet->setCellValue('B4',  strtoupper($this->equipoNombre));

                $sheet->mergeCells('F2:H2');
                $sheet->setCellValue('F2', 'EL DÍA:__/__/___/ ');
                $sheet->mergeCells('F4:H4');
                $sheet->setCellValue('F4',  'CONDICION:_________');
                $sheet->mergeCells('F3:H3');
                $sheet->setCellValue('F3',  'GOLES:_____');

                $sheet->getStyle('A1:H1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => '2C5282']
                    ],
                    'alignment' => [
                        'horizontal' => 'center'
                    ]
                ]);

                $sheet->getStyle('B2:C2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => 'FF0000']
                    ],
                    'alignment' => [
                        'horizontal' => 'center'
                    ]
                ]);

                $sheet->mergeCells('B4:D4');
                $sheet->getStyle('B4')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startColor' => [
                            'argb' => 'E26B0A',
                        ],
                        'endColor' => [
                            'argb' => '974706',
                        ],
                    ],
                ]);

                $sheet->getStyle('A2:H4')->applyFromArray([
                    'font' => [
                        'size' => 14
                    ],
                    'alignment' => [
                        'horizontal' => 'left'
                    ]
                ]);

                $sheet->fromArray([$this->headings()], null, 'A5');

                $sheet->getStyle('A5:I5')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => '3490DC']
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'thin',
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                $endRow = 35;
                $sheet->getStyle('A5:I' . $endRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000']
                        ]
                    ]
                ]);

                $startRowBloque2 = 36;

                $sheet->mergeCells("A{$startRowBloque2}:I{$startRowBloque2}");
                $sheet->setCellValue("A{$startRowBloque2}", 'CUERPO TÉCNICO Y AUTORIDADES');
                $sheet->getStyle("A{$startRowBloque2}")->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => 'F0F0F0'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000']
                        ]
                    ]
                ]);

                $subtitulosRow = $startRowBloque2 + 1;
                $sheet->setCellValue("A{$subtitulosRow}", 'Cargo');
                $sheet->mergeCells("A{$subtitulosRow}:C{$subtitulosRow}");

                $sheet->setCellValue("D{$subtitulosRow}", 'DNI');

                $sheet->setCellValue("E{$subtitulosRow}", 'Apellido y Nombre');
                $sheet->mergeCells("E{$subtitulosRow}:F{$subtitulosRow}");

                $sheet->setCellValue("G{$subtitulosRow}", 'Firma');
                $sheet->mergeCells("G{$subtitulosRow}:H{$subtitulosRow}");
                $sheet->setCellValue("I{$subtitulosRow}", 'Observación');

                $sheet->getStyle("A{$subtitulosRow}:I{$subtitulosRow}")->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000']
                        ]
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => '3490DC']
                    ],
                ]);

                $datosCuerpoTecnico = [
                    'Capitán' => '',
                    'Técnico' => '',
                    'Ayudante 1' => '',
                    'Ayudante 2' => '',
                ];

                $rowCuerpoTecnico = $subtitulosRow + 1;
                foreach ($datosCuerpoTecnico as $cargo => $valor) {
                    $sheet->setCellValue("A{$rowCuerpoTecnico}", $cargo);
                    $sheet->mergeCells("A{$rowCuerpoTecnico}:C{$rowCuerpoTecnico}");
                    $sheet->mergeCells("E{$rowCuerpoTecnico}:F{$rowCuerpoTecnico}");
                    $sheet->mergeCells("G{$rowCuerpoTecnico}:H{$rowCuerpoTecnico}");

                    $sheet->getStyle("A{$rowCuerpoTecnico}:I{$rowCuerpoTecnico}")->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => '000000']
                            ]
                        ]
                    ]);
                    $rowCuerpoTecnico++;
                }

                $sheet->getStyle('C5:C' . $sheet->getHighestRow())->getAlignment()->setHorizontal('center');

                // Pintar jugadores sancionados y la palabra SUSPENDIDO en rojo
                $jugadores = $this->collection();
                $rowOffset = 5;
                $alturaFilaSancion = 20;
                $alturaFilaNormal = 20;

                foreach ($jugadores as $index => $jug) {
                    $row = $rowOffset + $index + 1;
                    $sheet->getRowDimension($row)->setRowHeight($alturaFilaNormal);

                    // Buscar sanción activa
                    $sancion = Sanciones::where('jugador_id', $jug->jugador->id)
                        ->where('cumplida', false)
                        ->first();

                    if ($sancion) {
                        // Pintar toda la fila en rojo
                        $sheet->getStyle("A{$row}:I{$row}")->applyFromArray([
                            'font' => [
                                'color' => ['rgb' => 'FF0000'],
                                'bold' => true,
                            ]
                        ]);

                        // Pintar específicamente la celda de FIRMA (columna F) en rojo con fondo
                        $sheet->getStyle("F{$row}")->applyFromArray([
                            'font' => [
                                'color' => ['rgb' => 'FFFFFF'],
                                'bold' => true,
                            ],
                            'fill' => [
                                'fillType' => 'solid',
                                'startColor' => ['rgb' => 'FF0000']
                            ],
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_CENTER,
                            ]
                        ]);

                        $sheet->getRowDimension($row)->setRowHeight($alturaFilaSancion);
                    }
                }

                $drawing = new Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Descripción del logo');
                $drawing->setPath(public_path('images/cambios.png'));
                $drawing->setHeight(185);
                $drawing->setCoordinates('F43');
                $drawing->setOffsetX(10);
                $drawing->setOffsetY(5);
                $drawing->setWorksheet($sheet);

                // Configurar anchos de columnas
                $sheet->getColumnDimension('A')->setWidth(4);
                $sheet->getColumnDimension('B')->setWidth(5);
                $sheet->getColumnDimension('C')->setAutoSize(false)->setWidth(10);
                $sheet->getColumnDimension('D')->setWidth(20);
                $sheet->getColumnDimension('E')->setWidth(20);
                $sheet->getColumnDimension('F')->setAutoSize(false)->setWidth(15);

                // 🔥 Columnas G, H e I con autosize
                $sheet->getColumnDimension('G')->setAutoSize(true);
                $sheet->getColumnDimension('H')->setAutoSize(true);
                $sheet->getColumnDimension('I')->setAutoSize(true);

                $sheet->calculateColumnWidths();
            },
        ];
    }
}
