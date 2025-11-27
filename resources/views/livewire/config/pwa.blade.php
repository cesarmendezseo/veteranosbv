<div class="p-6 bg-white shadow-lg dark:bg-gray-700  rounded-lg">
    <h2 class="text-2xl font-bold mb-4  ">⚙️ Configuración del Manifest PWA</h2>


    <form wire:submit.prevent="save">
        {{-- Nombre Completo --}}
        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-semibold mb-2 dark:text-gray-50 ">Nombre de la Aplicación
            </label>
            <input type="text" id="name" wire:model="name"
                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-600 p-3 dark:border-gray-400 dark:border border ">

        </div>

        {{-- Nombre Corto --}}
        <div class="mb-4">
            <label for="short_name" class="block text-gray-700 font-semibold mb-2 dark:text-gray-50">Nombre
                Corto</label>
            <input type="text" id="short_name" wire:model="short_name"
                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-600 p-3 dark:border-gray-400 dark:border border">

        </div>

        {{-- Colores --}}
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="theme_color" class="block text-gray-700 font-semibold mb-2 dark:text-gray-50">Color del Tema
                </label>
                <input type="color" id="theme_color" wire:model="theme_color"
                    class="cursor-pointer w-full h-10 border-gray-300 rounded-md shadow-sm">

            </div>
            <div>
                <label for="background_color" class="block text-gray-700 font-semibold mb-2 dark:text-gray-50">Color de
                    Fondo
                </label>
                <input type="color" id="background_color" wire:model="background_color"
                    class="cursor-pointer  w-full h-10 border-gray-300 rounded-md shadow-sm">

            </div>
        </div>

        {{-- Descripción --}}
        <div class="mb-4">
            <label for="description"
                class="block text-gray-700 font-semibold mb-2 dark:text-gray-50">Descripción</label>
            <textarea id="description" wire:model="description" rows="3"
                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-600 p-3 dark:border-gray-400 dark:border border"></textarea>

        </div>

        {{-- Icono --}}
        <div class="mb-6">
            <label for="icon" class="block text-gray-700 font-semibold mb-2 dark:text-gray-50">Nombre del Archivo del
                Ícono </label>
            <input type="text" id="icon" wire:model="icon"
                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-600 p-3 dark:border-gray-400 dark:border border">
            <p class="text-xs text-gray-500 mt-1">Debe ser el nombre del archivo del ícono, el que carga para la página
                principal (ej. `logo.png`).</p>

        </div>
        {{-- 🆕 Sección de Subida de Icono --}}
        <div class="mb-6 border p-4 rounded-md">
            <h3 class="text-lg font-semibold mb-3">🖼️ Ícono de la Aplicación</h3>

            <div class="flex items-center space-x-4 mb-3 border border-gray-400 rounded p-2">
                <p class="font-medium text-gray-700 dark:text-gray-100">Ícono Actual:</p>
                @if ($icon)
                {{-- Mostrar el ícono actual. Nota: Asset() es necesario para la ruta pública --}}
                <img src="{{ asset($icon) }}" alt="Ícono actual" class="w-10 h-10 rounded-lg shadow-md">
                <span class="text-sm text-gray-600 dark:text-gray-100">{{ $icon }}</span>
                @else
                <span class="text-sm text-red-500">No hay ícono configurado.</span>
                @endif
            </div>

            <label for="newIcon"
                class="cursor-pointer block text-gray-700 font-semibold mb-2 dark:text-gray-100">Cambiar Ícono (.png,
                .jpg)</label>
            {{-- ⚠️ wire:model="newIcon" es CRUCIAL para Livewire File Uploads --}}
            <input type="file" id="newIcon" wire:model="newIcon"
                class="cursor-pointer w-full border-gray-300 rounded-md shadow-sm border border:bg-gray-100 p-2">

            @error('newIcon')
            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
            @enderror

            {{-- Opcional: Mostrar una barra de progreso mientras sube --}}
            <div wire:loading wire:target="newIcon" class="mt-2 text-blue-500 text-sm">
                Subiendo imagen...
            </div>
        </div>

        <button type="submit"
            class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-150 ease-in-out">
            Guardar Configuración PWA
        </button>
    </form>
</div>