<div>
    <flux:navbar>
        <a href="{{route('sanciones.crear')}}"
            class="px-5 py-2.5 gap-4 text-sm font-medium text-white inline-flex items-center bg-blue-950 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 shadow">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-badge-plus-icon lucide-badge-plus">
                <path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z" />
                <line x1="12" x2="12" y1="8" y2="16" />
                <line x1="8" x2="16" y1="12" y2="12" />
            </svg>
            Crear
        </a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white ml-4">Sanciones</h1>
        @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif
    </flux:navbar>
    <flux:separator />
    <div class="mt-4">
        @livewire('sanciones.sanciones-ver')
        {{-- @if ($vistaActual === 'ver')
            @livewire('sanciones.sanciones-ver')
        @elseif ($vistaActual === 'crear')
            @livewire('sanciones.sanciones-crear')
        @endif --}}
    </div>


</div>