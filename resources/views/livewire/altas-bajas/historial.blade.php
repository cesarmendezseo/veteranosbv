<div class=" bg-white rounded-lg mb-4 shadow-md">


    <div class="bg-blue-900 text-white mb-2 p-4 shadow-md rounded flex justify-between items-center relative z-10"">
        <h2 class=" font-semibold text-xl text-gray-100 leading-tight">
        {{ __('Historial de Jugadores por Fechas') }}
        </h2>
        <div>
            <a href="{{ route('altas-bajas.index') }}" class="cursor-pointer hover:underline">Volver</a>
        </div>
        <button wire:click="exportarExcel"
            class="cursor-pointer bg-blue-600 text-white px-4 py-2 rounded hover:bg-green-700">
            📊 Exportar a Excel
        </button>
    </div>

    {{-- 🔎 FILTROS --}}
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">



        <select wire:model.defer="tipo"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option value="altas">ALTAS</option>
            <option value="bajas">BAJAS</option>
        </select>

        <select wire:model.defer="campeonatoFiltro"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option value="">Todos los campeonatos</option>
            @foreach($campeonatos as $camp)
            <option value="{{ $camp->id }}">
                {{ strtoupper($camp->nombre) }}
            </option>
            @endforeach
        </select>

        <input type="date" wire:model.defer="fechaDesde"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        <input type="date" wire:model.defer="fechaHasta"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

        <div class="flex gap-2">
            <button type="button" wire:click="filtrar"
                class="cursor-pointer bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Filtrar
            </button>

            <button type="button" wire:click="limpiarFiltros"
                class="cursor-pointer  bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                Limpiar
            </button>
        </div>

    </div>

    {{-- 📋 TABLA --}}
    <div class="overflow-x-auto">
        <table class="min-w-full border text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">Jugador</th>
                    <th class="p-2 border">Equipo</th>
                    <th class="p-2 border">Campeonato</th>
                    <th class="p-2 border text-center">
                        {{ $tipo === 'altas' ? 'FECHA ALTA' : 'FECHA BAJA' }}
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($movimientos as $mov)
                <tr>
                    <td class="p-2 border">
                        {{ strtoupper($mov->apellido) }}, {{ strtoupper($mov->nombre) }}
                    </td>
                    <td class="p-2 border">{{ strtoupper($mov->equipo) }}</td>
                    <td class="p-2 border">{{ strtoupper($mov->campeonato) }}</td>
                    <td class="p-2 border text-center">
                        @if($tipo === 'altas' && $mov->fecha_alta)
                        {{ \Carbon\Carbon::parse($mov->fecha_alta)->format('d/m/Y') }}
                        @elseif($tipo === 'bajas' && $mov->fecha_baja)
                        {{ \Carbon\Carbon::parse($mov->fecha_baja)->format('d/m/Y') }}
                        @else
                        —
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-4 text-center text-gray-500">
                        No hay registros
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- 📄 PAGINACIÓN --}}
    <div>
        {{ $movimientos->links() }}
    </div>

</div>