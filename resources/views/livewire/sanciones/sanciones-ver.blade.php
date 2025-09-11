<div class="space-y-4">

    {{-- Filtros --}}
    <div class="flex flex-col sm:flex-row gap-2 bg-gray-200 dark:bg-gray-700 p-4 rounded-lg shawdon-md">
        {{-- Campeonato --}}
        <select wire:model.live="campeonato_id" class="border border-gray-500 rounded-lg p-2 flex-1 text-sm sm:text-base">
            <option value="">-- Selecciona un campeonato --</option>
            @foreach ($campeonatos as $camp)
            <option value="{{ $camp->id }}">{{ strtoupper($camp->nombre) }}</option>
            @endforeach
        </select>

        <input type="text" wire:model.defer="search" wire:keydown.enter="updateSearch" placeholder="Buscar jugador..."
            class="border  border-gray-500  rounded-lg p-2 flex-1 text-sm sm:text-base" />
    </div>

    {{-- Tabla --}}
    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="w-full text-sm sm:text-base text-left rtl:text-right text-gray-500 dark:text-gray-100">
            <thead class="text-xs sm:text-sm text-gray-50 uppercase bg-gray-700 dark:bg-gray-700 dark:text-[#FFC107]">
                <tr>
                    <th class="px-2 sm:px-4 py-2 text-left hidden sm:table-cell">Documento</th>
                    <th class="px-2 sm:px-4 py-2 text-left hidden sm:table-cell">Jugador</th>
                    <th class="px-2 sm:px-4 py-2 text-center hidden sm:table-cell">Fecha Sanción</th>
                    <th class="px-2 sm:px-4 py-2 text-center hidden sm:table-cell">Motivo</th>
                    <th class="px-2 sm:px-4 py-2 text-center hidden sm:table-cell">Fechas</th>
                    <th class="px-2 sm:px-4 py-2 text-center hidden sm:table-cell">Cumplidas</th>
                    <th class="px-2 sm:px-4 py-2 text-center hidden sm:table-cell">Detalle</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sanciones as $index => $jug)
                <tr
                    class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                    <td class="px-2 sm:px-4 py-2 hidden sm:table-cell">{{ $jug->jugador->documento }}</td>
                    <td class="px-2 sm:px-4 py-2 hidden sm:table-cell">
                        {{ strtoupper($jug->jugador->apellido) }}, {{ strtoupper($jug->jugador->nombre )}}
                    </td>
                    <td class="px-2 sm:px-4 py-2 text-center hidden sm:table-cell">{{ $jug->fecha_sancion }}</td>
                    <td class="px-2 sm:px-4 py-2 text-center hidden sm:table-cell">{{ ucwords($jug->motivo) }}</td>
                    <td class="px-2 sm:px-4 py-2 text-center hidden sm:table-cell">{{ $jug->partidos_sancionados }}
                    </td>
                    <td class="px-2 sm:px-4 py-2 text-center hidden sm:table-cell">{{ $jug->partidos_cumplidos }}
                    </td>
                    <td class="px-2 sm:px-4 py-2 text-center hidden sm:table-cell">{{ ucfirst($jug->observacion )}}</td>
                </tr>

                {{-- Fila adicional para móviles que muestra información resumida --}}
                <tr
                    class="sm:hidden
        @if ($index % 2 == 0) bg-blue-50 dark:bg-gray-800
        @else bg-blue-100 dark:bg-gray-500 @endif
        border-b border-gray-200 dark:border-gray-600">
                    <td colspan="7" class="px-2 py-2 text-sm">
                        <div class="grid grid-cols-2 gap-1">
                            <div><span class="font-semibold">DNI:</span> {{ $jug->jugador->documento }}</div>
                            <div><span class="font-semibold">Jugador:</span> {{ $jug->jugador->apellido }},
                                {{ $jug->jugador->nombre }}
                            </div>
                            <div><span class="font-semibold">Motivo:</span> {{ strtoupper($jug->motivo) }}</div>
                            <div><span class="font-semibold">Fecha Sanción:</span> {{ $jug->fecha_sancion }}</div>
                            <div><span class="font-semibold">Fechas:</span> {{ $jug->partidos_sancionados }}</div>
                            <div><span class="font-semibold">Cumplidas:</span> {{ $jug->partidos_cumplidos }}</div>
                            @if ($jug->observacion)
                            <div class="col-span-2"><span class="font-semibold">Detalle:</span>
                                {{ strtoupper($jug->observacion) }}
                            </div>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                        No se encontraron jugadores.
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