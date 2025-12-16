<div class="w-full mt-6 font-titulo">

    <!-- Contenedor vidrioso principal -->
    <div
        class="p-4 sm:p-6 rounded-3xl bg-white/10 backdrop-blur-xl border border-white/20 shadow-[0_0_25px_rgba(0,0,0,0.4)]">

        <!-- Título -->
        <div class="p-4 sm:p-6 rounded-3xl text-center mb-6">

            <h1
                class="text-3xl md:text-4xl font-extrabold text-black dark:text-white px-6 py-3 rounded-2xl inline-block">
                ⚠️ SANCIONES
            </h1>
        </div>

        {{-- Filtro de búsqueda --}}
        <div class="mb-6">
            <div
                class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/20 shadow-[0_0_15px_rgba(0,0,0,0.3)]">
                <input type="text" wire:model.live="search" placeholder="🔍 Buscar jugador por nombre o documento"
                    class="w-full px-4 py-3 bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl text-black dark:text-white placeholder-black/60 dark:placeholder-white/60 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-[0_0_10px_rgba(0,0,0,0.2)]" />
            </div>
        </div>

        {{-- 📌 Versión Escritorio --}}
        <div class="hidden sm:block overflow-x-auto rounded-2xl shadow-[0_0_20px_rgba(0,0,0,0.3)]">
            <table class="w-full text-sm text-center text-white/90 bg-white/5 backdrop-blur-xl">

                <!-- CABECERA -->
                <thead
                    class="bg-gradient-to-r from-orange-700/60 to-orange-500/50 text-black dark:text-white uppercase text-xs border-b border-white/20">
                    <tr>
                        <th class="px-4 py-3 text-left">Jugador</th>
                        <th class="px-4 py-3 text-center">Fecha Sanción</th>
                        <th class="px-4 py-3 text-center">Partido</th>
                        <th class="px-4 py-3 text-center">Motivo</th>
                        <th class="px-4 py-3 text-center">Fechas</th>
                        <th class="px-4 py-3 text-center">Cumplidas</th>
                    </tr>
                </thead>

                <!-- BODY -->
                <tbody class="backdrop-blur-xl">
                    @forelse($sanciones as $sancion)
                    <tr class="text-black dark:text-white border-b border-white/10 hover:bg-white/10 transition">

                        <!-- JUGADOR -->
                        <td class="px-4 py-3 text-left font-semibold tracking-wide">
                            {{ strtoupper($sancion->jugador->apellido) }}, {{ strtoupper($sancion->jugador->nombre) }}
                        </td>

                        <!-- FECHA SANCIÓN -->
                        <td class="px-4 py-3 text-center">
                            <span
                                class="inline-block bg-blue-600/80 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-[0_0_8px_rgba(0,150,255,0.5)]">
                                {{ ucfirst($sancion->etapa_sancion) }}
                            </span>
                        </td>

                        <!-- PARTIDO -->
                        <td class="px-4 py-3 text-center text-sm">
                            @if($sancion->sancionable)
                            {{ strtoupper($sancion->sancionable->equipoLocal->nombre) }} vs {{
                            strtoupper($sancion->sancionable->equipoVisitante->nombre) }}
                            @else
                            <em class="text-black/50 dark:text-white/50">Sin partido</em>
                            @endif
                        </td>


                        <td class="px-4 py-3 text-center">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-white
        @if(strtolower($sancion->motivo) === 'roja' || str_contains(strtolower($sancion->motivo), 'roja'))
            bg-red-600/80 shadow-[0_0_8px_rgba(255,0,0,0.5)]
        @elseif(strtolower($sancion->motivo) === 'amarilla' || strtolower($sancion->motivo) === 'doble amarilla' || str_contains(strtolower($sancion->motivo), 'amarilla'))
            bg-yellow-500/80 shadow-[0_0_8px_rgba(255,200,0,0.5)] text-black
        @else
            bg-gray-600/80 shadow-[0_0_8px_rgba(100,100,100,0.5)]
        @endif
    ">
                                {{ ucwords($sancion->motivo) }}
                            </span>
                        </td>

                        <!-- FECHAS SANCIONADAS -->
                        <td class="py-3">
                            <span
                                class="w-10 h-10 flex items-center justify-center mx-auto rounded-full bg-orange-600 text-white font-bold shadow-[0_0_10px_rgba(255,150,0,0.6)]">
                                {{ $sancion->partidos_sancionados }}
                            </span>
                        </td>

                        <!-- FECHAS CUMPLIDAS -->
                        <td class="py-3">
                            <span class="w-10 h-10 flex items-center justify-center mx-auto rounded-full 
                                {{ $sancion->partidos_cumplidos >= $sancion->partidos_sancionados 
                                    ? 'bg-emerald-600 shadow-[0_0_10px_rgba(0,255,180,0.6)]' 
                                    : 'bg-gray-600' }} 
                                text-white font-bold">
                                {{ $sancion->partidos_cumplidos }}
                            </span>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-black/70 dark:text-white/70 font-semibold">
                            @if ($search)
                            No se encontraron sanciones para <strong>"{{ $search }}"</strong>.
                            @else
                            No hay sanciones registradas.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- 📱 Versión Móvil --}}
        <div class="sm:hidden space-y-4">
            @forelse($sanciones as $sancion)
            <div
                class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl overflow-hidden shadow-[0_0_20px_rgba(0,0,0,0.3)] hover:bg-white/15 hover:shadow-[0_0_30px_rgba(255,100,0,0.4)] transition-all duration-300">

                <!-- Encabezado con nombre del jugador -->
                <div class="bg-gradient-to-r from-orange-700/60 to-orange-500/50 p-4 border-b border-white/20">
                    <h3 class="font-bold text-lg text-black dark:text-white">
                        {{ strtoupper($sancion->jugador->apellido) }}, {{ strtoupper($sancion->jugador->nombre) }}
                    </h3>
                </div>

                <!-- Contenido -->
                <div class="p-4 space-y-3">

                    <!-- Fecha Sanción -->
                    <div
                        class="flex justify-between items-center bg-white/5 backdrop-blur-md rounded-xl p-3 border border-white/10">
                        <span class="text-sm font-semibold text-black/70 dark:text-white/70">Fecha Sanción:</span>
                        <span
                            class="inline-block bg-blue-600/80 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-[0_0_8px_rgba(0,150,255,0.5)]">
                            {{ ucfirst($sancion->etapa_sancion) }}
                        </span>
                    </div>

                    <!-- Motivo -->
                    <div
                        class="flex justify-between items-center bg-white/5 backdrop-blur-md rounded-xl p-3 border border-white/10">
                        <span class="text-sm font-semibold text-black/70 dark:text-white/70">Motivo:</span>
                        <span
                            class="inline-block bg-red-600/80 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-[0_0_8px_rgba(255,0,0,0.5)]">
                            {{ ucwords($sancion->motivo) }}
                        </span>
                    </div>

                    <!-- Fechas vs Cumplidas -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-white/5 backdrop-blur-md rounded-xl p-3 border border-white/10 text-center">
                            <span
                                class="block text-xs font-semibold text-black/70 dark:text-white/70 mb-2">Fechas</span>
                            <span
                                class="w-12 h-12 flex items-center justify-center mx-auto rounded-full bg-orange-600 text-white font-bold text-lg shadow-[0_0_10px_rgba(255,150,0,0.6)]">
                                {{ $sancion->partidos_sancionados }}
                            </span>
                        </div>
                        <div class="bg-white/5 backdrop-blur-md rounded-xl p-3 border border-white/10 text-center">
                            <span
                                class="block text-xs font-semibold text-black/70 dark:text-white/70 mb-2">Cumplidas</span>
                            <span class="w-12 h-12 flex items-center justify-center mx-auto rounded-full 
                                {{ $sancion->partidos_cumplidos >= $sancion->partidos_sancionados 
                                    ? 'bg-emerald-600 shadow-[0_0_10px_rgba(0,255,180,0.6)]' 
                                    : 'bg-gray-600' }} 
                                text-white font-bold text-lg">
                                {{ $sancion->partidos_cumplidos }}
                            </span>
                        </div>
                    </div>

                    <!-- Partido -->
                    <div class="bg-white/5 backdrop-blur-md rounded-xl p-3 border border-white/10">
                        <span class="block text-xs font-semibold text-black/70 dark:text-white/70 mb-2">Partido:</span>
                        <div class="text-sm font-semibold text-black dark:text-white text-center">
                            @if($sancion->sancionable)
                            {{ strtoupper($sancion->sancionable->equipoLocal->nombre) }}
                            vs
                            {{ strtoupper($sancion->sancionable->equipoVisitante->nombre) }}
                            @else
                            <em class="text-black/50">Sin partido</em>
                            @endif

                        </div>

                    </div>

                    @if($sancion->observacion)
                    <!-- Observación (opcional) -->
                    <div class="bg-white/5 backdrop-blur-md rounded-xl p-3 border border-white/10">
                        <span class="block text-xs font-semibold text-black/70 dark:text-white/70 mb-1">Detalle:</span>
                        <p class="text-sm text-black dark:text-white">{{ ucfirst($sancion->observacion) }}</p>
                    </div>
                    @endif

                </div>

            </div>
            @empty
            <div
                class="text-center py-8 text-black/70 dark:text-white/70 font-semibold bg-white/5 backdrop-blur-md rounded-2xl border border-white/20 p-6">
                @if ($search)
                No se encontraron sanciones para <strong>"{{ $search }}"</strong>.
                @else
                No hay sanciones registradas.
                @endif
            </div>
            @endforelse
        </div>

        {{-- 📄 Paginación --}}
        @if($sanciones->hasPages())
        <div class="mt-8">
            {{ $sanciones->links() }}
        </div>
        @endif

    </div>

</div>