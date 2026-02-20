<div class="w-full mt-6 font-titulo">

    <!-- Contenedor vidrioso principal -->
    <div
        class="p-4 sm:p-6 rounded-3xl bg-white/10 backdrop-blur-xl border border-white/20 shadow-[0_0_25px_rgba(0,0,0,0.4)]">

        <!-- Título -->
        <div class="p-4 sm:p-6 rounded-3xl text-center mb-6">
            <h1
                class="text-3xl md:text-4xl font-extrabold text-black dark:text-white px-6 py-3 rounded-2xl inline-block">
                ⚽ GOLEADORES
            </h1>

        </div>

        {{-- 📌 Versión Escritorio --}}
        <div class="hidden sm:block overflow-x-auto rounded-2xl shadow-[0_0_20px_rgba(0,0,0,0.3)]">
            <table class="w-full text-sm text-center text-white/90 bg-white/5 backdrop-blur-xl">

                <!-- CABECERA -->
                <thead
                    class="bg-gradient-to-r from-blue-700/60 to-blue-500/50 text-black dark:text-white uppercase text-xs border-b border-white/20">
                    <tr>
                        <th class="px-2 py-3">#</th>
                        <th class="px-4 py-3 text-left">Jugador</th>
                        <th class="px-4 py-3 text-left">Equipo</th>
                        <th class="px-4 py-3 text-center">Goles</th>
                    </tr>
                </thead>

                <!-- BODY -->
                <tbody class="backdrop-blur-xl">
                    @forelse($goleadores as $index => $gol)
                    <tr class="text-black dark:text-white border-b border-white/10 hover:bg-white/10 transition">

                        <!-- POSICIÓN -->
                        <td class="py-3">
                            <span
                                class="w-8 h-8 text-sm flex items-center justify-center mx-auto rounded-full bg-blue-600 text-white font-bold shadow-[0_0_10px_rgba(0,150,255,0.7)]">
                                {{ $goleadores->firstItem() + $index }}
                            </span>
                        </td>

                        <!-- JUGADOR -->
                        <td class="px-4 py-3 text-left font-semibold tracking-wide">
                            {{ strtoupper($gol->jugador->apellido) }}, {{ strtoupper($gol->jugador->nombre) }}
                        </td>

                        <!-- EQUIPO -->
                        <td class="px-4 py-3 text-left text-black/80 dark:text-white/80">
                            {{ $gol->jugador->equipo->nombre ?? '-' }}
                        </td>

                        <!-- GOLES -->
                        <td class="py-3">
                            <span
                                class="w-12 h-12 flex items-center justify-center mx-auto rounded-full bg-emerald-600 text-white font-bold text-lg shadow-[0_0_10px_rgba(0,255,180,0.6)]">
                                {{ $gol->total_goles }}
                            </span>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-black/70 dark:text-white/70 font-semibold">
                            No hay goles registrados en este campeonato.
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- 📱 Versión Móvil --}}
        <div class="sm:hidden space-y-4">
            @forelse($goleadores as $index => $gol)
            <div
                class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl p-5 shadow-[0_0_20px_rgba(0,0,0,0.3)] hover:bg-white/15 hover:shadow-[0_0_30px_rgba(0,150,255,0.4)] transition-all duration-300">

                <!-- Top: Posición y Goles -->
                <div class="flex justify-between items-center mb-4">

                    <!-- Posición -->
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-600 text-white font-bold shadow-[0_0_10px_rgba(0,150,255,0.7)]">
                        {{ $goleadores->firstItem() + $index }}
                    </div>

                    <!-- Goles -->
                    <div
                        class="w-14 h-14 flex items-center justify-center rounded-full bg-emerald-600 text-white font-bold text-2xl shadow-[0_0_10px_rgba(0,255,180,0.6)]">
                        {{ $gol->total_goles }}
                    </div>

                </div>

                <!-- Nombre del Jugador -->
                <div class="mb-3">
                    <h2 class="text-xl font-extrabold text-black dark:text-white leading-tight">
                        {{ strtoupper($gol->jugador->apellido) }},
                        <span class="font-semibold">{{ strtoupper($gol->jugador->nombre) }}</span>
                    </h2>
                </div>

                <!-- Separador -->
                <div class="border-t border-white/20 my-3"></div>

                <!-- Equipo -->
                <div
                    class="flex items-center justify-between bg-white/5 backdrop-blur-md rounded-xl p-3 border border-white/10">
                    <span class="text-sm font-semibold text-black/70 dark:text-white/70">
                        Equipo:
                    </span>

                    {{ $gol->jugador->equiposPorCampeonato->first()?->nombre ?? '-' }}
                </div>

            </div>
            @empty
            <div
                class="text-center py-8 text-black/70 dark:text-white/70 font-semibold bg-white/5 backdrop-blur-md rounded-2xl border border-white/20 p-6">
                No hay goles registrados en este campeonato.
            </div>
            @endforelse
        </div>

        {{-- 📄 Paginación --}}
        @if($goleadores->hasPages())
        <div class="mt-8">
            {{ $goleadores->links() }}
        </div>
        @endif

    </div>

</div>