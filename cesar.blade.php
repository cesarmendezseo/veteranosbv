<!-- BOTON GUARDAR Y VOLVER -->
<div class="flex justify-between mt-4">
    <button type="submit" class="bg-blue-950 text-white px-4 py-2 rounded flex items-center gap-2 cursor-pointer hover:bg-blue-800">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hard-drive-upload">
            <path d="m16 6-4-4-4 4" />
            <path d="M12 2v8" />
            <rect width="20" height="8" x="2" y="14" rx="2" />
            <path d="M6 18h.01" />
            <path d="M10 18h.01" />
        </svg>
        Guardar Imagen
    </button>

    <button type="submit" class="bg-blue-950 text-white px-4 py-2 rounded flex items-center gap-2 cursor-pointer hover:bg-blue-800">
        <svg class="w-6 h-6 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M11 16h2m6.707-9.293-2.414-2.414A1 1 0 0 0 16.586 4H5a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V7.414a1 1 0 0 0-.293-.707ZM16 20v-6a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v6h8ZM9 4h6v3a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1V4Z" />
        </svg>

        Guardar
    </button>
    <a href="{{ route('equipo.index') }}" class="bg-blue-950 text-white px-4 py-2 rounded flex items-center gap-2 hover:bg-blue-800"> <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        Volver</a>
</div>
<!-- //////////////////////////////////////// -->