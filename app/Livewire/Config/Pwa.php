<?php

namespace App\Livewire\Config;

use Livewire\WithFileUploads;
use App\Models\PwaConfig;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class Pwa extends Component
{
    use WithFileUploads;
    // ... (Propiedades)
    public $configId;
    public $name;
    public  $short_name;
    public $background_color;
    public $theme_color;
    public $description;
    public $icon;


    public function mount()
    {
        $config = PwaConfig::getSingletonConfig();

        $this->configId = $config->id;
        $this->name = $config->name;
        $this->short_name = $config->short_name;
        $this->background_color = $config->background_color;
        $this->theme_color = $config->theme_color;
        $this->description = $config->description;
        $this->icon = $config->icon;

        // ❌ ELIMINAR EL BLOQUE REPETIDO DE LA CARGA DE $config

    }

    // ... (Reglas: Las dejamos como están, pero verifica el max:50 si usas rutas largas)

    public function save()
    {
        $this->validate();

        $config = PwaConfig::find($this->configId);

        // 1. Manejar la subida del nuevo ícono
        if ($this->newIcon) {
            $fileName = 'pwa-logo.png';
            $path = 'images'; // Carpeta de destino DENTRO de public/

            // Usamos el disco 'public'. El archivo se guarda en storage/app/public/images/pwa-logo.png
            // y se accede por URL vía /storage/images/pwa-logo.png
            $this->newIcon->storeAs($path, $fileName, 'public');

            // 🆕 Actualizar 'icon' con la URL relativa al sitio para que funcione el asset() y la PWA
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
                'icon' => $iconPublicPath, // Ahora contiene la ruta completa: storage/images/pwa-logo.png
            ]);

            // ... (Alerta de éxito) ...
        } else {
            // ... (Alerta de error) ...
        }
    }

    public function render()
    {
        return view('livewire.config.pwa');
    }
}
