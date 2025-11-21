<div class="space-y-4">
    {{-- Encabezado con filtros
    {{-- <div
        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-gray-200 dark:bg-gray-700 p-4 rounded-lg shadow-md">

        {{-- Buscar jugador --}
        <div class="w-full sm:w-1/2">
            <input type="text" wire:model.live="search" placeholder="Buscar jugador por nombre o documento"
                class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300" />
        </div>

        {{-- Filtro por equipo --}
        <div class="w-full sm:w-1/2">
            <select wire:model.live="equipoSeleccionado"
                class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300 dark:bg-gray-800 dark:border-gray-600 ">
                <option value="">Todos los equipos</option>
                @foreach($this->equipos as $equipo)
                <option value="{{ $equipo->id }}">{{ strtoupper($equipo->nombre) }}</option>
                @endforeach
            </select>
        </div>
    </div> --}}

    {{-- Tabla en escritorio --}}
    <div class="{{-- hidden --}} sm:block bg-white shadow rounded-lg overflow-x-auto">
        <table class="w-full text-sm sm:text-base text-left text-gray-700 dark:text-gray-100">
            <thead class="text-xs sm:text-sm uppercase bg-gray-700 dark:bg-gray-700 text-yellow-400">
                <tr>
                    {{-- <th class="px-4 py-2">Documento</th> --}}
                    <th class="px-4 py-2">Jugador</th>
                    <th class="px-4 py-2">Equipo</th>
                    <th class="px-4 py-2 text-center">Goles</th>
                </tr>
            </thead>
            <tbody>
                @forelse($goleadores as $gol)
                <tr class="border-b dark:border-gray-600 dark:bg-gray-800">
                    {{-- <td class="px-4 py-2">{{ $gol->jugador->documento }}</td> --}}
                    <td class="px-4 py-2">{{ strtoupper($gol->jugador->apellido) }}, {{
                        strtoupper($gol->jugador->nombre) }}</td>
                    <td class="px-4 py-2">{{ strtoupper($gol->jugador->equipo->nombre ?? 'Sin equipo') }}</td>
                    <td class="px-4 py-2 text-center font-bold">{{ $gol->total_goles }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                        @if ($search || $equipoSeleccionado)
                        No se encontraron coincidencias.
                        @else
                        No hay goles registrados en este campeonato.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Vista en móvil: tarjetas --}}
    {{-- <div class="sm:hidden space-y-4">
        @forelse($goleadores as $gol)
        <div class="bg-gray-100 dark:bg-gray-800 rounded-lg shadow p-4 text-sm text-gray-800 dark:text-gray-100">

            <div><span class="font-semibold">Jugador:</span> {{ strtoupper($gol->jugador->apellido) }}, {{
                strtoupper($gol->jugador->nombre) }}</div>
            <div><span class="font-semibold">Equipo:</span> {{ strtoupper($gol->jugador->equipo->nombre ?? 'Sin equipo')
                }}</div>
            <div><span class="font-semibold">Goles:</span> {{ $gol->total_goles }}</div>
        </div>
        @empty
        <div class="text-center text-gray-500 dark:text-gray-300">
            @if ($search || $equipoSeleccionado)
            No se encontraron coincidencias.
            @else
            No hay goles registrados en este campeonato.
            @endif
        </div>
        @endforelse
    </div> --}}

    {{-- Paginación --}}
    <div class="mt-4">
        {{ $goleadores->links() }}
    </div>
</div>