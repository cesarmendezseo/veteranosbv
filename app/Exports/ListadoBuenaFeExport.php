<?php

namespace App\Exports;

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

class ListadoBuenaFeExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    protected $equipoId;
    protected $equipoNombre;
    private int $rowNumber = 1; // Comienza en 1
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
        return Jugador::where('equipo_id', $this->equipoId)
            ->where('is_active', true)
            ->orderBy('apellido', 'asc')
            ->with(['equipo', 'sanciones' => function ($q) {
                $q->where('campeonato_id', $this->campeonato_id)
                    ->whereColumn('partidos_cumplidos', '<', 'partidos_sancionados');
            }])
            ->get();
    }
    //============================================================================================
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
    //============================================================================================
    public function map($jugador): array
    {
        $sancionActiva = $jugador->sanciones->first(); // Puede haber una sola sanción activa por jugador

        $leyenda = '';

        if ($sancionActiva) {
            $motivo = strtolower($sancionActiva->motivo);
            $leyenda = match ($motivo) {
                'doble amarilla' => 'paga $2000',
                default => ($sancionActiva->partidos_sancionados - $sancionActiva->partidos_cumplidos) . ' Fechas'
            };
        }

        return [
            $this->rowNumber++, // Número correlativo
            '',
            $jugador->documento,
            $jugador->apellido,
            $jugador->nombre,
            '', // Espacio para firmas
            '', // Espacio para goles
            '', // Espacio para tarjetas
            $leyenda
        ];
    }


    //============================================================================================
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                // Establecer tamaño oficio (legal)
                $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LEGAL);
                // Obtener el objeto PageMargins
                $margins = $sheet->getPageMargins();
                // Establecer el margen inferior (en pulgadas - puedes ajustar el valor)
                $margins->setBottom(0.5); // 1.2 pulgadas de margen inferior (ejemplo)
                // Establecer márgenes laterales (en pulgadas - puedes ajustar el valor)
                $margins->setLeft(0.5);   // 1 pulgada de margen izquierdo
                $margins->setRight(0.5);  // 1 pulgada de margen derecho


                $sheet->getPageMargins()->setTop(1); //margen para encabezado Por defecto es 0.75, aumentá según lo necesites
                $sheet->getHeaderFooter()
                    ->setOddHeader('&L&G&R&"Arial,Bold"&12ASOCIACIÓN CIVIL' . "\n" . 'DE FÚTBOL DE VETERANOS' . "\n" . 'BELLA VISTA - CORRIENTES');



                // Cargar la imagen para el encabezado izquierdo
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooterDrawing();
                $drawing->setName('Logo');
                $drawing->setPath(public_path('images/logo.jpeg')); // Ruta de tu logo
                $drawing->setHeight(36); // Altura del logo

                $sheet->getHeaderFooter()->addImage($drawing, HeaderFooter::IMAGE_HEADER_LEFT);

                // 1. Insertar filas arriba de los encabezados
                $sheet->insertNewRowBefore(1, 4);

                // 2. Agregar títulos
                $sheet->mergeCells('A1:I1');
                $sheet->setCellValue('A1', $this->torneoNombre);

                $sheet->mergeCells('B2:C2');
                $sheet->setCellValue('B2', 'FECHA: ' .  $this->fecha);

                $sheet->mergeCells('B3:C3');
                $sheet->setCellValue('B3', 'CANCHA: ');

                $sheet->mergeCells('B4:D4');
                $sheet->setCellValue('B4',  $this->equipoNombre);

                $sheet->mergeCells('F2:H2');
                $sheet->setCellValue('F2', 'EL DÍA:__/__/___/ ');
                $sheet->mergeCells('F4:H4');
                $sheet->setCellValue('F4',  'CONDICION:_________');
                $sheet->mergeCells('F3:H3');
                $sheet->setCellValue('F3',  'GOLES:_____');




                // 3. Aplicar estilos a los títulos del torneo
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

                //estilos a la fecha
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
                $sheet->getStyle('B4')->applyFromArray([ // Aplica el estilo a la celda combinada (generalmente la primera)
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




                // 4. Mover los encabezados a la fila 5
                $sheet->fromArray([$this->headings()], null, 'A5');

                // 5. Aplicar estilos a los encabezados (ahora en fila 5)
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

                // 6. Estilo para las filas de datos (inicialmente hasta la fila 30 con bordes)
                $endRow = 35;
                $sheet->getStyle('A5:I' . $endRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000']
                        ]
                    ]
                ]);

                //=================2do bloque==================================
                $startRowBloque2 = 36;

                // Título del segundo bloque
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

                // Subtítulos del segundo bloque
                $subtitulosRow = $startRowBloque2 + 1;
                $sheet->setCellValue("A{$subtitulosRow}", 'Cargo');
                $sheet->mergeCells("A{$subtitulosRow}:C{$subtitulosRow}");

                $sheet->setCellValue("D{$subtitulosRow}", 'DNI');

                $sheet->setCellValue("E{$subtitulosRow}", 'Apellido y Nombre');
                $sheet->mergeCells("E{$subtitulosRow}:F{$subtitulosRow}");

                $sheet->setCellValue("G{$subtitulosRow}", 'Firma');
                $sheet->mergeCells("G{$subtitulosRow}:H{$subtitulosRow}");
                $sheet->setCellValue("I{$subtitulosRow}", 'Observación');

                //estilos a los subtitulos
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


                // Datos del cuerpo técnico
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

                    // Opcional: dejar columnas C, F vacías o con placeholder si necesitás
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


                // 8. Centrar texto en algunas celdas
                $sheet->getStyle('C5:C' . $sheet->getHighestRow())->getAlignment()->setHorizontal('center');

                // 9. pinta jugadores de rojo sancionados
                $jugadores = $this->collection();
                $rowOffset = 5; // Encabezados están en fila 5
                $alturaFilaSancion = 20; // Define la altura deseada para las filas con sanción (en puntos)
                $alturaFilaNormal = 20;
                foreach ($jugadores as $index => $jugador) {
                    $row = $rowOffset + $index + 1;
                    $sheet->getRowDimension($row)->setRowHeight($alturaFilaNormal); // Establece una altura normal por defecto (opcional)

                    $sancion = Sanciones::where('jugador_id', $jugador->id)
                        ->where('campeonato_id', $this->campeonato_id)
                        ->where('cumplida', false)
                        ->first();

                    if ($sancion) {
                        $sheet->getStyle("C{$row}:I{$row}")->applyFromArray([
                            'font' => [
                                'color' => ['rgb' => 'FF0000'],
                                'bold' => true,
                            ]
                        ]);
                        $sheet->getRowDimension($row)->setRowHeight($alturaFilaSancion); // Establece una altura mayor si hay sanción
                    }
                }
                // Agregar una imagen al final
                $drawing = new Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Descripción del logo');
                $drawing->setPath(public_path('images/cambios.png')); // Ruta a tu imagen (debe ser una ruta válida)
                $drawing->setHeight(185); // Altura en píxeles (ajusta según necesites)
                $drawing->setCoordinates('F43'); // Celda donde se insertará la esquina superior izquierda de la imagen
                $drawing->setOffsetX(10);       // Opcional: ajustar posición horizontal (en píxeles)
                $drawing->setOffsetY(5);        // Opcional: ajustar posición vertical (en píxeles)
                $drawing->setWorksheet($sheet);


                // Ajuste de columnas
                $sheet->getColumnDimension('A')->setWidth(4);
                $sheet->getColumnDimension('B')->setWidth(5);  // aunque esté combinada, es buena práctica
                //$sheet->getColumnDimension('C')->setWidth(10);
                $sheet->getColumnDimension('D')->setWidth(20);
                $sheet->getColumnDimension('E')->setWidth(20);
                //$sheet->getColumnDimension('F')->setWidth(25);
                $sheet->getColumnDimension('G')->setWidth(5);
                $sheet->getColumnDimension('H')->setWidth(5);
                $sheet->getColumnDimension('I')->setAutoSize(false)->setWidth(20);

                $sheet->getColumnDimension('C')->setAutoSize(false)->setWidth(10);
                $sheet->getColumnDimension('F')->setAutoSize(false)->setWidth(15);

                // Forzar la actualización de dimensiones
                $sheet->calculateColumnWidths();
            },


        ];
    }
}
