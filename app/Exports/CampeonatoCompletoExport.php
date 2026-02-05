<?php

namespace App\Exports;

use App\Models\Jugador;
use App\Models\Equipo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class CampeonatoCompletoExport implements FromCollection, WithHeadings, WithMapping
{
    protected $campeonatoId;
    protected $nombreTorneo;
    protected $fecha;

    public function __construct($campeonatoId, $nombreTorneo, $fecha)
    {
        $this->campeonatoId = $campeonatoId;
        $this->nombreTorneo = $nombreTorneo;
        $this->fecha = $fecha;
    }

    public function collection(): Collection
    {
        // Obtener IDs de equipos que participan en este campeonato
        $equiposIds = Equipo::whereHas('campeonatos', function ($q) {
            $q->where('campeonato_id', $this->campeonatoId);
        })
            ->pluck('id');

        Log::info('Equipos en campeonato ' . $this->campeonatoId, [
            'cantidad' => $equiposIds->count(),
            'ids' => $equiposIds->toArray()
        ]);

        // Obtener jugadores de esos equipos
        $jugadores = Jugador::whereIn('equipo_id', $equiposIds)
            ->with('equipo')
            ->orderBy('equipo_id')
            ->orderBy('apellido')
            ->get();

        Log::info('Jugadores encontrados', ['cantidad' => $jugadores->count()]);

        return $jugadores;
    }

    public function headings(): array
    {
        return [
            'TORNEO',
            'FECHA',
            'EQUIPO',
            'APELLIDO',
            'NOMBRE',
            'DNI',
            'FECHA NAC.',
        ];
    }

    public function map($jugador): array
    {
        return [
            $this->nombreTorneo,
            $this->fecha,
            $jugador->equipo?->nombre ?? 'SIN EQUIPO',
            $jugador->apellido,
            $jugador->nombre,
            $jugador->documento,
            $this->formatearFecha($jugador->fecha_nac),
        ];
    }

    private function formatearFecha($fecha): string
    {
        if (empty($fecha) || $fecha === '0000-00-00') {
            return '';
        }

        try {
            return \Carbon\Carbon::parse($fecha)->format('d/m/Y');
        } catch (\Exception $e) {
            return '';
        }
    }
}
