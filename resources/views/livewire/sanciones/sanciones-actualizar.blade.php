<div class="space-y-4">
    {{-- Filtros --}}

    <div
        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-gray-200 dark:bg-gray-700 p-4 rounded-lg shadow-md">
        {{-- Nombre del campeonato --}}
        <div class="relative z-0 w-55  group">

            {{-- <select wire:model.live="campeonato_id" id="countries"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">Seleccione Campeonato</option>
                @foreach ($campeonatos as $campeonato)
                <option value="{{ $campeonato->id }}">{{ $campeonato->nombre }}</option>
                @endforeach
            </select> --}}

        </div>
        <select wire:model.live="filtroCumplidas"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-40 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option value="todas">Todas</option>
            <option value="cumplidas">Cumplidas</option>
            <option value="pendientes">Pendientes</option>
        </select>
        {{-- Filtro --}}
        <div class="w-full sm:w-1/2">
            <input type="text" wire:model.live="search" placeholder="Buscar jugador por nombre o documento"
                class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300" />
        </div>
        @adminOrCan('administracion')
        <button wire:click="sumarFecha"
            class="cursor-pointer p-2 bg-blue-500 hover:bg-blue-200 text-white rounded-lg shadow-md">
            Sumar Sancion
        </button>
        <button wire:click="restarFecha"
            class="cursor-pointer p-2 bg-blue-500 hover:bg-blue-200 text-white rounded-lg shadow-md">
            Restar Sancion
        </button>
        @endadminOrCan


    </div>
    {{-- Tabla --}}
    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="w-full text-sm sm:text-base text-left rtl:text-right text-gray-500 dark:text-gray-100">
            <thead class="text-xs sm:text-sm text-gray-50 uppercase bg-gray-700 dark:bg-gray-700 dark:text-[#FFC107]">
                <tr>
                    <th class="px-2 sm:px-4 py-2 text-left hidden sm:table-cell">Documento</th>
                    <th class="px-2 sm:px-4 py-2 text-left hidden sm:table-cell">Jugador</th>
                    <th class="px-2 sm:px-4 py-2 text-center hidden sm:table-cell">Fecha Sancion</th>
                    <th class="px-2 sm:px-4 py-2 text-center hidden sm:table-cell">Cantidad de Sanción</th>
                    <th class="px-2 sm:px-4 py-2 text-center hidden sm:table-cell">Cumplidas</th>
                    <th class="px-2 sm:px-4 py-2 text-center hidden sm:table-cell">Acción</th>

                </tr>
            </thead>
            <tbody>
                @forelse($sanciones as $index => $jug)
                <tr
                    class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                    <td class="px-2 sm:px-4 py-2 hidden sm:table-cell">{{ $jug->jugador->documento }}</td>
                    <td class="px-2 sm:px-4 py-2 hidden sm:table-cell">
                        {{ strtoupper($jug->jugador->apellido) }}, {{ strtoupper($jug->jugador->nombre) }}
                    </td>
                    <td class="px-2 sm:px-4 py-2 text-center hidden sm:table-cell">
                        {{ ucfirst($jug->etapa_sancion) }}
                    </td>


                    <td class="px-2 sm:px-4 py-2 text-center hidden sm:table-cell">{{ $jug->partidos_sancionados }}</td>
                    <td class="px-2 sm:px-4 py-2 text-center hidden sm:table-cell">{{ $jug->partidos_cumplidos }}</td>
                    @adminOrCan('administrador')
                    <td class="px-2 sm:px-4 py-2 text-center hidden sm:table-cell">
                        <div class="flex space-x-2">
                            <button wire:click="sumarFechaJugador({{ $jug->jugador_id }})"
                                class="cursor-pointer p-2 bg-green-500 hover:bg-green-600 text-white rounded-lg shadow-md transition">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-copy-plus-icon lucide-copy-plus">
                                    <line x1="15" x2="15" y1="12" y2="18" />
                                    <line x1="12" x2="18" y1="15" y2="15" />
                                    <rect width="14" height="14" x="8" y="8" rx="2" ry="2" />
                                    <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2" />
                                </svg>

                            </button>

                            <button wire:click="restarFechaJugador({{ $jug->jugador_id }})"
                                class="cursor-pointer p-2 bg-red-500 hover:bg-red-600 text-white rounded-lg shadow-md transition">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-copy-minus-icon lucide-copy-minus">
                                    <line x1="12" x2="18" y1="15" y2="15" />
                                    <rect width="14" height="14" x="8" y="8" rx="2" ry="2" />
                                    <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2" />
                                </svg>
                            </button>
                        </div>
                    </td>
                    @endadminOrCan
                </tr>

                {{-- Fila adicional para móviles --}}
                <tr class="sm:hidden
                    @if ($index % 2 == 0) bg-blue-50 dark:bg-gray-800
                    @else bg-blue-100 dark:bg-gray-500 @endif
                    border-b border-gray-200 dark:border-gray-600">
                    <td colspan="8" class="px-2 py-2 text-sm">
                        <div class="grid grid-cols-2 gap-1">

                            <div class="font-semibold font-Titillium Web "> {{ $jug->jugador->apellido }}, {{
                                $jug->jugador->nombre }} @role('administrador|comision')
                                <div class="text-xs"><span class="font-semibold">DNI:</span> {{ $jug->jugador->documento
                                    }}</div>
                                @endrole
                            </div>
                            <div><span class="font-semibold text-gray-500">Fecha Sanción:</span> {{
                                ucfirst($jug->etapa_sancion) }}
                            </div>

                            <div><span class="font-semibold">Cantidad Sanción:</span> {{ $jug->partidos_sancionados }}
                            </div>
                            <div><span class="font-semibold">Cumplidas:</span> {{ $jug->partidos_cumplidos }}</div>


                        </div>
                    </td>
                    @adminOrCan('administrador')
                    <td>
                        <div class="flex space-x-2">
                            <button wire:click="sumarFechaJugador({{ $jug->jugador_id }})"
                                class="cursor-pointer p-2 bg-green-500 hover:bg-green-600 text-white rounded-lg shadow-md transition">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-copy-plus-icon lucide-copy-plus">
                                    <line x1="15" x2="15" y1="12" y2="18" />
                                    <line x1="12" x2="18" y1="15" y2="15" />
                                    <rect width="14" height="14" x="8" y="8" rx="2" ry="2" />
                                    <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2" />
                                </svg>

                            </button>

                            <button wire:click="restarFechaJugador({{ $jug->jugador_id }})"
                                class="cursor-pointer p-2 bg-red-500 hover:bg-red-600 text-white rounded-lg shadow-md transition">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-copy-minus-icon lucide-copy-minus">
                                    <line x1="12" x2="18" y1="15" y2="15" />
                                    <rect width="14" height="14" x="8" y="8" rx="2" ry="2" />
                                    <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2" />
                                </svg>
                            </button>
                        </div>
                    </td>
                    @endadminOrCan
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                        @if ($search)
                        No se encontraron sanciones para <strong>"{{ $search }}"</strong>.
                        @else
                        No se encontraron sanciones.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Paginación --}}
        @if ($sanciones->count())
        <div class="p-4">
            {{ $sanciones->links() }}
        </div>
        @endif
    </div>
</div>