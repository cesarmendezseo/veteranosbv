<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Listado de Buena Fé') }}
        </h2>
        <div class="flex items-center space-x-4">

            @adminOrCan('comision|imprimir')
                @if ($equipoElegido)
                    <button wire:click="exportarJugadores" title="Imprimir Listado"
                        class="cursor-pointer flex items-center px-4 py-2 hover:underline text-white rounded hover:bg-blue-700 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="mr-2 lucide lucide-printer-icon lucide-printer">
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                            <path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6" />
                            <rect x="6" y="14" width="12" height="8" rx="1" />
                        </svg>

                    </button>
                @endif
            @endadminOrCan

            <button wire:click="actualizarSanciones"
                class="cursor-pointer flex  px-4 py-2 hover:underline text-white rounded hover:bg-blue-700 text-sm">
                {{-- Added text-sm --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="mr-1 lucide lucide-refresh-ccw-dot-icon lucide-refresh-ccw-dot">
                    {{-- Changed mr-2 to mr-1 to save space --}}
                    <path d="M3 2v6h6" />
                    <path d="M21 12A9 9 0 0 0 6 5.3L3 8" />
                    <path d="M21 22v-6h-6" />
                    <path d="M3 12a9 9 0 0 0 15 6.7l3-2.7" />
                    <circle cx="12" cy="12" r="1" />
                </svg>

            </button>
        </div>
        <!-- escritorio -->
        <div class="hidden md:flex">
            @adminOrCan('comision|imprimir')
                @if ($equipoElegido)
                    <button wire:click="exportarJugadores" title="Imprimir Listado"
                        class="cursor-pointer flex items-center px-4 py-2 hover:underline text-white rounded hover:bg-blue-700 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="mr-2 lucide lucide-printer-icon lucide-printer">
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                            <path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6" />
                            <rect x="6" y="14" width="12" height="8" rx="1" />
                        </svg>
                        Imprimir
                    </button>
                @endif
            @endadminOrCan

            <button wire:click="actualizarSanciones"
                class="cursor-pointer flex  px-4 py-2 hover:underline text-white rounded hover:bg-blue-700 text-sm">
                {{-- Added text-sm --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="mr-1 lucide lucide-refresh-ccw-dot-icon lucide-refresh-ccw-dot">
                    {{-- Changed mr-2 to mr-1 to save space --}}
                    <path d="M3 2v6h6" />
                    <path d="M21 12A9 9 0 0 0 6 5.3L3 8" />
                    <path d="M21 22v-6h-6" />
                    <path d="M3 12a9 9 0 0 0 15 6.7l3-2.7" />
                    <circle cx="12" cy="12" r="1" />
                </svg>
                Actualizar Sanciones
            </button>
        </div>

    </x-slot>
    <flux:separator />
    <div class="bg-gray-500 p-2 rounded mb-4 mt-2 grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-4">
        <div>
            <select wire:model.live="equiposSeleccionado"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">-- Selecciona un Equipo --</option>
                @foreach ($equiposDelGrupo as $campeonato)
                    <option value="{{ $campeonato->id }}">{{ strtoupper($campeonato->nombre) }}

                    </option>
                @endforeach
            </select>
        </div>

        <div {{-- class="hidden sm:block" --}}>
            {{-- Fecha --}}
            <select wire:model.live="fecha"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">-- Selecciona un Fecha --</option>
                @foreach ($equiposDelGrupo as $campeonato)
                    <option value="{{ $loop->iteration }}">{{ $loop->iteration }}

                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <!-- -----------TABLA ---------------->
    <div class="rounded-lg">
        <div class="overflow-x-auto"> {{-- Added for horizontal scrolling --}}
            <div class="overflow-y-auto max-h-[600px]">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-200 uppercase bg-gray-500 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-3 py-3 text-xs md:text-sm hidden sm:table-cell">N°</th>
                            {{-- Hidden on mobile --}}
                            <th scope="col" class="px-3 py-3 text-xs md:text-sm hidden sm:table-cell">DNI</th>
                            {{-- Hidden on mobile --}}
                            <th scope="col" class="px-3 py-3 text-xs md:text-sm">Apellido</th>
                            <th scope="col" class="px-3 py-3 text-xs md:text-sm">Nombre</th>
                            <th scope="col" class="px-3 py-3 text-xs md:text-sm">Sanciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jugadoresEquipos as $jugador)
                            @php
                                $estaSancionado = $jugador->sanciones->isNotEmpty();
                            @endphp

                            <tr>
                                <td class="px-3 py-2 border border-gray-500 dark:text-gray-200 hidden sm:table-cell">
                                    {{ $loop->iteration }}
                                </td> {{-- Hidden on mobile --}}
                                <td @adminOrCan('comision')
                                        class="px-3 py-2 border border-gray-500 dark:text-gray-200 {{ $estaSancionado ? 'text-red-600 font-bold' : '' }} hidden sm:table-cell">
                                        {{-- Hidden on mobile --}}
                                        {{ $jugador->documento }}
                                    @endadminOrCan
                                </td>
                                <td
                                    class="px-3 py-2 border border-gray-500 dark:text-gray-200 {{ $estaSancionado ? 'text-red-600 font-bold' : '' }}">
                                    {{ strtoupper($jugador->apellido) }}
                                </td>
                                <td
                                    class="px-3 py-2 border border-gray-500 dark:text-gray-200 {{ $estaSancionado ? 'text-red-600 font-bold' : '' }}">
                                    {{ strtoupper($jugador->nombre) }}
                                </td>
                                <td>
                                    @if ($jugador->sanciones->isNotEmpty())
                                        @foreach ($jugador->sanciones as $sancion)
                                            <div class="flex items-center space-x-1 me-1 mb-1 text-xs">
                                                {{-- Adjusted spacing and text size --}}
                                                <div class="flex items-center text-red-700  hover:text-white hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg px-2 py-1 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900"
                                                    title="Sancionado">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" {{-- Reduced icon size --}} viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-shield-ban-icon lucide-shield-ban mr-1">
                                                        <path
                                                            d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
                                                        <path d="m4.243 5.21 14.39 12.472" />
                                                    </svg>
                                                    Sancionado
                                                </div>
                                                <span class="text-white text-xs"> {{-- Reduced text size --}}
                                                    {{ $sancion->partidos_sancionados }} fechas, cumple:
                                                    {{ $sancion->partidos_cumplidos }}
                                                </span>
                                            </div>
                                        @endforeach
                                    @else
                                        <div
                                            class="flex text-green-700 hover:text-white hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-xs px-2 py-1 text-center me-1 mb-1 dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-800">
                                            {{-- Adjusted padding, margin, and text size --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                {{-- Reduced icon size --}} viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round"
                                                class="mr-1 lucide lucide-shield-check-icon lucide-shield-check">
                                                <path
                                                    d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
                                                <path d="m9 12 2 2 4-4" />
                                            </svg> Habilitado
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>


    {{-- --}}

    @push('js')
        <script>
            document.addEventListener('livewire:initialized', () => {

                Livewire.on('sancion-actualizada', () => {
                    Swal.fire({
                        title: 'Éxito!',
                        text: 'Sanciones actualizadas correctamente',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    });
                });
                Livewire.on('actualizar-sancion', () => {
                    Swal.fire({
                        'title': 'Correcto!',
                        'text': 'La sanción ha sido actualizado correctamente',
                        'icon': 'success'
                    });
                });

            });
        </script>
    @endpush

</div>
