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
        $fecha_string = $row['fecha_nac'];
        $fecha_formateada = null;

        // Verifica si el valor no está vacío antes de intentar la conversión
        if (!empty($fecha_string)) {
            // Intenta interpretar el string como una fecha en formato día/mes/año
            $dateTime = \DateTime::createFromFormat('d/m/Y', $fecha_string);

            // Si la conversión es exitosa, usa el formato de base de datos (YYYY-MM-DD)
            if ($dateTime) {
                $fecha_formateada = $dateTime->format('Y-m-d');
            } else {
                // Opción de fallback si el formato es distinto o está en otro idioma
                $fecha_formateada = date('Y-m-d', strtotime($fecha_string));
            }
        }

        // Mapea las columnas del Excel (ej: 'Apellido') a las columnas de tu DB
        return new Jugador([
            'apellido' => $row['apellido'],
            'nombre' => $row['nombre'],
            'documento' => $row['dni'],
            'fecha_nac' => $fecha_formateada,
            'tipo_documento' => 'dni',
            'telefono' => $row['telefono']
            // ... otras columnas ...
        ]);
    }
}
