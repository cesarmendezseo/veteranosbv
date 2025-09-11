<div>
      <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Tabla de Posiciones') }}
        </h2>
        <div class="flex items-center space-x-4">
        <a href="{{ route('tabla-posiciones') }}" class=" text-white px-4 py-2 rounded flex items-center gap-2 hover:underline">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            Volver
        </a>
        </div>
    </x-slot>
    <!-- Filtros y controles -->
    <div class="p-2 sm:p-4 flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4">

      @adminOrCan()


        <!-- Botones de exportación -->
        @if ($campeonato_id)

        <div class="flex gap-2 w-full sm:w-auto mt-2 sm:mt-0">

            <a href="{{ route('tabla.pdf', $campeonato_id) }}"
                class="cursor-pointer inline-flex items-center justify-center bg-red-600 text-white px-3 py-2 rounded hover:bg-red-700 transition-colors text-sm sm:text-base w-1/2 sm:w-auto"
                title="Exportar a PDF">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="ml-1 hidden sm:inline">PDF</span>
            </a>

            <button wire:click="exportarPosiciones"
                class="cursor-pointer  inline-flex items-center justify-center bg-green-600 text-white px-3 py-2 rounded hover:bg-green-700 transition-colors text-sm sm:text-base w-1/2 sm:w-auto"
                title="Exportar a Excel">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="ml-1 hidden sm:inline">Excel</span>
            </button>
        </div>

        @endif
        @endadminOrCan
    </div>
    <flux:separator />
    <!-- Tabla de posiciones -->
    <div class="p-2 sm:p-4 shadow-md bg-white dark:bg-gray-800 rounded-lg">
        @foreach ($posiciones as $grupoNombre => $tablaGrupo)
        <h2 class="text-lg font-bold mt-4 mb-2 text-center bg-gray-300 dark:bg-gray-600 py-1 rounded">
            {{ ucwords($grupoNombre) }}
        </h2>
        <div class="overflow-x-auto mb-6 shadow-md">
            <table class="w-full text-xs sm:text-sm text-center border border-gray-300 ">
                <thead class="bg-gray-700 text-gray-100 dark:bg-gray-700 dark:text-[#b8ae2a] dark:font-bold">
                    <tr>
                        <th class="border px-1 md:px-2 py-1 sticky left-0 dark:bg-gray-700 ">#</th>
                        <th class="border px-1 md:px-2 py-1 text-left sticky left-12 dark:bg-gray-700  min-w-[100px]">EQUIPOS</th>
                        <th class="border px-1 md:px-2 py-1 ">PJ</th>
                        <th class="border px-1 md:px-2 py-1">PG</th>
                        <th class="border px-1 md:px-2 py-1">PE</th>
                        <th class="border px-1 md:px-2 py-1">PP</th>
                        <th class="border px-1 md:px-2 py-1 hidden md:table-cell">GF</th>
                        <th class="border px-1 md:px-2 py-1 hidden md:table-cell">GC</th>
                        <th class="border px-1 md:px-2 py-1">DG</th>
                        <th class="border px-1 md:px-2 py-1 hidden sm:table-cell">Fair Play</th>
                        <th class="border px-1 md:px-2 py-1 font-bold">Pts</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tablaGrupo as $index => $equipo)
                    <tr class="{{ $index % 2 === 0 ? 'bg-white dark:bg-gray-800' : 'bg-gray-100 dark:bg-gray-700' }}">
                        <td class="border px-1 md:px-2 py-1 sticky left-0 {{ $index % 2 === 0 ? 'bg-white dark:bg-gray-800 dark:text-white' : 'bg-gray-100 dark:bg-gray-700' }} ">
                            {{ $index + 1 }}
                        </td>
                        <td class="border px-1 md:px-2 py-1 text-left  left-12 dark:text-white {{ $index % 2 === 0 ? 'bg-white dark:bg-gray-800' : 'bg-gray-100 dark:bg-gray-700' }}  min-w-[100px]">
                            {{ strtoupper($equipo['equipo'] )}}
                        </td>
                        <td class="border px-1 md:px-2 py-1">{{ $equipo['jugados'] }}</td>
                        <td class="border px-1 md:px-2 py-1">{{ $equipo['ganados'] }}</td>
                        <td class="border px-1 md:px-2 py-1">{{ $equipo['empatados'] }}</td>
                        <td class="border px-1 md:px-2 py-1">{{ $equipo['perdidos'] }}</td>
                        <td class="border px-1 md:px-2 py-1 hidden md:table-cell">{{ $equipo['goles_favor'] }}</td>
                        <td class="border px-1 md:px-2 py-1 hidden md:table-cell">{{ $equipo['goles_contra'] }}</td>
                        <td class="border px-1 md:px-2 py-1">{{ $equipo['diferencia_goles'] }}</td>
                        <td class="border px-1 md:px-2 py-1 hidden sm:table-cell">{{ $equipo['fair_play'] }}</td>
                        <td class="border px-1 md:px-2 py-1 font-bold">{{ $equipo['puntos'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endforeach
    </div>

</div>