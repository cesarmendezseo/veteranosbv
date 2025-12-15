<div class="w-full mt-6">

    <!-- Contenedor vidrioso -->
    <div
        class="p-4 sm:p-6 rounded-3xl bg-white/10 backdrop-blur-xl border border-white/20 shadow-[0_0_25px_rgba(0,0,0,0.4)]">
        <div class="p-4 sm:p-6 rounded-3xl
            text-center">
            <h1 class="
        text-4xl md:text-6xl font-extrabold text-center
        text-black dark:text-white 
        px-6 py-3 rounded-2xl inline-block

       
        
    ">
                {{ ucwords($campeonatoNombre) }}
            </h1>
        </div>
        @foreach ($posiciones as $grupoNombre => $tablaGrupo)
        <!-- Título del grupo -->
        <h2 class="text-center text-xl font-extrabold tracking-wider my-6 
                       text-black  bg-white/10 backdrop-blur-md
                       px-4 py-2 rounded-2xl border border-white/20
                       shadow-[0_0_10px_rgba(0,200,255,0.5)] dark:text-white">
            {{ strtoupper($grupoNombre) }}
        </h2>

        <!-- Tabla vidriosa -->
        <div class="overflow-x-auto rounded-2xl shadow-[0_0_20px_rgba(0,0,0,0.3)] mb-10">

            <table class="w-full text-sm text-center text-white/90 bg-white/5 backdrop-blur-xl">

                <!-- CABECERA -->
                <thead class="bg-gradient-to-r from-blue-700/60 to-blue-500/50
                               text-black uppercase text-xs border-b border-white/20 dark:text-white">
                    <tr>
                        <th class="px-2 py-3">#</th>
                        <th class="px-4 py-3 text-left">Equipo</th>
                        <th class="px-2 py-3">Pts</th>
                        <th class="px-2 py-3">PJ</th>
                        <th class="px-2 py-3">PG</th>
                        <th class="px-2 py-3">PE</th>
                        <th class="px-2 py-3">PP</th>
                        <th class="px-2 py-3">GF</th>
                        <th class="px-2 py-3">GC</th>
                        <th class="px-2 py-3">DG</th>
                        <th class="px-2 py-3">Fair</th>
                    </tr>
                </thead>

                <!-- BODY -->
                <tbody class="backdrop-blur-xl">
                    @foreach ($tablaGrupo as $index => $equipo)
                    <tr class=" text-black border-b border-white/10 hover:bg-white/10 transition dark:text-white">

                        <!-- POSICIÓN -->
                        <td class="py-3">
                            <span class="w-4 h-4 text-xs flex items-center justify-center mx-auto rounded-full
                                           bg-blue-600 text-white font-bold shadow-[0_0_10px_rgba(0,150,255,0.7)]">
                                {{ (int)$index + 1 }}
                            </span>
                        </td>

                        <!-- EQUIPO -->
                        <td class="px-4 py-3 text-left font-semibold text-black tracking-wide dark:text-white">
                            {{ strtoupper($equipo['equipo']) }}
                        </td>

                        <!-- PUNTOS -->
                        <td class="py-3">
                            <span class="w-8 h-8 flex items-center justify-center mx-auto rounded-full
                                           bg-emerald-600 text-white font-bold shadow-[0_0_10px_rgba(0,255,180,0.6)]">
                                {{ $equipo['puntos'] }}
                            </span>
                        </td>

                        <!-- ESTADÍSTICAS -->
                        <td class="py-3">{{ $equipo['jugados'] }}</td>
                        <td class="py-3">{{ $equipo['ganados'] }}</td>
                        <td class="py-3">{{ $equipo['empatados'] }}</td>
                        <td class="py-3">{{ $equipo['perdidos'] }}</td>
                        <td class="py-3">{{ $equipo['goles_favor'] }}</td>
                        <td class="py-3">{{ $equipo['goles_contra'] }}</td>
                        <td class="py-3 font-bold">{{ $equipo['diferencia_goles'] }}</td>
                        <td class="py-3">{{ $equipo['fair_play'] }}</td>

                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        @endforeach

    </div>

</div>