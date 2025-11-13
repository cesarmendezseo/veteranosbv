<div class="relative overflow-x-auto shadow-md sm:rounded-lg">

    <div class="bg-blue-900 text-white p-2 shadow-md rounded flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Listado de Buena Fé') }}
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
                        <div x-data="{ open: false }" class="relative inline-block text-left">
                            <button @click="open = !open"
                                class="p-2 rounded-full  hover:bg-blue-200 text-black cursor-pointer shadow-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6v.01M12 12v.01M12 18v.01" />
                                </svg>
                            </button>

                            <div x-show="open" @click.outside="open = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-40 origin-top-right bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                                <ul class="py-1 text-gray-700 text-sm">
                                    <li>
                                        <a href="{{ route('listado-buena-fe.ver1', $campeonato->id) }}"
                                            class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 dark:text-gray-900">
                                            Ver
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('listado-buena-fe.crear', $campeonato->id) }}"
                                            class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 dark:text-gray-900">
                                            Crear
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
                        class="p-2 rounded-full  hover:bg-blue-200 text-black shadow-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v.01M12 12v.01M12 18v.01" />
                        </svg>
                    </button>

                    <div x-show="open" @click.outside="open = false" x-transition
                        class="absolute right-0 mt-2 w-40 origin-top-right bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                        <ul class="py-1 text-gray-700 text-sm">
                            <li>
                                <a href="{{ route('listado-buena-fe.ver1', $campeonato->id) }}"
                                    class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100">
                                    Ver
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('listado-buena-fe.crear', $campeonato->id) }}"
                                    class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100">
                                    Crear
                                </a>
                            </li>
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