<?php

namespace App\Imports;

use App\Models\Jugador;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JugadoresImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Mapea las columnas del Excel (ej: 'Apellido') a las columnas de tu DB
        return new Jugador([
            'apellido' => $row['apellido'],
            'nombre' => $row['nombre'],
            'documento' => $row['dni'],
            'fecha_nac' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['fecha_nac']),
            'tipo_documento' => 'dni',
            'telefono' => $row['telefono']
            // ... otras columnas ...
        ]);
    }
}
