<div class="space-y-4">

    {{-- 📌 Versión Escritorio (moderna) --}}
    <div
        class="hidden sm:block bg-white dark:bg-gray-900 shadow-xl rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700">
        <table class="w-full text-sm sm:text-base text-gray-700 dark:text-gray-100">
            <thead
                class="bg-gradient-to-r from-blue-700 to-blue-900 text-white text-xs sm:text-sm uppercase font-semibold">
                <tr>
                    <th class="px-4 py-3 text-left">Jugador</th>
                    <th class="px-4 py-3 text-left">Equipo</th>
                    <th class="px-4 py-3 text-center">Goles</th>
                </tr>
            </thead>
            <tbody>
                @forelse($goleadores as $gol)
                <tr
                    class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    <td class="px-4 py-3 font-semibold">
                        {{ strtoupper($gol->jugador->apellido) }}, {{ strtoupper($gol->jugador->nombre) }}
                    </td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                        {{ strtoupper($gol->jugador->equipo->nombre ?? 'SIN EQUIPO') }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-white bg-blue-600 px-3 py-1 rounded-full font-bold shadow">
                            {{ $gol->total_goles }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-4 py-6 text-center text-gray-500 dark:text-gray-300">
                        No hay goles registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>



    {{-- 📱 Versión Móvil (super moderna tipo app) --}}
    <div class="sm:hidden space-y-4">
        @forelse($goleadores as $gol)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 border border-gray-200 dark:border-gray-700
                    hover:scale-[1.02] transition-transform duration-200">

            <!-- Top -->
            <div class="flex justify-between items-center mb-2">

                <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 leading-tight">
                    {{ strtoupper($gol->jugador->apellido) }},
                    <span class="font-normal">{{ strtoupper($gol->jugador->nombre) }}</span>
                </h2>

                <span class="bg-blue-600 text-white px-4 py-1 rounded-full font-bold shadow text-base">
                    {{ $gol->total_goles }}
                </span>

            </div>

            <hr class="my-3 border-gray-300 dark:border-gray-600">

            <!-- Equipo -->
            <div class="flex justify-between text-sm">
                <span class="font-semibold text-gray-600 dark:text-gray-300">Equipo:</span>
                <span class="font-bold text-gray-900 dark:text-gray-100">
                    {{ strtoupper($gol->jugador->equipo->nombre ?? 'SIN EQUIPO') }}
                </span>
            </div>

        </div>
        @empty
        <div class="text-center text-gray-500 dark:text-gray-300">
            No hay goles registrados en este campeonato.
        </div>
        @endforelse
    </div>


    {{-- 📄 Paginación --}}
    <div class="mt-4">
        {{ $goleadores->links() }}
    </div>

</div>