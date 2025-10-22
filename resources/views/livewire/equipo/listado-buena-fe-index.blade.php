<div class="relative overflow-x-auto shadow-md sm:rounded-lg">

    <div class="bg-blue-900 text-white p-2 shadow-md rounded flex justify-between items-center relative z-10"">
        <h2 class=" font-semibold text-xl text-gray-100 leading-tight">
        {{ __('Listado de Buena Fé') }}
        </h2>

    </div>
    <div class="hidden sm:block">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-100 uppercase bg-gray-500 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Campeonato
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Formato
                    </th>
                    <th scope="col" class="px-6 py-3 hidden sm:table-cell">
                        Cant. Equipos
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Categoria
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($campeonatos as $campeonato)
                <tr x-data="{ open: false, top: 0, left: 0 }" @click=" 
        open = !open;
        $nextTick(() => {
            const btn = $refs.trigger;
            const rect = btn.getBoundingClientRect();
            const menu = $refs.menu;

            // Mostrar temporalmente para medir
            const prevDisplay = menu.style.display;
            const prevVisibility = menu.style.visibility;
            menu.style.visibility = 'hidden';
            menu.style.display = 'block';
            const mh = menu.offsetHeight;
            const mw = menu.offsetWidth;
            menu.style.display = prevDisplay;
            menu.style.visibility = prevVisibility;

            // Posicionar
            let top = rect.bottom + 8;
            let left = rect.right - mw;
            if (top + mh > window.innerHeight) top = rect.top - mh - 8;
            left = Math.max(8, Math.min(left, window.innerWidth - mw - 8));

            $data.top = top;
            $data.left = left;
        });
    " @click.away="open = false" @keydown.escape.window="open=false"
                    class="cursor-pointer bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">

                    <!-- Nombre -->
                    <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <div class="text-base font-semibold">{{ strtoupper($campeonato->nombre) }}</div>
                    </th>

                    <!-- Formato -->
                    <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <div class="font-normal text-gray-500 dark:text-white">
                            @if ($campeonato->formato === 'todos_contra_todos')
                            Todos contra Todos
                            @else
                            {{ ucfirst($campeonato->formato) }}
                            @endif
                        </div>
                    </th>

                    <!-- Cantidad equipos -->
                    <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        @if ($campeonato->formato === 'todos_contra_todos')
                        @foreach ($campeonato->grupos as $grupo)
                        <div class="font-normal text-gray-500 dark:text-white">{{ $grupo->cantidad_equipos }}</div>
                        @endforeach
                        @else
                        <div class="font-normal text-gray-500 dark:text-white">
                            {{ $campeonato->cantidad_equipos_grupo }} x Grupo
                        </div>
                        @endif
                    </th>

                    <!-- Categoría -->
                    <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <div class="font-normal text-gray-500 dark:text-white">{{
                            ucfirst($campeonato->categoria->nombre) }}</div>
                    </th>

                    <!-- BOTÓN Y MENÚ -->
                    <td class="px-6 py-4 text-right relative">
                        <button x-ref="trigger" @click.stop="
                open = !open;
                $nextTick(() => { /* misma lógica de posición */ });
            " class="text-gray-600 hover:text-black focus:outline-none dark:text-white cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v.01M12 12v.01M12 18v.01" />
                            </svg>
                        </button>

                        <div x-ref="menu" x-cloak x-show="open" @click.away="open=false"
                            class="fixed z-50 flex flex-col gap-2 p-2 rounded-lg shadow-lg bg-gray-100 dark:bg-gray-800 dark:border dark:border-gray-700"
                            :style="`top:${top}px; left:${left}px`" x-transition.opacity>

                            <a href="{{ route('listado-buena-fe.ver1', $campeonato->id) }}"
                                class="flex items-center gap-2 hover:underline text-sm dark:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                Ver
                            </a>
                            <a href="{{ route('listado-buena-fe.crear', $campeonato->id) }}"
                                class="flex items-center gap-2 hover:underline text-sm dark:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-badge-plus-icon lucide-badge-plus">
                                    <path
                                        d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z" />
                                    <line x1="12" x2="12" y1="8" y2="16" />
                                    <line x1="8" x2="16" y1="12" y2="12" />
                                </svg>
                                Crear
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- ------------MOVIL--------------------->
    <div class="grid gap-4 md:hidden">
        @foreach ($campeonatos as $campeonato)
        <div x-data="{ open: false, top: 0, left: 0 }" @click="
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
            menu.style.display = prevDisplay;
            menu.style.visibility = prevVisibility;

            // Posición por defecto: debajo del botón
            let top = rect.bottom + 8;
            let left = rect.right - mw;

            // Si no entra abajo, abrir arriba
            if (top + mh > window.innerHeight) {
                top = rect.top - mh - 8;
            }

            // Limitar a los bordes laterales
            left = Math.max(8, Math.min(left, window.innerWidth - mw - 8));

            $data.top = top;
            $data.left = left;
        });
    " @click.away="open = false" @keydown.escape.window="open=false" @scroll.window="open=false"
            class="cursor-pointer bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-md p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
            {{-- Nombre del campeonato --}}
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Torneo: {{ ucwords($campeonato->nombre) }}
                </h2>

                {{-- Botón de menú móvil --}}
                <button x-ref="trigger" @click.stop="
                open = !open;
                $nextTick(() => { /* misma lógica de posicionamiento */ });
            " class="text-gray-600 hover:text-black focus:outline-none dark:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v.01M12 12v.01M12 18v.01" />
                    </svg>
                </button>

                {{-- MENÚ DESPLEGABLE --}}
                <div x-ref="menu" x-cloak x-show="open" @click.away="open=false"
                    class="fixed z-50 flex flex-col gap-2 p-4 rounded-lg shadow-lg bg-gray-100 dark:bg-gray-700 dark:border dark:border-gray-700"
                    :style="`top:${top}px; left:${left}px`" x-transition.opacity>
                    <a href="{{ route('listado-buena-fe.ver1', $campeonato->id) }}"
                        class="flex items-center gap-2 hover:underline text-sm dark:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        Ver
                    </a>

                    <a href="{{ route('listado-buena-fe.crear', $campeonato->id) }}"
                        class="flex items-center gap-2 hover:underline text-sm dark:text-white">
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
                </div>
            </div>

            {{-- Detalles del campeonato --}}
            <div class="space-y-1 text-gray-700 dark:text-gray-300">
                <div>{{ ucfirst($campeonato->categoria->nombre) }}</div>
            </div>
        </div>

        @endforeach
    </div>


</div>



</div>