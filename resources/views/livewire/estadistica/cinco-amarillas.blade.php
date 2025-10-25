<div class="space-y-4">
    {{-- Encabezado y buscador --}}
    <div
        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-gray-200 dark:bg-gray-700 p-4 rounded-lg shadow-md">


        {{-- Filtro --}}
        <div class="w-full sm:w-1/2">
            <input type="text" wire:model.debounce.500ms="search" placeholder="Buscar jugador por nombre o documento"
                class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring focus:border-yellow-400" />
        </div>
    </div>

    {{-- Vista en escritorio: tabla --}}
    <div class="hidden sm:block bg-white dark:bg-gray-800 shadow rounded-lg overflow-x-auto">
        <table class="w-full text-sm sm:text-base text-left text-gray-700 dark:text-gray-100">
            <thead class="text-xs sm:text-sm uppercase bg-gray-700 dark:bg-gray-700 text-yellow-400">
                <tr>
                    <th class="px-4 py-2">Documento</th>
                    <th class="px-4 py-2">Jugador</th>
                    <th class="px-4 py-2">Equipo</th>
                    <th class="px-4 py-2 text-center">Total Amarillas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tarjetasAcumuladasPorJugador as $jug)
                @php
                $jugador = \App\Models\Jugador::with('equipo')->find($jug->jugador_id);
                @endphp
                <tr class="border-b dark:border-gray-600">
                    <td class="px-4 py-2">{{ $jugador->documento ?? '—' }}</td>
                    <td class="px-4 py-2">{{ strtoupper($jugador->apellido) }}, {{ strtoupper($jugador->nombre) }}</td>
                    <td class="px-4 py-2">{{ $jugador->equipo->nombre ?? 'Sin equipo' }}</td>
                    <td class="px-4 py-2 text-center">{{ $jug->total_tarjetas_amarillas_acumuladas }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                        @if ($search)
                        No se encontraron coincidencias para <strong>"{{ $search }}"</strong>.
                        @else
                        No hay jugadores con 5 o más amarillas en este campeonato.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Paginación --}}
        @if ($tarjetasAcumuladasPorJugador->count())
        <div class="p-4">
            {{ $tarjetasAcumuladasPorJugador->links() }}
        </div>
        @endif
    </div>

    {{-- Vista en móvil: tarjetas --}}
    <div class="sm:hidden space-y-4">
        @forelse($tarjetasAcumuladasPorJugador as $jug)
        @php
        $jugador = \App\Models\Jugador::with('equipo')->find($jug->jugador_id);
        @endphp
        <div class="bg-yellow-50 dark:bg-gray-800 rounded-lg shadow p-4 text-sm text-gray-800 dark:text-gray-100">
            <div><span class="font-semibold">DNI:</span> {{ $jugador->documento ?? '—' }}</div>
            <div><span class="font-semibold">Jugador:</span> {{ strtoupper($jugador->apellido) }}, {{
                strtoupper($jugador->nombre) }}</div>
            <div><span class="font-semibold">Equipo:</span> {{ $jugador->equipo->nombre ?? 'Sin equipo' }}</div>
            <div><span class="font-semibold">Total Amarillas:</span> {{ $jug->total_tarjetas_amarillas_acumuladas }}
            </div>
        </div>
        @empty
        <div class="text-center text-gray-500 dark:text-gray-300">
            @if ($search)
            No se encontraron coincidencias para <strong>"{{ $search }}"</strong>.
            @else
            No hay jugadores con 5 o más amarillas en este campeonato.
            @endif
        </div>
        @endforelse

        {{-- Paginación en móvil --}}
        @if ($tarjetasAcumuladasPorJugador->count())
        <div class="p-2">
            {{ $tarjetasAcumuladasPorJugador->links() }}
        </div>
        @endif
    </div>
</div>