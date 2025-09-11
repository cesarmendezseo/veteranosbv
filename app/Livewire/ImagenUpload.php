<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class ImagenUpload extends Component
{


    use WithFileUploads; // Usa el trait

    public $image; // Propiedad para almacenar la imagen temporalmente

    // Método para guardar la imagen
    public function save()
    {
        // 1. Validar la imagen
        $this->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // 2MB Max
        ]);

        // 2. Guardar la imagen
        // Puedes especificar un directorio dentro de 'storage/app/public/'
        $imageName = $this->image->store('photos', 'public'); // Guarda en storage/app/public/photos

        // Opcional: Si quieres moverlo a otro disco o ruta específica:
        // $imageName = $this->image->storeAs('images', 'nombre_personalizado.jpg', 'public');

        // Opcional: Guardar el nombre de la imagen en la base de datos
        // Por ejemplo, si tienes un modelo Photo:
        // Photo::create(['path' => $imageName]);

        session()->flash('message', '¡Imagen cargada exitosamente!');

        // Limpiar la propiedad después de guardar
        $this->image = null;
    }
    public function render()
    {
        return view('livewire.imagen-upload');
    }
}
