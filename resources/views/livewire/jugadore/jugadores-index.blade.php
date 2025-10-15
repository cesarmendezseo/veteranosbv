<div>
    <div>
        <div class="bg-blue-900 text-white p-2 shadow-md rounded flex justify-between items-center relative z-10"">
            <h2 class=" font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Listado de Jugadores') }}
            </h2>

            <div class="flex items-center space-x-4 m-2">
                <a href="{{ route('jugadores.crear') }}"
                    class=" gap-4 text-sm font-medium text-white inline-flex items-center hover:underline focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-center dark:hover:underline dark:focus:ring-blue-800 shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-badge-plus-icon lucide-badge-plus">
                        <path
                            d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z" />
                        <line x1="12" x2="12" y1="8" y2="16" />
                        <line x1="8" x2="16" y1="12" y2="12" />
                    </svg>
                    Crear
                </a>

                <div class=" hidden md:table">
                    <input type="text" wire:model.defer="search" wire:keydown.enter="buscar"
                        placeholder="Buscar por nombre, apellido o DNI" class="border rounded  w-full " />
                </div>
            </div>
        </div>

        <div>
            <div>
                <table class="hidden md:table w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-100 uppercase bg-gray-500 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Logo
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Nombre y Apellido
                            </th>

                            <th scope="col" class="px-6 py-3 hidden sm:table-cell">
                                Activo
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                Acción
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jugadores as $jugador)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                @if ($jugador->foto)
                                <div class="mb-4 flex justify-center">
                                    <div
                                        class="w-14 h-14 rounded-full border-4 border-gray-300 shadow-lg overflow-hidden">
                                        <img src="{{ asset('storage/' . $jugador->foto) }}"
                                            alt="Logo {{ $jugador->nombre }}" class="w-full h-full object-cover" />
                                    </div>
                                </div>
                                @else
                                <div
                                    class="w-14 h-14 rounded-full border-4 border-gray-300 shadow-lg overflow-hidden flex items-center justify-center">
                                    <div
                                        class="flex animate-pulse items-center justify-center w-full h-48 bg-gray-300 rounded-sm sm:w-96 dark:bg-gray-700">
                                        <svg class="w-10 h-10 text-gray-200 dark:text-gray-600" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                                            <path
                                                d="M18 0H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm-5.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm4.376 10.481A1 1 0 0 1 16 15H4a1 1 0 0 1-.895-1.447l3.5-7A1 1 0 0 1 7.468 6a.965.965 0 0 1 .9.5l2.775 4.757 1.546-1.887a1 1 0 0 1 1.618.1l2.541 4a1 1 0 0 1 .028 1.011Z" />
                                        </svg>
                                    </div>
                                </div>
                                @endif
                            </th>
                            <th scope=" row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <div class="text-base font-semibold">{{ strToupper($jugador->apellido) }},
                                    {{ strToupper($jugador->nombre) }}</div>
                                @adminOrCan()
                                <div class="font-normal text-gray-500">Dni: {{ $jugador->documento }} </div>
                                @endadminOrCan
                            </th>


                            <th class="text-center align-center px-6 py-4">
                                <p class="flex items-center">
                                    @if ($jugador->is_active)
                                    <span
                                        class="inline-flex w-6 h-6 items-center justify-center rounded-full bg-green-100 text-green-800 dark:bg-green-300 dark:text-green-900">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </span>
                                    @else
                                    <span
                                        class="inline-flex items-center w-6 h-6 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>

                                    </span>
                                    @endif
                                </p>
                            </th>

                            {{-- /////////////////*****************///////////////////// --}}
                            <td class="px-6 py-4 text-right">
                                <div x-data="{ open: false, coords: { top: 0, left: 0 } }" class="inline-block">
                                    <!-- Botón -->
                                    <button @click="
                                            open = !open;
                                            if(open){
                                                $nextTick(() => {
                                                    const rect = $refs.trigger.getBoundingClientRect();
                                                    coords.top = rect.bottom + window.scrollY;
                                                    coords.left = rect.right + window.scrollX - 192; // 192px = w-48
                                                })
                                            }
                                        " x-ref="trigger" class="px-4 py-2">
                                        <!-- Icono menú -->
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-10 h-10 cursor-pointer text-gray-800 dark:text-white" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 6v.01M12 12v.01M12 18v.01" />
                                        </svg>
                                    </button>

                                    <!-- Dropdown -->
                                    <div x-show="open" x-cloak
                                        class="fixed w-48 bg-white dark:bg-gray-700 rounded shadow-lg z-[9999]"
                                        :style="`top:${coords.top}px; left:${coords.left}px`"
                                        @click.outside="open = false">

                                        <ul class="flex flex-col">
                                            {{-- VER --}}
                                            <li>
                                                <a wire:click="verJugador({{ $jugador->id }})" class="cursor-pointer flex items-center w-full px-4 py-2 
                                                                        text-gray-800 dark:text-white 
                                                                        hover:bg-gray-100 dark:hover:bg-gray-600 
                                                                        hover:underline font-medium rounded-lg mb-2"
                                                    title="Ver">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-5 h-5 mr-2 text-gray-800 dark:text-white">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>
                                                    <span>Ver</span>
                                                </a>
                                            </li>

                                            {{-- EDITAR --}}
                                            <li>
                                                @adminOrCan('comision')
                                                <a href="{{ route('jugadores.editar', $jugador->id) }}" class="flex items-center w-full px-4 py-2 
                                                    text-gray-800 dark:text-white 
                                                     hover:bg-gray-100 dark:hover:bg-gray-600 
                                                       hover:underline font-medium rounded-lg mb-2" title="Editar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="h-6 w-6 text-gray-800 dark:text-white">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                    </svg>
                                                    <span>Editar</span>
                                                </a>
                                                @endadminOrCan
                                            </li>

                                            {{-- BORRAR --}}
                                            <li>
                                                @adminOrCan('comision')
                                                <a wire:click="borrar({{ $jugador->id }})" class="flex items-center w-full px-4 py-2 
                                                    text-gray-800 dark:text-white 
                                                    hover:bg-gray-100 dark:hover:bg-gray-600 
                                                    hover:underline font-medium rounded-lg mb-2 cursor-pointer"
                                                    title="Borrar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="h-6 w-6 text-gray-800 dark:text-white">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>
                                                    <span>Borrar</span>
                                                </a>
                                                @endadminOrCan
                                            </li>

                                            {{-- FOTO --}}
                                            <li>
                                                <a href="{{ route('jugadores.foto.upload', $jugador->id) }}" class="flex items-center w-full px-4 py-2 
                                                    text-gray-800 dark:text-white 
                                                    hover:bg-gray-100 dark:hover:bg-gray-600 
                                                    hover:underline font-medium rounded-lg mb-2" title="Subir foto">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-5 h-5 mr-2 text-gray-800 dark:text-white">
                                                        <path
                                                            d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z" />
                                                        <circle cx="12" cy="13" r="3" />
                                                    </svg>
                                                    <span>Foto</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>


                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:hidden">
            <div class="mt-2">
                <input type="text" wire:model.defer="search" wire:keydown.enter="buscar"
                    placeholder="Buscar por nombre, apellido o DNI"
                    class="border border-gray-500 rounded px-3 py-2.5 w-full mb-2" />
            </div>
            @foreach ($jugadores as $jugador)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex justify-end">
                    <div x-data="{ open: false, dropUp: false, alignRight: false }"
                        class="relative inline-block text-left">
                        <button x-ref="trigger" @click="
                                        dropUp = false;
                                                alignRight = false;
                                                open = !open;
                                                $nextTick(() => {
                                                    const menu = $refs.menu;
                                                    const rect = menu.getBoundingClientRect();
                                
                                                    // Ajustar vertical
                                                    if (rect.bottom > window.innerHeight) dropUp = true;
                                
                                                    // Ajustar horizontal
                                                    const spaceRight = window.innerWidth - rect.left;
                                                    if (spaceRight < rect.width + 8) alignRight = true;
                                                });
                                                "
                            class="px-4 py-2 text-gray-800 dark:text-gray-100 rounded cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v.01M12 12v.01M12 18v.01" />
                            </svg>
                        </button>
                        <div x-show="open" x-ref="menu" x-transition x-cloak :class="[dropUp ? 'bottom-full mb-2' : 'top-full mt-2', alignRight ?
                                    'right-0 left-auto' : 'left-0'
                                ]"
                            class="absolute w-48 bg-white border dark:bg-gray-700 dark:text-gray-100 rounded shadow-lg z-50 overflow-auto"
                            style="max-height: calc(100vh - 4rem);" @click.outside="open = false">
                            {{-- VER --}}
                            <a wire:click="verJugador({{ $jugador->id }})"
                                class="cursor-pointer flex items-center w-full px-4 py-2 hover:bg-gray-100 hover:underline font-medium rounded-lg mb-2"
                                title="Ver">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                <span>Ver</span>
                            </a>

                            {{-- EDITAR --}}
                            @adminOrCan('comision')
                            <a href="{{ route('jugadores.editar', $jugador->id) }}"
                                class="flex items-center w-full px-4 py-2 hover:bg-gray-100 hover:underline font-medium rounded-lg mb-2"
                                title="Editar">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                                <span>Editar</span>
                            </a>
                            @endadminOrCan

                            {{-- BORRAR --}}
                            @adminOrCan()
                            <a wire:click="borrar({{ $jugador->id }})"
                                class="flex items-center w-full px-4 py-2 hover:bg-gray-100 hover:underline font-medium rounded-lg mb-2 cursor-pointer"
                                title="Borrar">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                                <span>Borrar</span>
                            </a>
                            @endadminOrCan

                            {{-- FOTO --}}
                            <a href="{{ route('jugadores.foto.upload', $jugador->id) }}"
                                class="flex items-center w-full px-4 py-2 hover:bg-gray-100 hover:underline font-medium rounded-lg mb-2"
                                title="Subir foto">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                    <path
                                        d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z" />
                                    <circle cx="12" cy="13" r="3" />
                                </svg>
                                <span>Foto</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="flex justify-center mb-4">
                    @if ($jugador->foto)
                    <div class="w-20 h-20 rounded-full border-4 border-gray-300 shadow-lg overflow-hidden">
                        <img src="{{ asset('storage/' . $jugador->foto) }}" alt="Logo {{ $jugador->nombre }}"
                            class="w-full h-full object-cover" />
                    </div>
                    @else
                    <div
                        class="w-20 h-20 rounded-full border-4 border-gray-300 shadow-lg overflow-hidden flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-400 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 18">
                            <path
                                d="M18 0H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm-5.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Z" />
                        </svg>
                    </div>
                    @endif
                </div>
                <div class="text-center">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        {{ strtoupper($jugador->apellido) }}, {{ strtoupper($jugador->nombre) }}
                    </h3>
                    @adminOrCan()
                    <p class="text-gray-500 dark:text-gray-400">DNI: {{ $jugador->documento }}</p>
                    @endadminOrCan
                    {{-- <p class="text-gray-600 dark:text-gray-300 font-semibold">
                        {{ strtoupper($jugador->equipo->nombre) }}</p> --}}
                </div>
                <div class="flex justify-center my-2">
                    @if ($jugador->is_active)
                    <span
                        class="inline-flex w-6 h-6 items-center justify-center rounded-full bg-green-100 text-green-800">
                        ✓
                    </span>
                    @else
                    <span class="inline-flex w-6 h-6 items-center justify-center rounded-full bg-red-100 text-red-800">
                        ✗
                    </span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">
            {{ $jugadores->links() }}
        </div>
        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>
        <div x-data="{ show: false }" x-show="show" x-on:static-modal.window="show = true" x-cloak
            class="shadow-2xs fixed inset-0 z-50 flex items-center justify-center bg-[rgba(0,0,0,0.5)] p-4 sm:p-6">
            <div class="bg-blue-900 dark:bg-gray-800 rounded-xl shadow-2xl max-w-xl w-full p-6 sm:p-8 relative transform transition-all scale-100 opacity-100 ease-out duration-300
                            max-h-[90vh] overflow-y-auto">
                <button @click="show = false"
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-200 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full p-1">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <h2
                    class="text-2xl font-extrabold mb-6 text-white dark:text-gray-100 text-center border-b pb-3 border-gray-200 dark:border-gray-700">
                    Detalles del Jugador
                </h2>

                @if ($jugadorSeleccionado)
                <div class="grid grid-cols-1 md:grid-cols-1 gap-y-4 gap-x-6 text-gray-700 dark:text-[#efb810]">
                    <div
                        class="grid grid-cols-1 md:grid-cols-1 gap-y-4 gap-x-6 text-[#efb810] font-semibold dark:text-gray-300">
                        <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                            <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Nombre :</strong>
                            {{ ucwords(strtolower($jugadorSeleccionado->apellido)) }}
                            {{ ucwords(strtolower($jugadorSeleccionado->nombre)) }}
                        </p>
                        @adminOrCan()
                        <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                            <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Fecha de Nac:</strong>
                            {{ ucwords(strtolower($jugadorSeleccionado->fecha_nac)) }}
                        </p>
                        @endadminOrCan
                        <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                            <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Edad:</strong>
                            {{ $jugadorSeleccionado->edad }} años
                        </p>
                        <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                            <strong class="font-semibold text-gray-400 dark:text-[#efb810]">N° Socio:</strong>
                            {{ ucwords(strtolower($jugadorSeleccionado->num_socio)) }}
                        </p>
                        <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                            <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Teléfono:</strong>
                            {{ ucwords(strtolower($jugadorSeleccionado->telefono)) }}
                        </p>
                        <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                            <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Email:</strong>
                            {{ $jugadorSeleccionado->email }}
                        </p>
                        <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                            <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Ciudad:</strong>
                            {{ ucwords(strtolower($jugadorSeleccionado->ciudad)) }}
                        </p>
                        <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                            <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Provincia:</strong>
                            {{ ucwords(strtolower($jugadorSeleccionado->provincia)) }}
                        </p>
                        <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                            <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Cod Pos:</strong>
                            {{ ucwords(strtolower($jugadorSeleccionado->cod_pos)) }}
                        </p>

                        {{-- <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                            <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Equipo:</strong>
                            {{ ucwords(strtolower($jugadorSeleccionado->equipo->nombre ?? 'Sin equipo')) }}
                        </p> --}}
                    </div>
                </div>
                @else
                <p class="text-center text-gray-600 dark:text-gray-400 py-4">No se ha seleccionado ningún jugador
                    para mostrar.</p>
                @endif
            </div>
        </div>
        @push('js')
        <script>
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('confirmar-baja', ({
                    id
                }) => {
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "¡No podrás revertir esto!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, ¡bórralo!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Livewire.emit('borrarJugador', id);
                            Swal.fire(
                                '¡Borrado!',
                                'El jugador ha sido borrado.',
                                'success'
                            )
                        }
                    })
                });
            });
        </script>
        @endpush
    </div>
</div>