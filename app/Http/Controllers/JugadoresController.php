<?php

namespace App\Http\Controllers;

use App\Imports\JugadoresImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class JugadoresController extends Controller
{
    /**
     * Muestra el formulario para subir el archivo.
     */
    public function showImportForm()
    {
        return view('admin.importar');
    }

    /**
     * Procesa el archivo subido y ejecuta la importación.
     */
    public function import(Request $request)
    {
        // 1. **VALIDACIÓN CRÍTICA:** Asegura que se ha subido un archivo y que es del formato correcto.
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10000', // Max 10MB
        ], [
            'file.required' => 'Debes seleccionar un archivo.',
            'file.mimes' => 'El archivo debe ser un Excel (.xlsx, .xls) o CSV.',
        ]);

        try {
            // 2. **EJECUCIÓN DE LA IMPORTACIÓN:** Usa Laravel Excel para leer el archivo subido.
            Excel::import(new JugadoresImport, $request->file('file'));

            // 3. Respuesta de éxito
            return back()
                ->with('success', '¡La lista de personas ha sido importada exitosamente!');
        } catch (\Exception $e) {
            // 4. Manejo de errores (por ejemplo, si falla la base de datos o el mapeo)

            return back()
                ->withErrors(['import_error' => 'Ocurrió un error durante la importación. Asegúrate de que el formato del archivo es correcto.']);
        }
    }
}
