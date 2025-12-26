<div class=" bg-white rounded-lg mb-4 shadow-md">
    <div class="bg-blue-900 text-white mb-2 p-4 shadow-md rounded flex justify-between items-center relative z-10"">
        <h2 class=" font-semibold text-xl text-gray-100 leading-tight">
        {{ __('Historial de Jugadores por Fechas') }}
        </h2>
        <div>
            <a href="{{ route('altas-bajas.index') }}" class="cursor-pointer hover:underline">Volver</a>
        </div>
    </div>

    {{-- 🔎 FILTROS --}}
    <div class="flex flex-wrap gap-3 items-end mb-2">

        <div>
            <label class="text-sm font-semibold">Tipo</label>
            <select wire:model.live="tipo" class="border rounded p-2">
                <option value="altas">Altas</option>
                <option value="bajas">Bajas</option>
            </select>
        </div>

        <div>
            <label class="text-sm font-semibold">Campeonato</label>
            <select wire:model.live="campeonatoFiltro" class="border rounded p-2">
                <option value="">Todos</option>
                @foreach($campeonatos as $camp)
                <option value="{{ $camp->id }}">
                    {{ strtoupper($camp->nombre) }}
                </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="text-sm font-semibold">Desde</label>
            <input type="date" wire:model.live="fechaDesde" class="border rounded p-2">
        </div>

        <div>
            <label class="text-sm font-semibold">Hasta</label>
            <input type="date" wire:model.live="fechaHasta" class="border rounded p-2">
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
                    <th class="p-2 border">
                        {{ $tipo === 'altas' ? 'Fecha Alta' : 'Fecha Baja' }}
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
                    <td class="p-2 border">
                        {{ $tipo === 'altas'
                        ? $mov->fecha_alta
                        : $mov->fecha_baja }}
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