<x-layouts.app :title="__('upload logo')">
    <div class="max-w-md mx-auto mt-10">
        <div class="flex items-center p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400"
            role="alert">
            <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
            <div>
                <h1> <span class="font-medium underline decoration-blue-500">Cargar Logo pagina cesar</h1>
            </div>
        </div>

        <form action="{{ route('configuracion.subir-logo') }}" method="POST" enctype="multipart/form-data" wire:ignore>
            @csrf

            <label class="block text-sm font-medium text-gray-700">Subir logo</label>
            <input type="file" name="logo" class="mt-1 block w-full">

            <button class="mt-2 px-4 py-2 bg-blue-600 text-white rounded">
                Subir logo
            </button>

            @if(session('success'))
            <p class="text-green-600 mt-2">{{ session('success') }}</p>
            @endif
        </form>
    </div>
</x-layouts.app>