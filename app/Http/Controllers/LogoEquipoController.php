<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use App\Models\Equipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert as FacadesLivewireAlert;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class LogoEquipoController extends Controller
{
    public function subirLogo(Request $request)
    {

        // Validación del archivo
        $request->validate([
            'logo' => 'required|image|max:2048', // 2MB
        ]);

        // Crear carpeta si no existe
        if (!Storage::disk('public')->exists('logos')) {
            Storage::disk('public')->makeDirectory('logos');
        }

        // Guardar archivo
        $path = $request->file('logo')->store('logos', 'public');

        // Guardar en configuración
        Configuracion::set('logo', $path);



        return redirect()->route('configuracion.index')
            ->with('success', 'Logo actualizado correctamente.');
    }

    public function index()
    {
        return view('admin.logoPagina');
    }
}
