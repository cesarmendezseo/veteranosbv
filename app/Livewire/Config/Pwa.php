<?php

namespace App\Livewire\Config;

use Livewire\WithFileUploads;
use App\Models\PwaConfig;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class Pwa extends Component
{
    use WithFileUploads;
    // Propiedades que se enlazan al formulario
    public $name;
    public $short_name;
    public $background_color;
    public $theme_color;
    public $description;
    public $icon;

    // Almacena el ID de la configuración para saber qué actualizar


    public $newIcon;

    public $configId;

    public function mount()
    {
        // Usa el método del modelo para asegurar que siempre haya un registro
        $config = PwaConfig::getSingletonConfig();

        $this->configId = $config->id;
        $this->name = $config->name;
        $this->short_name = $config->short_name;
        $this->background_color = $config->background_color;
        $this->theme_color = $config->theme_color;
        $this->description = $config->description;
        $this->icon = $config->icon;

        $config = PwaConfig::getSingletonConfig();
        $this->configId = $config->id;
        // ... (Carga de los demás campos)
        $this->icon = $config->icon;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:50',
            'short_name' => 'required|string|max:12',
            // Simple validación de color hexadecimal
            'background_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'theme_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'description' => 'required|string|max:150',
            'icon' => 'required|string|ends_with:.png,.jpg|max:50', // Ajusta si permites subir archivos
        ];
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
            // 🆕 Definir el nombre del archivo y la ubicación
            // Usamos un nombre fijo (pwa-logo.png) para que siempre sobrescriba
            $fileName = 'pwa-logo.png';

            // 🆕 Mover el archivo al directorio public
            // La subida usa por defecto el disco 'public' y crea una carpeta 'livewire-tmp'
            // El método storeAs mueve el archivo de la carpeta temporal a la ruta deseada.
            $this->newIcon->storeAs('/', $fileName, 'public');

            // 🆕 Actualizar la propiedad 'icon' con el nuevo nombre (sin la ruta pública, solo el nombre)
            $this->icon = $fileName;
        }
        // 2. Actualizar los datos en la base de datos
        if ($config) {
            $config->update([
                'name' => $this->name,
                'short_name' => $this->short_name,
                'background_color' => $this->background_color,
                'theme_color' => $this->theme_color,
                'description' => $this->description,
                'icon' => $this->icon,
            ]);
            LivewireAlert::title('Ok')
                ->text('✅ Configuración PWA actualizada correctamente.')
                ->success()
                ->toast()
                ->show();
        } else {
            LivewireAlert::title('Error')
                ->text('❌ Error: No se encontró el registro de configuración..')
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
