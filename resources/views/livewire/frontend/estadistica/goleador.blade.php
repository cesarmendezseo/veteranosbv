<div class="space-y-4">

    {{-- 📌 Versión Escritorio --}}
    <div class="hidden sm:block bg-white dark:bg-gray-900 shadow-lg rounded-xl overflow-hidden">
        <table class="w-full text-sm sm:text-base text-gray-700 dark:text-gray-100">
            <thead class="bg-gray-800 text-yellow-400 text-xs sm:text-sm uppercase">
                <tr>
                    <th class="px-4 py-3">Jugador</th>
                    <th class="px-4 py-3">Equipo</th>
                    <th class="px-4 py-3 text-center">Goles</th>
                </tr>
            </thead>
            <tbody>
                @forelse($goleadores as $gol)
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <td class="px-4 py-3">{{ strtoupper($gol->jugador->apellido) }}, {{
                        strtoupper($gol->jugador->nombre) }}</td>
                    <td class="px-4 py-3">{{ strtoupper($gol->jugador->equipo->nombre ?? 'Sin equipo') }}</td>
                    <td class="px-4 py-3 text-center font-extrabold text-blue-600">
                        {{ $gol->total_goles }}
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

    {{-- 📱 Versión Móvil --}}
    <div class="sm:hidden space-y-4">
        @forelse($goleadores as $gol)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-4 border border-gray-200 dark:border-gray-700">

            <!-- Nombre -->
            <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-1">
                {{ strtoupper($gol->jugador->apellido) }}, {{ strtoupper($gol->jugador->nombre) }}
            </h2>

            <hr class="my-2 border-gray-300 dark:border-gray-600">

            <!-- Datos -->
            <div class="space-y-1 text-sm">

                <div class="flex justify-between">
                    <span class="font-semibold text-gray-600 dark:text-gray-300">Equipo:</span>
                    <span class="text-gray-900 dark:text-gray-100">
                        {{ strtoupper($gol->jugador->equipo->nombre ?? 'SIN EQUIPO') }}
                    </span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="font-semibold text-gray-600 dark:text-gray-300">Goles:</span>
                    <span class="text-white bg-blue-600 px-3 py-1 rounded-full font-bold">
                        {{ $gol->total_goles }}
                    </span>
                </div>

            </div>

        </div>
        @empty
        <div class="text-center text-gray-500 dark:text-gray-300">
            No hay goles registrados en este campeonato.
        </div>
        @endforelse
    </div>

    {{-- Paginación --}}
    <div class="mt-4">
        {{ $goleadores->links() }}
    </div>
</div>