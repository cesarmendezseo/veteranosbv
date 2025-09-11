<?php

namespace App\Http\Controllers;

use App\Models\Jugador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FotoJugadorController extends Controller
{


    public function upload($jugadorId)
    {
        // Aquí puedes implementar la lógica para manejar la carga del logo del equipo
        // Por ejemplo, podrías retornar una vista con un formulario de carga de imagen
        $jugador = Jugador::findOrFail($jugadorId);
        return view('admin.jugadorFoto', compact('jugador'));
    }


    // Guardar logo
    public function guardarFoto(Request $request, $jugadorId)
    {

        $jugador = Jugador::findOrFail($jugadorId);


        $request->validate([
            'foto' => 'required|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $ruta = $request->file('foto')->store('fotos', 'public');

            if ($jugador->foto) {
                Storage::disk('public')->delete($jugador->foto);
            }

            $jugador->foto = $ruta;
            $jugador->save();

            return redirect()->back()->with('success', 'Jugador actualizado correctamente.');
        }

        return back()->withErrors(['foto' => 'No se pudo subir el archivo.']);
    }
}
