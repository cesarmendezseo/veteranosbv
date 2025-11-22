<div>

    <div class=" mt-2 w-full ">

        {{-- <div class="bg-blue-900 text-white p-2 shadow-md rounded flex justify-between items-center relative z-10">
            <h1 class="text-lg font-bold">Tabla de posiciones</h1>


            <!--Nav para móvil (se muestra hasta md)  -->
            <nav class="flex md:hidden space-x-4">
                <a href="{{ route('tabla-posicion-index') }}" wire:navigate
                    class="text-white px-4 py-2 rounded flex items-center gap-2 hover:underline">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </a>
            </nav>

            <!--Nav para escritorio (md en adelante)  -->
            <nav class="hidden md:flex space-x-4">
                <a href="{{ route('tabla-posicion-index') }}" wire:navigate
                    class="text-white px-4 py-2 rounded flex items-center gap-2 hover:underline">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Volver
                </a>
            </nav>
        </div> --}}


        <!-- Tabla de posiciones -->
        <div class="p-2 sm:p-4 shadow-md bg-white dark:bg-gray-800 rounded-lg space-y-4 min-h-screen">
            @foreach ($posiciones as $grupoNombre => $tablaGrupo)
            <h2 class="text-lg font-bold mt-4 mb-2 text-center bg-gray-300 dark:bg-gray-600 py-1 rounded">
                {{ ucwords($grupoNombre) }}
            </h2>
            <div class="overflow-x-auto mb-6 shadow-md">
                <table class="w-full text-xs sm:text-sm text-center border border-gray-300">
                    <thead class="bg-gray-700 text-gray-100">
                        <tr>
                            <th class="px-2 py-2">#</th>
                            <th class="px-2 py-2 text-left">EQUIPO</th>
                            <th class="px-2 py-2 font-bold">Pts</th>
                            <th class="px-2 py-2">PJ</th>
                            <th class="px-2 py-2">PG</th>
                            <th class="px-2 py-2">PE</th>
                            <th class="px-2 py-2">PP</th>
                            <th class="px-2 py-2">GF</th>
                            <th class="px-2 py-2">GC</th>
                            <th class="px-2 py-2">DG</th>
                            <th class="px-2 py-2">Fair Play</th>

                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($tablaGrupo as $index => $equipo)
                        <tr
                            class="relative after:content-[''] after:block after:absolute after:bottom-0 after:left-0 after:right-0 after:h-[1px] after:bg-gray-300/50">

                            <!-- POSICIÓN -->
                            <td class=" py-2 ">
                                <div class="w-5 h-5   rounded-full flex items-center justify-center mx-auto">
                                    {{ (int)$index + 1 }}
                                </div>
                            </td>

                            <!-- EQUIPO -->
                            <td class="px-2 py-3 text-left font-semibold border-l border-gray-300 whitespace-nowrap">
                                {{ strtoupper($equipo['equipo']) }}
                            </td>
                            <!-- PUNTOS -->
                            <td
                                class="relative after:content-[''] after:absolute after:top-1/2 after:-translate-y-1/2 after:right-0 after:w-px after:h-4 after:bg-gray-900">
                                <div
                                    class="w-6 h-6 bg-blue-700 text-white rounded-full flex items-center justify-center font-bold mx-auto">
                                    {{ $equipo['puntos'] }}
                                </div>
                            </td>
                            <!-- ESTADÍSTICAS -->
                            <td
                                class="relative after:content-[''] after:absolute after:top-1/2 after:-translate-y-1/2 after:right-0 after:w-px after:h-4 after:bg-gray-900 ">
                                {{ $equipo['jugados'] }}</td>
                            <td
                                class="relative after:content-[''] after:absolute after:top-1/2 after:-translate-y-1/2 after:right-0 after:w-px after:h-4 after:bg-gray-900 ">
                                {{ $equipo['ganados'] }}</td>
                            <td
                                class="relative after:content-[''] after:absolute after:top-1/2 after:-translate-y-1/2 after:right-0 after:w-px after:h-4 after:bg-gray-900 ">
                                {{ $equipo['empatados'] }}</td>
                            <td
                                class="relative after:content-[''] after:absolute after:top-1/2 after:-translate-y-1/2 after:right-0 after:w-px after:h-4 after:bg-gray-900 ">
                                {{ $equipo['perdidos'] }}</td>
                            <td
                                class="relative after:content-[''] after:absolute after:top-1/2 after:-translate-y-1/2 after:right-0 after:w-px after:h-4 after:bg-gray-900 ">
                                {{ $equipo['goles_favor'] }}</td>
                            <td
                                class="relative after:content-[''] after:absolute after:top-1/2 after:-translate-y-1/2 after:right-0 after:w-px after:h-4 after:bg-gray-900 ">
                                {{ $equipo['goles_contra'] }}</td>
                            <td
                                class="relative after:content-[''] after:absolute after:top-1/2 after:-translate-y-1/2 after:right-0 after:w-px after:h-4 after:bg-gray-900 ">
                                {{ $equipo['diferencia_goles'] }}
                            </td>
                            <td class="">
                                {{ $equipo['fair_play'] }}</td>



                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endforeach
        </div>
    </div>

</div>