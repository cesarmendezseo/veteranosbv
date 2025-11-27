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

        <button type="submit"
            class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-150 ease-in-out">
            Guardar Configuración PWA
        </button>
    </form>
</div>