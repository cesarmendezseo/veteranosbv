<div class="w-full mt-6 font-titulo">

    <!-- Contenedor vidrioso principal -->
    <div
        class="p-4 sm:p-6 rounded-3xl bg-white/10 backdrop-blur-xl border border-white/20 shadow-[0_0_25px_rgba(0,0,0,0.4)]">

        <!-- Título -->
        <div class="p-4 sm:p-6 rounded-3xl text-center mb-6">
            <h1
                class="text-2xl md:text-4xl font-extrabold text-black dark:text-white px-6 py-3 rounded-2xl inline-block">
                📅 PRÓXIMOS ENCUENTROS
            </h1>
        </div>

        <!-- Filtro de Jornada -->
        <div class="mb-8">
            <div class="flex justify-center">
                <div class="w-full md:w-72">
                    <label class="block text-sm font-bold text-black dark:text-white mb-2">Filtrar Jornada</label>
                    <select wire:model.live="jornadaSeleccionada"
                        class="w-full bg-white/10 backdrop-blur-md border border-gray-400 text-black dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-2.5 transition-all">
                        <option value="">Próximos Encuentros</option>
                        @foreach($jornadasDisponibles as $jornada)
                        <option value="{{ $jornada }}">{{ $jornada }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- LEYENDA VACÍA --}}
        @if($proximos->count() == 0 && $proximosEliminatorias->count() == 0)
        <div
            class="text-center py-8 text-black/70 dark:text-white/70 font-semibold bg-white/5 backdrop-blur-md rounded-2xl border border-white/20 p-6">
            <div class="text-6xl mb-4">📭</div>
            <h3 class="text-xl font-bold text-black dark:text-white mb-2">Sin partidos a la vista</h3>
            <p class="text-black/60 dark:text-white/60">Vuelve pronto para ver la actualización del fixture.</p>
        </div>
        @endif

        {{-- 📌 Versión Escritorio --}}
        @if($proximos->count() > 0)
        <div class="hidden sm:block overflow-x-auto rounded-2xl shadow-[0_0_20px_rgba(0,0,0,0.3)]">
            <table class="w-full text-sm text-center text-white/90 bg-white/5 backdrop-blur-xl">

                <!-- CABECERA -->
                <thead
                    class="bg-gradient-to-r from-blue-600/60 to-gray-600/50 text-black dark:text-white uppercase text-xs border-b border-white/20">
                    <tr>
                        <th class="px-4 py-3 text-left">Campeonato</th>
                        <th class="px-4 py-3 text-left">Fecha</th>
                        <th class="px-4 py-3 text-center">Equipo Local</th>
                        <th class="px-4 py-3 text-center">VS</th>
                        <th class="px-4 py-3 text-center">Equipo Visitante</th>
                    </tr>
                </thead>

                <!-- BODY -->
                <tbody class="backdrop-blur-xl">
                    @foreach($proximos as $partido)
                    <tr
                        class="text-black dark:text-white border-b border-white/10 hover:bg-gray-300 transition odd:bg-gray-50 even:bg-gray-100">

                        <!-- CAMPEONATO -->
                        <td class="px-4 py-3 text-left font-semibold tracking-wide">
                            {{ strtoupper($partido->campeonato->nombre) }}
                        </td>

                        <!-- FECHA -->
                        <td class="px-4 py-3 text-left text-black/80 dark:text-white/80">
                            {{ $partido->fecha_encuentro }}
                        </td>

                        <!-- EQUIPO LOCAL -->
                        <td class="px-4 py-3 text-center font-bold">
                            {{ strtoupper($partido->equipoLocal->nombre) }}
                        </td>

                        <!-- VS -->
                        <td class="py-3">
                            <span
                                class="w-10 h-10 flex items-center justify-center mx-auto rounded-full bg-gradient-to-tr from-blue-600 to-blue-400 text-white font-bold shadow-[0_0_10px_rgba(59,130,246,0.5)]">
                                VS
                            </span>
                        </td>

                        <!-- EQUIPO VISITANTE -->
                        <td class="px-4 py-3 text-center font-bold">
                            {{ strtoupper($partido->equipoVisitante->nombre) }}
                        </td>

                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
        @endif

        {{-- 📱 Versión Móvil --}}
        @if($proximos->count() > 0)
        <div class="sm:hidden space-y-4">
            @foreach($proximos as $partido)
            <div
                class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl overflow-hidden shadow-[0_0_20px_rgba(0,0,0,0.3)] hover:bg-white/15 hover:shadow-[0_0_30px_rgba(59,130,246,0.4)] transition-all duration-300">

                <!-- Encabezado con campeonato -->
                <div class="bg-gradient-to-r from-blue-600/60 to-gray-600/50 p-4 border-b border-white/20">
                    <h3 class="font-bold text-sm text-black dark:text-white uppercase">
                        {{ strtoupper($partido->campeonato->nombre) }}
                    </h3>
                    <p class="text-xs text-black/70 dark:text-white/70 mt-1">
                        📅 Fecha: {{ $partido->fecha_encuentro }}
                    </p>
                </div>

                <!-- Contenido con equipos -->
                <div class="p-4">

                    <!-- Equipos enfrentados -->
                    <div class="flex items-center justify-between gap-3">

                        <!-- Equipo Local -->
                        <div
                            class="flex-1 bg-white/5 backdrop-blur-md rounded-xl p-4 border border-white/10 text-center">


                            <p class="text-sm font-bold text-black dark:text-white">
                                {{ strtoupper($partido->equipoLocal->nombre) }}
                            </p>
                        </div>

                        <!-- VS -->
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 rounded-full bg-gradient-to-tr from-blue-600 to-blue-400 flex items-center justify-center shadow-[0_0_15px_rgba(59,130,246,0.5)]">
                                <span class="text-xs font-black text-white">VS</span>
                            </div>
                        </div>

                        <!-- Equipo Visitante -->
                        <div
                            class="flex-1 bg-white/5 backdrop-blur-md rounded-xl p-4 border border-white/10 text-center">


                            <p class="text-sm font-bold text-black dark:text-white">
                                {{ strtoupper($partido->equipoVisitante->nombre) }}
                            </p>
                        </div>

                    </div>

                </div>

            </div>
            @endforeach
        </div>
        @endif

        {{-- SECCIÓN ELIMINATORIAS --}}
        @if($proximosEliminatorias->count() > 0)
        <div class="mt-8">
            <h2 class="text-xl font-bold text-black dark:text-white mb-4 text-center">⚔️ ELIMINATORIAS</h2>

            <!-- Aquí puedes agregar la tabla o cards de eliminatorias con el mismo diseño -->
            <div class="sm:hidden space-y-4">
                @foreach($proximosEliminatorias as $partido)
                <div
                    class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl overflow-hidden shadow-[0_0_20px_rgba(0,0,0,0.3)] hover:bg-white/15 hover:shadow-[0_0_30px_rgba(255,0,0,0.4)] transition-all duration-300">

                    <!-- Encabezado con campeonato -->
                    <div class="bg-gradient-to-r from-red-600/60 to-orange-600/50 p-4 border-b border-white/20">
                        <h3 class="font-bold text-sm text-black dark:text-white uppercase">
                            {{ strtoupper($partido->campeonato->nombre) }}
                        </h3>
                        <p class="text-xs text-black/70 dark:text-white/70 mt-1">
                            📅 {{ $partido->fecha_encuentro }}
                        </p>
                    </div>

                    <!-- Contenido con equipos -->
                    <div class="p-4">

                        <!-- Equipos enfrentados -->
                        <div class="flex items-center justify-between gap-3">

                            <!-- Equipo Local -->
                            <div
                                class="flex-1 bg-white/5 backdrop-blur-md rounded-xl p-4 border border-white/10 text-center">

                                <span
                                    class="block text-xs font-semibold text-black/70 dark:text-white/70 mb-2">Local</span>
                                <p class="text-sm font-bold text-black dark:text-white">
                                    {{ strtoupper($partido->equipoLocal->nombre) }}
                                </p>
                            </div>

                            <!-- VS -->
                            <div class="flex-shrink-0">
                                <div
                                    class="w-12 h-12 rounded-full bg-gradient-to-tr from-red-600 to-orange-400 flex items-center justify-center shadow-[0_0_15px_rgba(220,38,38,0.5)]">
                                    <span class="text-xs font-black text-white">VS</span>
                                </div>
                            </div>

                            <!-- Equipo Visitante -->
                            <div
                                class="flex-1 bg-white/5 backdrop-blur-md rounded-xl p-4 border border-white/10 text-center">

                                <span
                                    class="block text-xs font-semibold text-black/70 dark:text-white/70 mb-2">Visitante</span>
                                <p class="text-sm font-bold text-black dark:text-white">
                                    {{ strtoupper($partido->equipoVisitante->nombre) }}
                                </p>
                            </div>

                        </div>

                    </div>

                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

</div>