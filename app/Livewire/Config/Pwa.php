<?php

namespace App\Livewire\Config;

use Livewire\WithFileUploads;
use App\Models\PwaConfig;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class Pwa extends Component
{
    use WithFileUploads;

    public $configId;
    public $name;
    public $short_name;
    public $background_color;
    public $theme_color;
    public $description;
    public $icon;
    public $newIcon; // Propiedad para el archivo cargado

    public function mount()
    {
        // Usamos el método del modelo para asegurar que siempre haya un registro
        $config = PwaConfig::getSingletonConfig();

        $this->configId = $config->id;
        $this->name = $config->name;
        $this->short_name = $config->short_name;
        $this->background_color = $config->background_color;
        $this->theme_color = $config->theme_color;
        $this->description = $config->description;
        $this->icon = $config->icon; // La ruta actual (ej. storage/images/logo.png)
    }

    /**
     * Define las reglas de validación para Livewire.
     */
    public function rules() // 👈 ¡ESTE MÉTODO ES CRUCIAL!
    {
        $rules = [
            'name' => 'required|string|max:50',
            'short_name' => 'required|string|max:12',
            // Simple validación de color hexadecimal
            'background_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'theme_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'description' => 'required|string|max:150',
            // La validación del 'icon' actual ya no es necesaria si solo lo mostramos,
            // pero si se edita manualmente en el campo de texto, mantén la validación.
            'icon' => 'nullable|string|max:100',
        ];

        // Regla condicional para la nueva imagen subida
        if ($this->newIcon) {
            // El archivo debe ser una imagen, tener un tamaño máximo de 1MB y ser png/jpg/jpeg
            $rules['newIcon'] = 'image|max:1024|mimes:png,jpg,jpeg';
        }

        return $rules;
    }

    public function save()
    {
        $this->validate();

        $config = PwaConfig::find($this->configId);

        // 1. Manejar la subida del nuevo ícono
        if ($this->newIcon) {
            $fileName = 'pwa-logo.png';
            $path = 'images'; // Carpeta de destino dentro de storage/app/public/

            // Guardamos en storage/app/public/images/pwa-logo.png
            $this->newIcon->storeAs($path, $fileName, 'public');

            // Actualizar 'iconPublicPath' con la URL de acceso (/storage/images/pwa-logo.png)
            $iconPublicPath = 'storage/' . $path . '/' . $fileName;
        } else {
            // Si no se sube un nuevo ícono, mantén el valor actual de la DB
            $iconPublicPath = $this->icon;
        }

        // 2. Actualizar los datos en la base de datos
        if ($config) {
            $config->update([
                'name' => $this->name,
                'short_name' => $this->short_name,
                'background_color' => $this->background_color,
                'theme_color' => $this->theme_color,
                'description' => $this->description,
                'icon' => $iconPublicPath,
            ]);

            // Limpiar la propiedad de carga para evitar re-subidas
            $this->newIcon = null;

            LivewireAlert::title('Ok')
                ->text('✅ Configuración PWA actualizada correctamente.')
                ->success()
                ->toast()
                ->show();
        } else {
            LivewireAlert::title('Error')
                ->text('❌ Error: No se encontró el registro de configuración.')
                ->error()
                ->toast()
                ->show();
        }
    }

    public function render()
    {
        return view('livewire.config.pwa');
    }
}
