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

        {{-- 1. BOTONES DE FECHAS --}}
        <div class="mb-6">
            <button
                wire:click="setJornada(null)"
                class="cursor-pointer px-4 py-2 rounded-lg text-sm font-medium transition-all {{ is_null($jornadaSeleccionada) ? 'bg-blue-600 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700' }}">
                Todas
            </button>

            @foreach($botonesJornadas as $jornada)
            <button
                wire:key="jornada-btn-{{ $jornada }}"
                wire:click="setJornada('{{ $jornada }}')"
                class="cursor-pointer px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $jornadaSeleccionada == $jornada ? 'bg-blue-600 text-white ring-2 ring-blue-400' : 'bg-gray-800 text-gray-400 hover:bg-gray-700' }}">
                {{ $jornada }}
            </button>
            @endforeach
        </div>

        {{-- Filtro de búsqueda --}}
        <div class="mb-6 flex flex-col md:flex-row gap-4 items-center">


            <!-- Buscador -->
            <input type="text"
                wire:model.live="search"
                placeholder="🔍 Buscar jugador por nombre o documento"
                class="flex-1 px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-xl text-black dark:text-white placeholder-black/60 dark:placeholder-white/60 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-[0_0_10px_rgba(0,0,0,0.2)]" />

        </div>



        {{-- 📌 Versión Escritorio --}}
        {{-- Loop único responsive --}}
        <div class="space-y-4">
            @forelse($sanciones as $sancion)
            @php
            \Carbon\Carbon::setLocale('es');
            $esPorTiempo = $sancion->partidos_sancionados === 0;
            $esVigente = $esPorTiempo
            ? ($sancion->fecha_fin && \Carbon\Carbon::parse($sancion->fecha_fin)->isFuture())
            : ($sancion->partidos_cumplidos < $sancion->partidos_sancionados);
                @endphp

                <div wire:key="sancion-{{ $sancion->id }}"
                    class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl overflow-hidden shadow-lg hover:bg-white/15 transition-all duration-300">

                    {{-- Header --}}
                    <div class="bg-gradient-to-r from-orange-700/60 to-orange-500/50 p-4 border-b border-white/20 flex flex-wrap items-center justify-between gap-2">
                        <h3 class="font-bold text-lg text-black dark:text-white">
                            {{ strtoupper($sancion->jugador->apellido) }}, {{ strtoupper($sancion->jugador->nombre) }}
                        </h3>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="px-3 py-1 text-xs bg-gradient-to-r from-indigo-500 to-blue-500 text-white rounded-full shadow-md">
                                {{ strtoupper($sancion->jugador->equipo?->nombre) }}
                            </span>
                            <span class="inline-block bg-blue-600/80 text-white px-3 py-1 rounded-full text-xs font-semibold">
                                {{ ucfirst($sancion->etapa_sancion) }}
                            </span>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="p-4 grid grid-cols-2 md:grid-cols-4 gap-3">

                        {{-- Partido --}}
                        <div class="col-span-2 md:col-span-2 bg-white/5 rounded-xl p-3 border border-white/10 flex flex-col justify-center text-center">
                            <span class="text-xs font-semibold text-black/70 dark:text-white/70 mb-1">Partido</span>
                            <span class="text-sm font-bold text-black dark:text-white">
                                @if($sancion->sancionable)
                                {{ strtoupper($sancion->sancionable->equipoLocal->nombre) }} vs {{ strtoupper($sancion->sancionable->equipoVisitante->nombre) }}
                                @else
                                <em class="text-black/50 dark:text-white/50">Sin partido</em>
                                @endif
                            </span>
                        </div>

                        {{-- Sancionados / Tiempo --}}
                        @if($esPorTiempo)
                        <div class="col-span-2 md:col-span-1 flex flex-col items-center justify-center bg-blue-50 border border-blue-200 rounded-xl px-4 py-2 shadow-sm border-l-4 border-l-blue-500 gap-0.5">
                            <div class="flex items-center gap-1 text-[10px] text-gray-400 italic">
                                Desde: {{ \Carbon\Carbon::parse($sancion->fecha_inicio)->format('d/m/Y') }}
                            </div>
                            <div class="flex items-center gap-1 px-3 py-0.5 bg-blue-500 text-white text-xs font-bold rounded-full shadow">
                                {{ \Carbon\Carbon::parse($sancion->fecha_inicio)->diffForHumans(\Carbon\Carbon::parse($sancion->fecha_fin), true) }}
                            </div>
                            <div class="flex items-center gap-1 text-[10px] text-gray-400 italic">
                                Hasta: {{ \Carbon\Carbon::parse($sancion->fecha_fin)->format('d/m/Y') }}
                            </div>
                        </div>
                        @else
                        <div class="flex flex-col items-center justify-center bg-white/5 rounded-xl p-3 border border-white/10 text-center">
                            <span class="text-xs font-semibold text-black/70 dark:text-white/70 mb-2">Sancionados</span>
                            <span class="w-12 h-12 flex items-center justify-center mx-auto rounded-full bg-orange-600 text-white font-bold text-lg shadow-[0_0_10px_rgba(255,150,0,0.6)]">
                                {{ $sancion->partidos_sancionados }}
                            </span>
                        </div>
                        @endif

                        {{-- Cumplidas --}}
                        <div class="flex flex-col items-center justify-center bg-white/5 rounded-xl p-3 border border-white/10 text-center">
                            <span class="text-xs font-semibold text-black/70 dark:text-white/70 mb-2">Cumplidas</span>
                            <span class="w-12 h-12 flex items-center justify-center mx-auto rounded-full
                    {{ $sancion->partidos_cumplidos >= $sancion->partidos_sancionados
                        ? 'bg-emerald-600 shadow-[0_0_10px_rgba(0,255,180,0.6)]'
                        : 'bg-gray-600' }}
                    text-white font-bold text-lg">
                                {{ $sancion->partidos_cumplidos }}
                            </span>
                        </div>

                    </div>
                </div>

                @empty
                <div class="text-center py-8 text-black/70 dark:text-white/70 font-semibold bg-white/5 backdrop-blur-md rounded-2xl border border-white/20 p-6">
                    @if ($search)
                    No se encontraron sanciones para <strong>"{{ $search }}"</strong>.
                    @else
                    No hay sanciones registradas.
                    @endif
                </div>
                @endforelse
        </div>

        <!-- {{-- 📄 Paginación --}} -->

        @if($sanciones->hasPages())
        <div class="mt-8">
            {{ $sanciones->links() }}
        </div>
        @endif

    </div>

</div>