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
    <button type="submit" class="inline-flex items-center gap-2 mt-4  bg-blue-950 hover:bg-blue-800 text-white px-4 py-2 rounded ">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-save-icon lucide-save">
            <path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
            <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7" />
            <path d="M7 3v4a1 1 0 0 0 1 1h7" />
        </svg> <span>Guardar</span></button>
    <a href="{{route('campeonato.index')}}" class="inline-flex items-center gap-2 mt-4  bg-blue-950 hover:bg-blue-800 text-white px-4 py-2 rounded ">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <span>Volver</span>
    </a>
    <!-- //////////////////////////////////////// -->