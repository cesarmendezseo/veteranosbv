<?php

namespace App\Exports;

use App\Models\CampeonatoJugadorEquipo;
use App\Models\Jugador;
use App\Models\Sanciones;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithMapping,
    WithEvents,
    ShouldAutoSize
};
use Maatwebsite\Excel\Events\AfterSheet;

class ListadoBuenaFeExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithEvents,
    ShouldAutoSize
{
    protected $equipoId;
    protected $campeonato;
    protected $campeonato_id;
    protected $fecha;
    protected $rowNumber = 1;

    public function __construct($equipoId, $campeonato, $campeonato_id, $fecha)
    {
        $this->equipoId = $equipoId;
        $this->campeonato = $campeonato;
        $this->campeonato_id = $campeonato_id;
        $this->fecha = $fecha;
    }

    /**
     * 👉 ACA DEFINIMOS QUIÉNES APARECEN
     * TODOS los jugadores del equipo/campeonato
     */
    public function collection(): Collection
    {
        return CampeonatoJugadorEquipo::with('jugador')
            ->where('campeonato_id', $this->campeonato_id)
            ->where('equipo_id', $this->equipoId)
            ->whereNull('fecha_baja')
            ->orderBy(
                Jugador::select('apellido')
                    ->whereColumn('jugadores.id', 'campeonato_jugador_equipo.jugador_id')
            )
            ->get();
    }

    /**
     * 👉 ACA DEFINIMOS CÓMO APARECEN
     */
    public function map($registro): array
    {
        $jugador = $registro->jugador;

        $sancion = Sanciones::activa()
            ->where('jugador_id', $jugador->id)
            ->first();

        return [
            $this->rowNumber++,               // #
            '',                                // Firma
            $jugador->documento,              // DNI
            strtoupper($jugador->apellido),   // Apellido
            strtoupper($jugador->nombre),     // Nombre
            $sancion ? 'SUSPENDIDO' : '',      // Estado
            '',                                // Observación
            '',                                // Fecha
            $sancion ? strtoupper($this->leyendaSancion($sancion)) : '',
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'FIRMA',
            'DNI',
            'APELLIDO',
            'NOMBRE',
            'ESTADO',
            '',
            '',
            'OBSERVACIÓN',
        ];
    }

    /**
     * Texto humano de la sanción
     */
    private function leyendaSancion($sancion): string
    {
        if ($sancion->fecha_fin) {
            return 'SANCIONADO ' .
                $sancion->fecha_inicio->format('d/m/Y') .
                ' - ' .
                $sancion->fecha_fin->format('d/m/Y');
        }

        return 'SANCIONADO SIN FECHA DE FIN';
    }

    /**
     * SOLO ESTILOS (NO lógica)
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->getStyle('A1:I1')->applyFromArray([
                    'font' => ['bold' => true],
                ]);
            },
        ];
    }
}
