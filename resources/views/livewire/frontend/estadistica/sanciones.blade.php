<div>
    <div class="space-y-4">
        {{-- Filtros --}}

        <div
            class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-gray-200 dark:bg-gray-700 p-4 rounded-lg shadow-md">
            {{-- Nombre del campeonato --}}


            {{-- Filtro --}}
            <div class="w-full sm:w-1/2">
                <input type="text" wire:model.live="search" placeholder="Buscar jugador por nombre o documento"
                    class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300" />
            </div>
        </div>
        {{-- Tabla --}}
        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="w-full text-sm sm:text-base text-left rtl:text-right text-gray-500 dark:text-gray-100">
                <thead
                    class="text-xs sm:text-sm text-gray-50 uppercase bg-gray-700 dark:bg-gray-700 dark:text-[#FFC107]">
                    <tr>

                        <th class="px-2 sm:px-4 py-2 text-left hidden sm:table-cell">Jugador</th>
                        <th class="px-2 sm:px-4 py-2 text-center hidden sm:table-cell">Fecha Sanción</th>
                        <th class="px-2 sm:px-4 py-2 text-center hidden sm:table-cell">Partido</th>
                        <th class="px-2 sm:px-4 py-2 text-center hidden sm:table-cell">Motivo</th>
                        <th class="px-2 sm:px-4 py-2 text-center hidden sm:table-cell">Fechas</th>
                        <th class="px-2 sm:px-4 py-2 text-center hidden sm:table-cell">Cumplidas</th>
                        {{-- <th class="px-2 sm:px-4 py-2 text-center hidden sm:table-cell">Detalle</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse($sanciones as $index => $jug)
                    <tr
                        class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">

                        <td class="px-2 sm:px-4 py-2 hidden sm:table-cell">
                            {{ strtoupper($jug->jugador->apellido) }}, {{ strtoupper($jug->jugador->nombre) }}
                        </td>
                        <td class="px-2 sm:px-4 py-2 text-center hidden sm:table-cell">
                            {{ ucfirst($jug->etapa_sancion) }}
                        </td>
                        <td class="px-2 sm:px-4 py-2 text-center hidden sm:table-cell">
                            @if($jug->sancionable)
                            {{ strtoupper($jug->sancionable->equipoLocal->nombre) }} vs {{
                            strtoupper($jug->sancionable->equipoVisitante->nombre) }}
                            @else
                            <em>Sin partido</em>
                            @endif
                        </td>
                        <td class="px-2 sm:px-4 py-2 text-center hidden sm:table-cell">{{ ucwords($jug->motivo) }}</td>
                        <td class="px-2 sm:px-4 py-2 text-center hidden sm:table-cell">{{ $jug->partidos_sancionados }}
                        </td>
                        <td class="px-2 sm:px-4 py-2 text-center hidden sm:table-cell">{{ $jug->partidos_cumplidos }}
                        </td>
                        {{-- <td class="px-2 sm:px-4 py-2 text-center hidden sm:table-cell">{{
                            ucfirst($jug->observacion) }}
                        </td> --}}
                    </tr>

                    {{-- Fila adicional para móviles --}}
                    <tr class="sm:hidden my-3">
                        <td colspan="8" class="px-2 py-3">
                            <div class="rounded-xl shadow-2xl overflow-hidden ">

                                <!-- Encabezado -->
                                <div class="p-2 font-front  text-sm bg-blue-900 text-white">

                                    {{ strtoupper($jug->jugador->apellido) }}, {{ strtoupper($jug->jugador->nombre) }}
                                </div>

                                <!-- Contenido -->
                                <div class=" p-3 bg-blue-600 space-y-2 text-sm text-gray-100">

                                    <!-- Fila 1 -->
                                    <div class="flex justify-between">
                                        <span class="font-front">Fecha Sanción:</span>
                                        <span>{{ ucfirst($jug->etapa_sancion) }}</span>
                                    </div>

                                    <!-- Fila 2 -->
                                    <div class="flex justify-between">
                                        <span class="font-front">Motivo:</span>
                                        <span class="uppercase">{{ $jug->motivo }}</span>
                                    </div>

                                    <!-- Fila 3 -->
                                    <div class="flex justify-between">
                                        <span class="font-front">Fechas:</span>
                                        <span>{{ $jug->partidos_sancionados }}</span>
                                    </div>

                                    <!-- Fila 4 -->
                                    <div class="flex justify-between">
                                        <span class="font-front">Cumplidas:</span>
                                        <span>{{ $jug->partidos_cumplidos }}</span>
                                    </div>

                                    <!-- Partido -->
                                    <div class="pt-2 border-t">
                                        <span class="font-front">Partido:</span>
                                        @if($jug->sancionable)
                                        {{ strtoupper($jug->sancionable->equipoLocal->nombre) }} vs
                                        {{ strtoupper($jug->sancionable->equipoVisitante->nombre) }}
                                        @else
                                        <em>Sin partido</em>
                                        @endif
                                    </div>

                                    @if ($jug->observacion)
                                    <div class="pt-2 border-t">
                                        <span class="font-front">Detalle:</span> {{ strtoupper($jug->observacion) }}
                                    </div>
                                    @endif

                                </div>
                            </div>
                        </td>
                    </tr>


                    {{-- ////////////////////// --}}
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
</div>