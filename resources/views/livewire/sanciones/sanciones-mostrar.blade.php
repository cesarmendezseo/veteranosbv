<div class="space-y-4">
    {{-- Filtros --}}
    <div class="bg-gray-200 dark:bg-gray-700 p-4 rounded-lg shadow-md">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center">
            <div>
                <input type="text" wire:model.live="search" placeholder="Buscar jugador..."
                    class="w-full px-4 py-2 border rounded-md shadow-sm focus:ring focus:border-blue-300 dark:bg-gray-800 dark:text-white" />
            </div>

            <div>
                <select wire:model.live="campeonato_id"
                    class="w-full px-4 py-2 border rounded-md shadow-sm focus:ring focus:border-blue-300 dark:bg-gray-800 dark:text-white">
                    <option value="">Todos los campeonatos</option>
                    @foreach($campeonatos as $camp)
                    <option value="{{ $camp->id }}">{{ strtoupper($camp->nombre) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model.live="soloPendientes"
                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-200">Solo pendientes</span>
                </label>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="overflow-x-auto bg-white shadow rounded-lg dark:bg-gray-900">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-100">
            <thead class="text-xs uppercase bg-gray-700 text-gray-50 dark:text-[#FFC107]">
                <tr>
                    <th class="px-4 py-2 hidden sm:table-cell">Documento</th>
                    <th class="px-4 py-2 hidden sm:table-cell">Jugador</th>
                    <th class="px-4 py-2 hidden sm:table-cell">Campeonato</th>
                    <th class="px-4 py-2 text-center hidden sm:table-cell">Sanción (Tiempo/Fec)</th>
                    <th class="px-4 py-2 text-center hidden sm:table-cell">Estado / Cumplidas</th>
                    <th class="px-4 py-2 text-center hidden sm:table-cell">Detalle</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sanciones as $index => $jug)
                @php
                \Carbon\Carbon::setLocale('es');
                $esPorTiempo = $jug->partidos_sancionados === 0;
                $esVigente = $esPorTiempo
                ? ($jug->fecha_fin && \Carbon\Carbon::parse($jug->fecha_fin)->isFuture())
                : ($jug->partidos_cumplidos < $jug->partidos_sancionados);
                    @endphp

                    {{-- Fila Desktop --}}
                    <tr
                        class="hidden sm:table-row odd:bg-white even:bg-gray-50 dark:odd:bg-gray-900 dark:even:bg-gray-800 border-b dark:border-gray-700">
                        <td class="px-4 py-2">{{ $jug->jugador->documento }}</td>
                        <td class="px-4 py-2 font-bold">{{ strtoupper($jug->jugador->apellido) }}, {{
                            strtoupper($jug->jugador->nombre) }}</td>
                        <td class="px-4 py-2 text-xs">{{ strtoupper($jug->campeonato->nombre) }}</td>

                        {{-- Columna Sanción --}}
                        <td class="px-4 py-2 text-center">
                            @if($esPorTiempo)
                            <div class="text-[10px] text-gray-400 italic">Desde: {{
                                \Carbon\Carbon::parse($jug->fecha_inicio)->format('d/m/Y') }}</div>
                            <div class="font-bold text-blue-600">{{
                                \Carbon\Carbon::parse($jug->fecha_inicio)->diffForHumans(\Carbon\Carbon::parse($jug->fecha_fin),
                                true) }}</div>
                            <div class="text-[10px] text-gray-400 italic">Hasta: {{
                                \Carbon\Carbon::parse($jug->fecha_fin)->format('d/m/Y') }}</div>
                            @else
                            <span class="font-bold text-lg">{{ $jug->partidos_sancionados }}</span> <span
                                class="text-xs text-gray-400">fec.</span>
                            @endif
                        </td>

                        {{-- Columna Estado --}}
                        <td class="px-4 py-2 text-center">
                            @if($esVigente)
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                <span class="w-2 h-2 mr-1 bg-red-500 rounded-full animate-pulse"></span>
                                VIGENTE {{ !$esPorTiempo ? "($jug->partidos_cumplidos cumplidas)" : '' }}
                            </span>
                            @else
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                CUMPLIDA
                            </span>
                            @endif
                        </td>

                        <td class="px-4 py-2 text-xs">{{ $jug->observacion }}</td>
                    </tr>

                    {{-- Fila Móvil --}}
                    <tr
                        class="sm:hidden border-b dark:border-gray-700 {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }} dark:bg-gray-800">
                        <td colspan="7" class="px-4 py-3">
                            <div class="space-y-2">
                                <div
                                    class="flex justify-between items-start font-bold text-blue-600 dark:text-blue-400">
                                    <span class="uppercase">{{ $jug->jugador->apellido }}, {{ $jug->jugador->nombre
                                        }}</span>
                                    <span
                                        class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-gray-600 dark:text-gray-300">DNI:
                                        {{ $jug->jugador->documento }}</span>
                                </div>

                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-500"><strong>Camp:</strong> {{ $jug->campeonato->nombre
                                        }}</span>
                                    @if($esVigente)
                                    <span class="text-red-600 font-bold animate-pulse">● VIGENTE</span>
                                    @else
                                    <span class="text-green-600 font-bold">✓ CUMPLIDA</span>
                                    @endif
                                </div>

                                <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded text-sm">
                                    @if($esPorTiempo)
                                    <p><strong>Duración:</strong> {{
                                        \Carbon\Carbon::parse($jug->fecha_inicio)->diffForHumans(\Carbon\Carbon::parse($jug->fecha_fin),
                                        true) }}</p>
                                    <p class="text-xs">Vence: {{ \Carbon\Carbon::parse($jug->fecha_fin)->format('d/m/Y')
                                        }}</p>
                                    @else
                                    <p><strong>Sanción:</strong> {{ $jug->partidos_sancionados }} fechas</p>
                                    <p><strong>Cumplidas:</strong> {{ $jug->partidos_cumplidos }}</p>
                                    @endif
                                </div>

                                @if($jug->observacion)
                                <div class="text-xs italic text-gray-500"><strong>Nota:</strong> {{ $jug->observacion }}
                                </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-gray-500">No se encontraron resultados.</td>
                    </tr>
                    @endforelse
            </tbody>
        </table>

        @if ($sanciones->hasPages())
        <div class="p-4 border-t">{{ $sanciones->links() }}</div>
        @endif
    </div>
</div>