<?php

namespace Database\Seeders;

use App\Models\Equipo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class Equipos extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/equipos.csv');

        if (!File::exists($path)) {
            $this->command->error("Archivo no encontrado: $path");
            return;
        }

        $file = fopen($path, 'r');

        // Leer encabezado
        $headers = fgetcsv($file, 0, ';');

        while (($row = fgetcsv($file, 0, ';')) !== false) {
            $data = array_combine($headers, $row);

            // Convertir "\N" a null
            foreach ($data as $key => $value) {
                if ($value === '\N') {
                    $data[$key] = null;
                }
            }

            // Crear equipo y asignar manualmente created_at y updated_at
            $equipo = Equipo::create([
                'nombre'       => $data['nombre'],
                'is_active'  => $data['is_active'],

            ]);

            // Asignar created_at y updated_at
            $equipo->created_at = $data['created_at'];
            $equipo->updated_at = $data['updated_at'];
            $equipo->save();
        }

        fclose($file);
    }
}
