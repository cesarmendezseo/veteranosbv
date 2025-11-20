<div>
    <div class="bg-blue-900 text-white p-2 shadow-md rounded flex justify-between items-center relative z-10"">
        <h2 class=" font-semibold text-xl text-gray-100 leading-tight">
        {{ __('Listado de Buena Fé') }}
        </h2>
        <a href="{{ route('listado-buena-fe') }}"
            class=" text-white px-4 py-2 rounded flex items-center gap-2 hover:underline"> <svg
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            Volver</a>
        @if($equipoSeleccionado)
        @adminOrCan('comision|administrador')
        <button wire:click="exportarJugadores" class="cursor-pointer p-2 hover:underline">Exportar

        </button>
        @endadminOrCan
        @endif
    </div>
    <!-- Select de equipos -->
    <div class="mb-4 bg-gray-500 p-4 rounded shadow-md">
        <label class="block mb-2 font-semibold text-white">Seleccione un equipo</label>
        <select wire:model.live="equipoSeleccionado" class="border bg-blue-900 text-white rounded p-2 w-full">
            <option value="">-- Elegir --</option>
            @foreach ($equiposDelCampeonato as $equipo)
            <option value="{{ $equipo->id }}">{{strtoupper( $equipo->nombre )}}</option>
            @endforeach
        </select>
    </div>

    <!-- Tabla de jugadores -->
    @if (!empty($jugadoresEquipos))
    <div class="overflow-x-auto rounded-lg border border-gray-300 dark:border-gray-600">
        <div class="hidden lg:block">
            <table class="min-w-full table-fixed border-collapse bg-white dark:bg-gray-700">
                <thead class="sticky top-0 z-10 bg-gray-200 dark:bg-gray-900 text-gray-800 dark:text-white">
                    <tr>
                        <th class="border px-4 py-2 text-center">#</th>
                        <th class="border px-4 py-2 text-center">DNI</th>
                        <th class="border px-4 py-2 text-center">Apellido</th>
                        <th class="border px-4 py-2 text-center">Nombre</th>
                        <th class="border px-4 py-2 text-center">Sanciones</th>
                        <th class="border px-4 py-2 text-center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jugadoresEquipos as $jugador)
                    @php
                    $sancionesJugador = $sanciones->where('jugador_id', $jugador['jugador']->id);
                    @endphp
                    <tr class="text-sm text-gray-900 dark:text-white">
                        <td class="border px-2 py-2 text-center">{{ strtoupper($loop->iteration) }}</td>
                        <td class="border px-2 py-2 text-center">{{ strtoupper($jugador['jugador']->documento) }}</td>
                        <td class="border px-2 py-2">{{ strtoupper($jugador['jugador']->apellido) }}</td>
                        <td class="border px-2 py-2">{{ strtoupper($jugador['jugador']->nombre) }}</td>
                        <td class="border px-2 py-2 max-w-[250px]">
                            @if ($sancionesJugador->isNotEmpty())
                            @foreach ($sancionesJugador as $sancion)
                            <div class="flex items-center gap-2 mb-1 text-xs">
                                <div class="flex items-center bg-red-500 text-white rounded px-2 py-1"
                                    title="Sancionado">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-shield-ban mr-1">
                                        <path
                                            d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
                                        <path d="m4.243 5.21 14.39 12.472" />
                                    </svg>
                                    Sancionado
                                </div>
                                <span class="text-red-900 dark:text-white">
                                    {{ $sancion->partidos_sancionados }} fechas, cumple: {{ $sancion->partidos_cumplidos
                                    }}
                                </span>
                            </div>
                            @endforeach
                            @else
                            <div class="flex items-center bg-green-600 text-white rounded px-2 py-1 text-xs">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-shield-check mr-1">
                                    <path
                                        d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
                                    <path d="m9 12 2 2 4-4" />
                                </svg>
                                Habilitado
                            </div>
                            @endif
                        </td>
                        <td class="px-2 py-2 text-right border">
                            @adminOrCan('comision|administrador')
                            <button type="button" wire:click=" darDeBaja( {{ $jugador['jugador']->id }})" class="text-red-600 hover:underline text-sm ml-2 cursor-pointer dark:bg-white rounded-4xl
                            dark:p-1 dark:shadow-2xl bg-gray-200 p-1 shadow-lg">
                                {{-- Icono de eliminar --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-5 w-5 shasow-lg">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                            @endadminOrCan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{-- ========================MOVIL================================================== --}}
    <div class="lg:hidden space-y-4 pt-4">
        @foreach ($jugadoresEquipos as $jugador)
        @php
        // Se mantiene la lógica para buscar las sanciones si no las cargaste con eager loading
        $sancionesJugador = $sanciones->where('jugador_id', $jugador['jugador']->id);
        $jugadorModel = $jugador['jugador']; // Alias para simplificar
        @endphp

        {{-- Tarjeta Individual del Jugador --}}
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-4 border border-gray-200 dark:border-gray-700">

            {{-- Sección Superior: Nombre y Documento --}}
            <div class="flex items-center justify-between border-b pb-2 mb-3 dark:border-gray-700">
                <div class="text-lg font-bold text-gray-900 dark:text-white">
                    {{ strtoupper($jugadorModel->apellido) }}, {{ strtoupper($jugadorModel->nombre) }}
                </div>
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    <span class="inline-block bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-full">
                        DNI: {{ strtoupper($jugadorModel->documento) }}
                    </span>
                </div>
            </div>

            {{-- Sección de Detalles / Sanciones --}}
            <div class="space-y-2">

                {{-- Sanciones / Habilitación --}}
                <div class="flex items-start justify-between">
                    <div class="font-semibold text-gray-700 dark:text-gray-300">Estado:</div>
                    <div class="text-right flex-shrink-0 max-w-[60%]">
                        @if ($sancionesJugador->isNotEmpty())
                        @foreach ($sancionesJugador as $sancion)
                        <div class="flex items-center gap-1 mb-1 text-xs justify-end">
                            <div class="flex items-center bg-red-500 text-white rounded px-2 py-1" title="Sancionado">
                                {{-- Icono de Sancionado --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-shield-ban mr-1">
                                    <path
                                        d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
                                    <path d="m4.243 5.21 14.39 12.472" />
                                </svg>
                                Sancionado
                            </div>
                        </div>
                        <span class="text-red-900 dark:text-red-400 text-xs block text-right">
                            {{ $sancion->partidos_sancionados }} fechas, cumple: {{ $sancion->partidos_cumplidos }}
                        </span>
                        @endforeach
                        @else
                        <div class="inline-flex items-center bg-green-600 text-white rounded px-2 py-1 text-xs">
                            {{-- Icono de Habilitado --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-shield-check mr-1">
                                <path
                                    d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
                                <path d="m9 12 2 2 4-4" />
                            </svg>
                            Habilitado
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Posición en la Lista --}}
                {{-- <div class="flex justify-between items-center text-sm pt-2 border-t dark:border-gray-700">
                    <div class="text-gray-700 dark:text-gray-300"># Posición:</div>
                    <div class="font-semibold text-gray-900 dark:text-white">{{ strtoupper($loop->iteration) }}</div>
                </div> --}}

            </div>

            {{-- Sección de Acción (Botón Dar de Baja) --}}
            @adminOrCan('comision|administrador')
            <div class="text-right pt-3 mt-3 border-t dark:border-gray-700">
                <button type="button" wire:click="darDeBaja({{ $jugadorModel->id }})"
                    class="inline-flex items-center text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm font-semibold p-2 rounded-full transition duration-150 ease-in-out bg-gray-100 dark:bg-gray-700 shadow-md">
                    {{-- Icono de eliminar --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="h-5 w-5 mr-1">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                    Dar de Baja
                </button>
            </div>
            @endadminOrCan

        </div>
        @endforeach
    </div>
    @endif
</div>