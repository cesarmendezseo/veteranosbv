<div class="relative overflow-x-auto shadow-md sm:rounded-lg">

    <div class="bg-blue-900 text-white p-2 shadow-md rounded flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Listado de Buena Fé Index ') }}
        </h2>
    </div>
    <div class="mt-2">
        <input type="text" wire:model.live="search" placeholder="Buscar por nombre del campeonato "
            class="hidden md:table border rounded px-3 py-2.5 w-full mb-2" />
    </div>

    {{-- VISTA DE ESCRITORIO --}}
    <div class="hidden sm:block">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-100 uppercase bg-gray-500 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3">Campeonato</th>
                    <th class="px-6 py-3">Formato</th>
                    <th class="px-6 py-3">Cant. Equipos</th>
                    <th class="px-6 py-3">Categoría</th>
                    <th class="px-6 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($campeonatos as $campeonato)
                <tr
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                        {{ strtoupper($campeonato->nombre) }}
                    </td>
                    <td class="px-6 py-4 text-gray-700 dark:text-white">
                        {{ $campeonato->formato === 'todos_contra_todos' ? 'Todos contra Todos' :
                        ucfirst($campeonato->formato) }}
                    </td>
                    <td class="px-6 py-4 text-gray-700 dark:text-white">
                        @if ($campeonato->formato === 'todos_contra_todos')
                        @foreach ($campeonato->grupos as $grupo)
                        <div>{{ $grupo->cantidad_equipos }}</div>
                        @endforeach
                        @else
                        <div>{{ $campeonato->cantidad_equipos_grupo }} x Grupo</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-700 dark:text-white">
                        {{ ucfirst($campeonato->categoria->nombre) }}
                    </td>

                    {{-- MENÚ DE OPCIONES --}}
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            {{-- VER --}}
                            <a href="{{ route('listado-buena-fe.ver1', $campeonato->id) }}" title="Ver"
                                class="cursor-pointer text-blue-600 hover:text-white border border-blue-600 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-xs px-2 py-1 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>

                            </a>

                            {{-- CREAR --}}

                            @adminOrCan('administrador|comision')
                            <a href="{{ route('listado-buena-fe.crear', $campeonato->id) }}" title="Crear"
                                class="cursor-pointer text-green-600 hover:text-white border border-green-600 hover:bg-green-600 focus:ring-4 focus:outline-none focus:ring-green-300 rounded-lg text-xs px-2 py-1 dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </a>
                            @endadminOrCan

                        </div>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- VISTA MÓVIL --}}
    <div class="grid gap-4 md:hidden p-2">
        @foreach ($campeonatos as $campeonato)
        <div class="bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-lg shadow-md p-4">
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ ucwords($campeonato->nombre) }}
                </h2>

                <div x-data="{ open: false }" class="relative inline-block text-left">
                    <button @click="open = !open"
                        class="p-2 rounded-full  hover:bg-blue-200 text-black dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v.01M12 12v.01M12 18v.01" />
                        </svg>
                    </button>

                    <div x-show="open" @click.outside="open = false" x-transition
                        class="absolute right-0 mt-2 w-40 origin-top-right bg-white border border-gray-200 dark:border-gray-800 rounded-lg shadow-lg z-50">
                        <ul class="py-1 text-sm bg-white text-black dark:bg-gray-800 ">
                            <li>
                                <a href="{{ route('listado-buena-fe.ver1', $campeonato->id) }}"
                                    class="flex items-center gap-2 px-4 py-2 hover:bg-gray-400  dark:text-white dark:hover:text-gray-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        class="w-5 h-5 mr-2 text-gray-800 dark:text-white">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    Ver
                                </a>
                            </li>
                            @adminOrCan('administrador|comision')
                            <li>
                                <a href="{{ route('listado-buena-fe.crear', $campeonato->id) }}"
                                    class="flex items-center gap-2 px-4 py-2 hover:bg-gray-400  dark:text-white dark:hover:text-gray-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-badge-plus-icon lucide-badge-plus">
                                        <path
                                            d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z" />
                                        <line x1="12" x2="12" y1="8" y2="16" />
                                        <line x1="8" x2="16" y1="12" y2="12" />
                                    </svg>
                                    <span class="ml-2"> Crear</span>
                                </a>
                            </li>
                            @endadminOrCan
                        </ul>
                    </div>
                </div>
            </div>

            <div class="text-gray-700 dark:text-gray-300">
                <div><strong>Formato:</strong> {{ ucfirst($campeonato->formato) }}</div>
                <div><strong>Equipos:</strong>
                    @if ($campeonato->formato === 'todos_contra_todos')
                    @foreach ($campeonato->grupos as $grupo)
                    {{ $grupo->cantidad_equipos }}
                    @endforeach
                    @else
                    {{ $campeonato->cantidad_equipos_grupo }} x Grupo
                    @endif
                </div>
                <div><strong>Categoría:</strong> {{ ucfirst($campeonato->categoria->nombre) }}</div>
            </div>
        </div>
        @endforeach
    </div>
    {{ $campeonatos->links() }}
</div>