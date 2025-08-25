<header x-data="{ open: false }"
    class="bg-blue-900 text-white p-2 shadow-md rounded flex justify-between items-center relative z-10">
    <h1 class="text-lg font-bold">{!! $titulo ?? 'Título' !!}</h1>

    {{-- Botón hamburguesa móvil --}}
    <button class="cursor-pointer md:hidden focus:outline-none flex items-center space-x-2" @click="open = true">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        <span>Menu</span>
    </button>

    {{-- Menú escritorio --}}
    <nav class="hidden md:flex space-x-4">
        {{ $slot }}
    </nav>

    {{-- Menú flotante móvil --}}
    <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-start z-50 p-4"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">
        <div class="bg-blue-900 w-full max-w-sm rounded shadow-lg p-6 space-y-4 relative">
            <button @click="open = false" class="absolute top-2 right-2 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            {{ $slot }}
        </div>
    </div>
</header>
