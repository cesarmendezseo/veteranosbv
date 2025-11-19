<div class="relative overflow-x-auto shadow-md sm:rounded-lg">

    <div class="bg-blue-900 text-white p-4 shadow-md rounded flex justify-between items-center relative z-10"">
        <h2 class=" font-semibold text-xl text-gray-100 leading-tight">
        {{ __('Finalizar Campeoanto y dar de baja todos los jugadores') }}
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
                <tr
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">

                    <th scope=" row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <div class="text-base font-semibold">{{ strtoupper($campeonato->nombre) }}</div>
                    </th>
                    <th scope=" row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <div class="font-normal text-gray-500 dark:text-white">
                            @if ($campeonato->formato === 'todos_contra_todos')
                            Todos contra Todos
                            @else
                            {{ ucfirst($campeonato->formato) }}
                            @endif
                        </div>
                    </th>
                    <th scope=" row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        @if ($campeonato->formato === 'grupos')
                        <div class="font-normal text-gray-500 dark:text-white"> {{ $campeonato->cantidad_equipos_grupo
                            }} x Grupo </div>
                        @else
                        <div class="font-normal text-gray-500 dark:text-white"> {{ $campeonato->total_equipos }} </div>
                        @endif
                    </th>

                    <th scope=" row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <div class="font-normal text-gray-500 dark:text-white"> {{
                            strtoupper($campeonato->categoria->nombre) }}</div>
                    </th>


                    <!----------- BOTONES DE ACCION  ------------------------>
                    <td class="px-6 py-4 text-right">
                        <!-- Para pantallas medianas en adelante -->
                        <div x-data="{ open: false, top: 0, left: 0 }" class="relative"
                            @keydown.escape.window="open=false" @scroll.window="open=false">
                            <button x-ref="trigger" @click="
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
                        " class="text-gray-600 hover:text-black focus:outline-none dark:text-white cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6v.01M12 12v.01M12 18v.01" />
                                </svg>
                            </button>

                            <div x-ref="menu" x-cloak x-show="open" @click.away="open=false"
                                class="fixed z-50 flex flex-col gap-2 p-2 rounded-lg shadow-lg bg-gray-100 dark:bg-gray-800 dark:border dark:border-gray-700"
                                :style="`top:${top}px; left:${left}px`" x-transition.opacity>

                                @adminOrCan('comision')

                                <button wire:click="finalizarCampeonato({{ $campeonato->id }})"
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 cursor-pointer">
                                    Finalizar Campeonato
                                </button>

                                @endadminOrCan
                            </div>
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
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-md p-4">

            {{-- Nombre del campeonato --}}
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Torneo: {{ ucwords($campeonato->nombre) }}
                </h2>

                {{-- Menú acciones móvil (abre hacia arriba si no hay espacio) --}}
                <div x-data="{ open: false, top: 0, left: 0 }" class="relative" @keydown.escape.window="open=false"
                    @scroll.window="open=false">
                    <button x-ref="trigger" @click="
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
                        " class="text-gray-600 hover:text-black focus:outline-none dark:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v.01M12 12v.01M12 18v.01" />
                        </svg>
                    </button>

                    <div x-ref="menu" x-cloak x-show="open" @click.away="open=false"
                        class="fixed z-50 flex flex-col gap-2 p-4 rounded-lg shadow-lg bg-gray-100 dark:bg-gray-700 dark:border dark:border-gray-700"
                        :style="`top:${top}px; left:${left}px`" x-transition.opacity>

                        <!-- CREAR -->
                        @adminOrCan('comision')
                        <button wire:click="finalizarCampeonato"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Finalizar Campeonato
                        </button>
                        @endadminOrCan
                    </div>
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