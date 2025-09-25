<div>
    <div class="bg-blue-900 text-white p-2 shadow-md rounded flex justify-between items-center relative z-10"">
        <h2 class=" font-semibold text-xl text-gray-100 leading-tight">
        {{ __('Listado de Buena Fé') }}
        </h2>
        @if($equipoSeleccionado)
        <button wire:click="exportarJugadores" class="cursor-pointer p-2 hover:underline">Exportar

        </button>
        @endif
    </div>
    <!-- Select de equipos -->
    <div class="mb-4 bg-gray-500 p-4 rounded shadow-md">
        <label class="block mb-2 font-semibold text-white">Seleccione un equipo</label>
        <select wire:model.live="equipoSeleccionado" class="border bg-blue-900 text-white rounded p-2 w-full">
            <option value="">-- Elegir --</option>
            @foreach ($equiposDelCampeonato as $equipo)
            <option value="{{ $equipo->id }}">{{ $equipo->nombre }}</option>
            @endforeach
        </select>
    </div>

    <!-- Tabla de jugadores -->
    @if (!empty($jugadoresEquipos))
    <table class="min-w-full bg-white border dark:bg-gray-500">
        <thead>
            <tr class="bg-gray-200 text-left dark:bg-gray-900">
                <th class="px-4 py-2 text-center ">#</th>
                <th class="px-4 py-2 text-center ">DNI</th>
                <th class="px-4 py-2 text-center ">Apellido</th>
                <th class="px-4 py-2 text-center ">Nombre</th>
                <th class="px-4 py-2 text-center ">Sanciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($jugadoresEquipos as $jugador)
            <tr>
                <td class="border px-4 py-2">{{ strtoupper($loop->iteration) }}</td>
                <td class="border px-4 py-2">{{ strtoupper($jugador->documento )}}</td>
                <td class="border px-4 py-2">{{ strtoupper($jugador->apellido) }}</td>
                <td class="border px-4 py-2">{{ strtoupper($jugador->nombre) }}</td>
                <td>
                    @if ($jugador->sanciones->isNotEmpty())
                    @foreach ($jugador->sanciones as $sancion)
                    <div class="flex items-center space-x-1 me-1 mb-1 text-xs">
                        {{-- Adjusted spacing and text size --}}
                        <div class="flex items-center text-red-700  hover:text-white hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg px-2 py-1 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900"
                            title="Sancionado">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" {{-- Reduced icon size --}}
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
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
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" {{-- Reduced icon size --}}
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"
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
    @endif
</div>