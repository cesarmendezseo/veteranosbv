<div class="w-full mt-6">

    <!-- Contenedor vidrioso principal -->
    <div
        class="p-4 sm:p-6 rounded-3xl bg-white/10 backdrop-blur-xl border border-white/20 shadow-[0_0_25px_rgba(0,0,0,0.4)]">

        <!-- Título y acciones -->
        <div class="p-4 sm:p-6 rounded-3xl text-center mb-6 relative">
            <h1
                class="text-2xl md:text-4xl font-extrabold text-center text-black dark:text-white px-6 py-3 rounded-2xl inline-block">
                Listado de Buena Fé
            </h1>

            <!-- Botón de exportar (flotante en la esquina) -->

        </div>

        <!-- Select de equipos con estilo vidrioso -->
        <div
            class="mb-6 bg-white/10 backdrop-blur-md p-6 rounded-2xl border border-white/20 shadow-[0_0_20px_rgba(0,0,0,0.3)]">
            <label class="block mb-3 font-bold text-lg text-black dark:text-white tracking-wide">
                Seleccione un equipo
            </label>
            <select wire:model.live="equipoSeleccionado" class="w-full border border-blue-500/30 bg-white/20 backdrop-blur-md text-black dark:text-white rounded-xl p-3 
                           focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition">
                <option value="">-- Elegir --</option>
                @foreach ($equiposDelCampeonato as $equipo)
                <option value="{{ $equipo->id }}">{{ strtoupper($equipo->nombre) }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tabla de jugadores (vista escritorio) -->
        @if (!empty($jugadoresEquipos))


        <!-- VERSIÓN MÓVIL -->
        <div class="w-full mt-6 flex justify-center">

            <!-- Contenedor vidrioso principal con ancho máximo -->
            <div
                class="w-full max-w-6xl p-4 sm:p-6 rounded-3xl bg-white/10 backdrop-blur-xl border border-white/20 shadow-[0_0_25px_rgba(0,0,0,0.4)]">




                <!-- Tabla de jugadores (vista escritorio) -->
                @if (!empty($jugadoresEquipos))
                <div class="overflow-x-auto rounded-2xl shadow-[0_0_20px_rgba(0,0,0,0.3)] mb-10">
                    <div class="hidden lg:block">
                        <table
                            class="w-full text-sm text-center text-black dark:text-white bg-white/5 backdrop-blur-xl">

                            <!-- CABECERA -->
                            <thead
                                class="bg-gradient-to-r from-blue-700/60 to-blue-500/50 text-black dark:text-white uppercase text-xs border-b border-white/20">
                                <tr>
                                    <th class="px-4 py-3">#</th>
                                    <th class="px-4 py-3 text-left">Apellido</th>
                                    <th class="px-4 py-3 text-left">Nombre</th>
                                    <th class="px-4 py-3 text-left">Edad</th>

                                    <th class="px-4 py-3">Estado</th>
                                    <th class="px-4 py-3">Acción</th>
                                </tr>
                            </thead>

                            <!-- BODY -->
                            <tbody class="backdrop-blur-xl">
                                @foreach ($jugadoresEquipos as $jugador)
                                @php
                                $sancionesJugador = $jugador['sanciones']->where('cumplida', false);
                                @endphp
                                <tr class="border-b border-white/10 hover:bg-white/10 transition">

                                    <!-- POSICIÓN -->
                                    <td class="py-3">
                                        <span class="w-8 h-8 text-xs flex items-center justify-center mx-auto rounded-full
                                             bg-blue-600 text-white font-bold shadow-[0_0_10px_rgba(0,150,255,0.7)]">
                                            {{ $loop->iteration }}
                                        </span>
                                    </td>

                                    <!-- APELLIDO -->
                                    <td class="px-4 py-3 text-left font-semibold tracking-wide">
                                        {{ strtoupper($jugador['jugador']->apellido) }}
                                    </td>

                                    <!-- NOMBRE -->
                                    <td class="px-4 py-3 text-left font-semibold tracking-wide">
                                        {{ strtoupper($jugador['jugador']->nombre) }}
                                    </td>
                                    <td class="px-4 py-3 text-left font-semibold tracking-wide">
                                        {{ strtoupper($jugador['jugador']->edad) }}
                                    </td>


                                    <!-- ESTADO/SANCIONES -->
                                    <td class="py-3">
                                        @if ($sancionesJugador->isNotEmpty())
                                        @foreach ($sancionesJugador as $sancion)
                                        <div class="flex flex-col items-center gap-1 mb-2">
                                            <div
                                                class="flex items-center bg-red-500 text-white rounded-lg px-3 py-1 text-xs shadow-[0_0_10px_rgba(255,0,0,0.5)]">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="mr-1">
                                                    <path
                                                        d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
                                                    <path d="m4.243 5.21 14.39 12.472" />
                                                </svg>
                                                Sancionado
                                            </div>
                                            <span class="text-xs">
                                                @if($sancion->periodo_texto)
                                                <strong>{{ $sancion->periodo_texto }}</strong>
                                                <br>
                                                <small class="text-[10px] opacity-80">
                                                    ({{ \Carbon\Carbon::parse($sancion->fecha_inicio)->format('d/m/Y')
                                                    }} -
                                                    {{ \Carbon\Carbon::parse($sancion->fecha_fin)->format('d/m/Y') }})
                                                </small>
                                                @else
                                                {{ $sancion->partidos_sancionados }} fechas, cumple: {{
                                                $sancion->partidos_cumplidos }}
                                                @endif
                                            </span>
                                        </div>
                                        @endforeach
                                        @else
                                        <div
                                            class="inline-flex items-center bg-emerald-600 text-white rounded-lg px-3 py-1 text-xs shadow-[0_0_10px_rgba(0,255,180,0.6)]">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                                <path
                                                    d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
                                                <path d="m9 12 2 2 4-4" />
                                            </svg>
                                            Habilitado
                                        </div>
                                        @endif
                                    </td>


                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>

                <!-- VERSIÓN MÓVIL -->
                <div class="lg:hidden space-y-4">
                    @foreach ($jugadoresEquipos as $jugador)
                    @php
                    $sancionesJugador = $jugador['sanciones']->where('cumplida', false);
                    $jugadorModel = $jugador['jugador'];
                    @endphp

                    <div
                        class="bg-white/10 backdrop-blur-xl border border-white/20 shadow-[0_0_15px_rgba(0,0,0,0.3)] rounded-2xl p-5">

                        <!-- Header del jugador -->
                        <div class="flex items-center border-b border-white/20 pb-3 mb-3">

                            <!-- Número de posición -->
                            <div class="w-6 h-6 flex items-center mr-2 justify-center rounded-full
                                bg-blue-600 text-white font-bold shadow-[0_0_10px_rgba(0,150,255,0.7)]">
                                {{ $loop->iteration }}
                            </div>
                            <div class="space-y-1">
                                <div class="text-lg font-bold text-black dark:text-white">
                                    {{ strtoupper($jugadorModel->apellido) }}, {{ strtoupper($jugadorModel->nombre) }}
                                </div>
                                <td class="px-4 py-3 text-left font-semibold tracking-wide">
                                    Edad: {{ strtoupper($jugador['jugador']->edad) }}
                                </td>

                            </div>

                        </div>

                        <!-- Estado del jugador -->
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center justify-center">
                                <div class="text-center w-full">
                                    @if ($sancionesJugador->isNotEmpty())
                                    @foreach ($sancionesJugador as $sancion)
                                    <div class="space-y-2">
                                        <div
                                            class="inline-flex items-center bg-red-500 text-white rounded-lg px-3 py-1.5 text-xs shadow-[0_0_10px_rgba(255,0,0,0.5)]">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                                <path
                                                    d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
                                                <path d="m4.243 5.21 14.39 12.472" />
                                            </svg>
                                            Sancionado
                                        </div>
                                        <div class="text-xs text-black dark:text-white text-center">
                                            @if($sancion->periodo_texto)
                                            <strong class="block">Restan: {{ $sancion->periodo_texto }}</strong>
                                            <small class="block mt-1 opacity-80 text-[10px]">
                                                {{ \Carbon\Carbon::parse($sancion->fecha_inicio)->format('d/m/Y') }} -
                                                {{ \Carbon\Carbon::parse($sancion->fecha_fin)->format('d/m/Y') }}
                                            </small>
                                            @else
                                            {{ $sancion->partidos_sancionados }} fechas, cumple: {{
                                            $sancion->partidos_cumplidos }}
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                    @else
                                    <div
                                        class="inline-flex items-center bg-emerald-600 text-white rounded-lg px-3 py-1.5 text-xs shadow-[0_0_10px_rgba(0,255,180,0.6)]">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                            <path
                                                d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
                                            <path d="m9 12 2 2 4-4" />
                                        </svg>
                                        Habilitado
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>



                    </div>
                    @endforeach
                </div>
                @endif

            </div>

        </div>
        @endif

    </div>

</div>