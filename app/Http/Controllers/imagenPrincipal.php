<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class imagenPrincipal extends Controller
{
    public function subirImagen(Request $request)
    {


        // Validación del archivo
        $request->validate([
            'fondo_pagina_principal' => 'required|image|max:2048', // 2MB
        ]);

        // Crear carpeta si no existe
        if (!Storage::disk('public')->exists('logos')) {
            Storage::disk('public')->makeDirectory('logos');
        }

        // Guardar archivo
        $path = $request->file('fondo_pagina_principal')->store('logos', 'public');

        // Guardar en configuración
        Configuracion::set('fondo_pagina_principal', $path);



        return redirect()->route('config.PanelConfiguracion')
            ->with('success', 'imagen actualizado correctamente.');
    }



    public function index()
    {
        return view('admin.imagenPrincipal');
    }
}
