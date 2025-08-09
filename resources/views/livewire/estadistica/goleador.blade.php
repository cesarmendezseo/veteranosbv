<div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right bg-gray-500 text-gray-100 dark:text-gray-400">
            <thead class="text-2x1 text-gray-100 uppercase bg-gray-500 dark:bg-purple-900 dark:text-[#FFC107]">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Apellido y Nombre
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Equipo
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Goles
                    </th>

                </tr>
            </thead>
            <tbody>
                @foreach ($goleadores as $goleador)
                <tr
                    class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                    <th scope="row"
                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ strtoupper($goleador->jugador->apellido) }} {{ strtoupper($goleador->jugador->nombre) }}
                    </th>
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ ucwords($goleador->jugador->equipo->nombre) }}
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $goleador->total_goles }}
                    </td>

                </tr>
                @endforeach

            </tbody>
        </table>
    </div>



</div>