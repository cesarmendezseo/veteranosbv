<?php

namespace Database\Seeders;

use App\Models\Jugador;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class JugadoresSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/jugadores.csv');

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

            // Crear jugador y asignar manualmente created_at y updated_at
            $jugador = Jugador::create([
                'documento'       => $data['documento'],
                'tipo_documento'  => $data['tipo_documento'],
                'nombre'          => $data['nombre'],
                'apellido'        => $data['apellido'],
                'fecha_nac'       => $data['fecha_nac'],
                'num_socio'       => $data['num_socio'],
                'telefono'        => $data['telefono'],
                'email'           => $data['email'],
                'direccion'       => $data['direccion'],
                'ciudad'          => $data['ciudad'],
                'provincia'       => $data['provincia'],
                'cod_pos'         => $data['cod_pos'],
                'foto'            => $data['foto'],
                'is_active'       => $data['is_active'],
                'equipo_id'       => $data['equipo_id'],
            ]);

            // Asignar created_at y updated_at
            $jugador->created_at = $data['created_at'];
            $jugador->updated_at = $data['updated_at'];
            $jugador->save();
        }

        fclose($file);
    }
}
