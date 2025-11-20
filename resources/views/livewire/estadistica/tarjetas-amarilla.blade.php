<div class="space-y-4">
    {{-- Encabezado --}}
    <div
        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-gray-200 dark:bg-gray-700 p-4 rounded-lg shadow-md">

        {{-- Filtro --}}
        <div class="w-full sm:w-1/2">
            <input type="text" wire:model.live="buscarAmarillas" placeholder="Buscar jugador por nombre o documento"
                class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300" />
        </div>
    </div>

    {{-- Vista en escritorio: tabla --}}
    <div class="hidden sm:block bg-white shadow rounded-lg overflow-x-auto">
        <table class="w-full text-sm sm:text-base text-left text-gray-700 dark:text-gray-100">
            <thead class="text-xs sm:text-sm uppercase bg-gray-700 dark:bg-gray-700 text-yellow-400">
                <tr>
                    <th class="px-4 py-2">Documento</th>
                    <th class="px-4 py-2">Jugador</th>
                    <th class="px-4 py-2">Equipo</th>
                    <th class="px-4 py-2">Tarjetas</th>
                    <th class="px-4 py-2">Partido</th>
                </tr>
            </thead>
            <tbody>
                @forelse($amarillas as $jug)
                <tr class="border-b dark:border-gray-600 dark:bg-gray-800">
                    <td class="px-4 py-2">{{ $jug->jugador->documento }}</td>
                    <td class="px-4 py-2">{{ strtoupper($jug->jugador->apellido) }}, {{
                        strtoupper($jug->jugador->nombre) }}</td>
                    <td class="px-4 py-2">{{ $jug->jugador->equipo->nombre ?? 'Sin equipo' }}</td>
                    <td class="px-4 py-2 text-center">{{ $jug->tarjeta_amarilla }}</td>
                    <td class="px-4 py-2">
                        @if($jug->estadisticable && method_exists($jug->estadisticable, 'equipoLocal'))
                        {{ $jug->estadisticable->equipoLocal->nombre ?? 'Local' }} vs {{
                        $jug->estadisticable->equipoVisitante->nombre ?? 'Visitante' }}
                        @else
                        <em>Sin partido</em>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                        @if ($buscarAmarillas)
                        No se encontraron coincidencias para <strong>"{{ $buscarAmarillas }}"</strong>.
                        @else
                        No hay tarjetas amarillas registradas en este campeonato.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Vista en móvil: tarjetas --}}
    <div class="sm:hidden space-y-4">
        @forelse($amarillas as $jug)
        <div class="bg-gray-100 dark:bg-gray-800 rounded-lg shadow p-4 text-sm text-gray-800 dark:text-gray-100">
            <div><span class="font-semibold">DNI:</span> {{ $jug->jugador->documento }}</div>
            <div><span class="font-semibold">Jugador:</span> {{ strtoupper($jug->jugador->apellido) }}, {{
                strtoupper($jug->jugador->nombre) }}</div>
            <div><span class="font-semibold">Equipo:</span> {{ $jug->jugador->equipo->nombre ?? 'Sin equipo' }}</div>
            <div><span class="font-semibold">Tarjetas:</span> {{ $jug->tarjeta_amarilla }}</div>
            <div><span class="font-semibold">Partido:</span>
                @if($jug->estadisticable && method_exists($jug->estadisticable, 'equipoLocal'))
                {{ $jug->estadisticable->equipoLocal->nombre ?? 'Local' }} vs {{
                $jug->estadisticable->equipoVisitante->nombre ?? 'Visitante' }}
                @else
                <em>Sin partido</em>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center text-gray-500 dark:text-gray-300">
            @if ($buscarAmarillas)
            No se encontraron coincidencias para <strong>"{{ $buscarAmarillas }}"</strong>.
            @else
            No hay tarjetas amarillas registradas en este campeonato.
            @endif
        </div>
        @endforelse
    </div>
</div>