<div class="p-4 bg-gray-100 rounded-lg mb-4 shadow-md">
   <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Altas y Bajas') }}
        </h2>
        
   </x-slot>
    <input wire:model.lazy="dni" wire:keydown.enter="buscar" type="text" placeholder="Buscar por DNI"
        class="mt-2 flex-grow bg-gray-50 mb-2 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-500 dark:border-gray-600 dark:placeholder-gray-800 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />

    <div class="overflow-x-auto">
        <table class="w-full border hidden ">
            <thead class="bg-gray-700 rounded-lg text-gray-100 dark:bg-gray-400 dark:text-gray-900">
                <tr class="">
                    <th class="p-2 text-xs">DNI</th>
                    <th class="p-2 text-xs">Nombre</th>
                    <th class="p-2 text-xs">Equipo</th>
                    <th class="p-2 text-xs">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jugadores as $jugador)
                    <tr
                        class="border-t odd:bg-gray-200 odd:text-gray-800 font-semibold even:text-gray-800 even:bg-gray-300 dark:odd:bg-gray-900 dark:even:bg-gray-800">
                        <td class="p-2 text-sm dark:text-gray-300">{{ $jugador->documento }}</td>
                        <td class="p-2 text-sm dark:text-gray-300">{{ strtoupper($jugador->nombre) }}
                            {{ strtoupper($jugador->apellido) }}
                        </td>
                        <td class="p-2 text-sm dark:text-gray-300">{{ ucwords($jugador->equipo?->nombre ?? '-') }}</td>

                        <td class="px-6 py-4 text-right">
                            <!-- Para pantallas medianas en adelante -->
                            <div x-data="{ open: false, top: 0, left: 0 }" class="relative" @keydown.escape.window="open=false"
                                @scroll.window="open=false">
                                <button x-ref="trigger"
                                    @click="
                            open = !open;
                            $nextTick(() => {
                                const btn = $refs.trigger;
                                const rect = btn.getBoundingClientRect();
                                const menu = $refs.menu;

                                // Mostrar temporalmente para medir tamaño real
                                const prevDisplay = menu.style.display;
                                const prevVisibility = menu.style.visibility;
                                menu.style.visibility = 'hidden';
                                menu.style.display = 'block';

                                const mh = menu.offsetHeight;
                                const mw = menu.offsetWidth;

                                // Restaurar estado
                                menu.style.display = prevDisplay;
                                menu.style.visibility = prevVisibility;

                                // Posición por defecto: debajo del botón
                                let top = rect.bottom + 8;
                                let left = rect.right - mw;

                                // Si no entra abajo, abrir arriba
                                if (top + mh > window.innerHeight) {
                                    top = rect.top - mh - 8;
                                }

                                // Limitar a bordes laterales
                                left = Math.max(8, Math.min(left, window.innerWidth - mw - 8));

                                $data.top = top;
                                $data.left = left;
                            });
                        "
                                    class="text-gray-600 hover:text-black focus:outline-none dark:text-white cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 6v.01M12 12v.01M12 18v.01" />
                                    </svg>
                                </button>

                                <div x-ref="menu" x-cloak x-show="open" @click.away="open=false"
                                    class="fixed z-50 flex flex-col gap-2 p-2 rounded-lg shadow-lg bg-gray-100 dark:bg-gray-800 dark:border dark:border-gray-700"
                                    :style="`top:${top}px; left:${left}px`" x-transition.opacity>
                                    <!--ALTA-->
                                    <a wire:click="mostrarFormularioAlta({{ $jugador->id }})"
                                        class="cursor-pointer  flex items-center gap-2 hover:underline">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-badge-plus">
                                            <path
                                                d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z" />
                                            <line x1="12" x2="12" y1="8" y2="16" />
                                            <line x1="8" x2="16" y1="12" y2="12" />
                                        </svg>
                                        <span class="ml-1">Alta</span>
                                    </a>
                                    <!-- BAJA -->

                                    <a wire:click="$dispatch('confirmar-baja', { id: {{ $jugador->id }} })"
                                        class="cursor-pointer  flex items-center gap-2 hover:underline">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>

                                        <span>Baja</span>
                                    </a>
                                    <!-- HISTORIAL -->

                                    <a wire:click="verHistorial({{ $jugador->id }})"
                                        class="cursor-pointer flex items-center gap-2 hover:underline">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                        <span>Historial</span>
                                    </a>
                                </div>
                            </div>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center p-4 text-sm dark:text-gray-300">No hay jugadores</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $jugadores->links() }}
    </div>

    <!-- movil -->
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($jugadores as $jugador)
            <div
                class="rounded-xl shadow-md bg-gray-100 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 p-4 flex justify-between items-start">

                <!-- Datos del jugador -->
                <div>
                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                        DNI: {{ $jugador->documento }}
                    </p>
                    <p class="text-base font-bold text-gray-900 dark:text-white">
                        {{ strtoupper($jugador->nombre) }} {{ strtoupper($jugador->apellido) }}
                    </p>
                    <p class="text-sm text-gray-700 dark:text-gray-400">
                        Equipo: {{ ucwords($jugador->equipo?->nombre ?? '-') }}
                    </p>
                </div>

                <!-- Menú de acciones -->
                <div x-data="{ open: false, top: 0, left: 0 }" class="relative" @keydown.escape.window="open=false"
                    @scroll.window="open=false">
                    <button x-ref="trigger"
                        @click="
                        open = !open;
                        $nextTick(() => {
                            const btn = $refs.trigger;
                            const rect = btn.getBoundingClientRect();
                            const menu = $refs.menu;
                            const prevDisplay = menu.style.display;
                            const prevVisibility = menu.style.visibility;
                            menu.style.visibility = 'hidden';
                            menu.style.display = 'block';
                            const mh = menu.offsetHeight;
                            const mw = menu.offsetWidth;
                            menu.style.display = prevDisplay;
                            menu.style.visibility = prevVisibility;
                            let top = rect.bottom + 8;
                            let left = rect.right - mw;
                            if (top + mh > window.innerHeight) {
                                top = rect.top - mh - 8;
                            }
                            left = Math.max(8, Math.min(left, window.innerWidth - mw - 8));
                            $data.top = top;
                            $data.left = left;
                        });
                    "
                        class="text-gray-600 hover:text-black dark:text-white focus:outline-none cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v.01M12 12v.01M12 18v.01" />
                        </svg>
                    </button>

                    <div x-ref="menu" x-cloak x-show="open" @click.away="open=false"
                        class="fixed z-50 flex flex-col gap-2 p-2 rounded-lg shadow-lg bg-white dark:bg-gray-800 dark:border dark:border-gray-700"
                        :style="`top:${top}px; left:${left}px`" x-transition.opacity>
                        <!-- ALTA -->
                        <a wire:click="mostrarFormularioAlta({{ $jugador->id }})"
                            class="cursor-pointer flex items-center gap-2 hover:underline">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-badge-plus">
                                <path
                                    d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z" />
                                <line x1="12" x2="12" y1="8" y2="16" />
                                <line x1="8" x2="16" y1="12" y2="12" />
                            </svg>
                            <span>Alta</span>
                        </a>
                        <!-- BAJA -->
                        <a wire:click="$dispatch('confirmar-baja', { id: {{ $jugador->id }} })"
                            class="cursor-pointer flex items-center gap-2 hover:underline">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <span>Baja</span>
                        </a>
                        <!-- HISTORIAL -->
                        <a wire:click="verHistorial({{ $jugador->id }})"
                            class="cursor-pointer flex items-center gap-2 hover:underline">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <span>Historial</span>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center p-4 text-sm dark:text-gray-300">No hay jugadores</p>
        @endforelse
    </div>


    {{-- Sección de alta --}}
    @if ($mostrarAlta)
        <div class="mt-4 p-4 bg-gray-100 border rounded">
            <h3 class="text-base font-bold mb-2">Seleccionar equipo para alta</h3>
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                <select wire:model="equipoSeleccionado"
                    class="bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-60 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    >
                    <option value="">-- Selecciona un equipo --</option>
                    @foreach ($equipos->sortBy('nombre') as $equipo)
                        <option value="{{ $equipo->id }}">
                            {{ strtoupper($equipo->nombre) }}
                        </option>
                    @endforeach
                </select>

                <button wire:click="darDeAlta"
                    class="cursor-pointer inline-flex items-center gap-2 mt-4  bg-blue-950 hover:bg-blue-800 text-white px-4 py-2 rounded ">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-save-icon lucide-save">
                        <path
                            d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
                        <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7" />
                        <path d="M7 3v4a1 1 0 0 0 1 1h7" />
                    </svg> <span>Guardar</span></button>
                <a href="{{ route('altas-bajas.index') }}"
                    class=" cursor-pointer inline-flex items-center gap-2 mt-4  bg-blue-950 hover:bg-blue-800 text-white px-4 py-2 rounded ">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span>Volver</span>
                </a>
            </div>
        </div>
    @endif

    {{-- Historial --}}
    @if (count($historial))
        <div class="mt-4 overflow-x-auto bg-gray-100">
            <h3
                class="bg-blue-900 text-white p-2 shadow-md rounded flex justify-between items-center relative font-semibold">
                Historial de equipos</h3>
            <table class=" w-full border table-auto text-sm shadow-md">
                <thead class=" bg-gray-700 dark:bg-gray-500 text-gray-100 dark:text-gray-900 shadow-md">
                    <tr class="">
                        <th class="p-2">Equipo</th>
                        <th class="p-2">Fecha de Baja</th>
                        <th class="p-2">Fecha de Alta</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($historial as $h)
                        <tr
                            class="border-t  odd:bg-gray-200 odd:text-gray-800 font-semibold even:text-gray-800 even:bg-gray-300 dark:odd:bg-gray-900 dark:even:bg-gray-800">
                            <td class="p-2  semi-bold">{{ strtoupper($h->equipo) }}</td>
                            <td class="p-2 dark:text-gray-100 text-center">
                                {{ $h->fecha_baja ? \Carbon\Carbon::parse($h->fecha_baja)->format('d/m/Y') : 'ACTUALMENTE ACTIVO' }}
                            </td>
                            <td class="p-2 dark:text-gray-100 text-center">
                                {{ \Carbon\Carbon::parse($h->fecha_alta)->format('d/m/Y') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif


    @push('js')
        <script>
            document.addEventListener('livewire:initialized', () => {

                Livewire.on('equipo-existe', () => {
                    Swal.fire(
                        'Error',
                        'El jugador ya tiene equipo asignado, si quiere cambiar de equipo debera dar de baja primero.',
                        'warning'
                    );
                });

                Livewire.on('darDeAlta', () => {
                    Swal.fire(
                        'Ok la Alta!',
                        'El jugador se dio de alta en el equipo correctamente.',
                        'success'
                    );
                });

                Livewire.on('confirmar-baja', ({
                    id
                }) => {
                    Swal.fire({
                        title: 'CUIDADO...',
                        text: "¿Estás seguro de dar de Baja al jugador del equipo?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, dar la Baja'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Llamás al método del componente Livewire y le pasás el parámetro
                            Livewire.dispatch('dar-de-baja', {
                                jugadorId: id
                            });
                        }
                    });
                });
                //========================================
                Livewire.on('Baja', () => {
                    Swal.fire(
                        'Ok la Baja!',
                        'El jugador se dio de baja en el equipo correctamente.',
                        'success'
                    );
                });
            });
        </script>
    @endpush
</div>
