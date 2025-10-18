<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LogoEquipoController extends Controller
{
    public function upload($equipoId)
    {
        // Aquí puedes implementar la lógica para manejar la carga del logo del equipo
        // Por ejemplo, podrías retornar una vista con un formulario de carga de imagen
        $equipo = Equipo::findOrFail($equipoId);
        return view('admin.equipoLogo', compact('equipo'));
    }


    // Guardar logo
    public function guardarLogo(Request $request, $equipoId)
    {

        $equipo = Equipo::findOrFail($equipoId);

        $request->validate([
            'logo' => 'required|image|max:2048',
        ], [
            'logo.required' => 'Debés seleccionar una imagen.',
            'logo.image' => 'El archivo debe ser una imagen válida (JPG, PNG, etc).',
            'logo.max' => 'La imagen no puede superar los 2MB.',
        ]);

        try {
            $request->validate([
                'logo' => 'required|image|max:2048',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors(['logo' => 'Hubo un problema con la imagen.'])->withInput();
        }



        if ($request->hasFile('logo')) {
            $ruta = $request->file('logo')->store('logos', 'public');

            if ($equipo->logo) {
                Storage::disk('public')->delete($equipo->logo);
            }


            $equipo->logo = $ruta;
            $equipo->save();

            return redirect()->route('equipo.index')
                ->with('success', 'Logo actualizado correctamente.');
        }


        return back()->withErrors(['logo' => 'No se pudo subir el archivo.']);
    }
}
