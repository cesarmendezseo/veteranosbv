<div>
    {{-- 🔍 Buscador --}}
    <form wire:submit.prevent="buscar" class="flex items-center space-x-2">
        <input type="text" wire:model.defer="search" placeholder="Buscar..."
            class="flex-grow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-500 dark:border-gray-600 dark:placeholder-gray-800 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            style="max-width: 300px;">
        <button type="submit"
            class="px-4 py-2.5 text-sm font-medium text-white inline-flex items-center bg-yellow-600 hover:bg-yellow-700 focus:ring-4 focus:outline-none focus:ring-yellow-300 rounded-lg text-center dark:bg-yellow-500 dark:hover:bg-yellow-600 dark:focus:ring-yellow-700">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-search-icon lucide-search mr-1">
                <path d="m21 21-4.34-4.34" />
                <circle cx="11" cy="11" r="8" />
            </svg>
            Buscar
        </button>
    </form>

    {{-- 🟡 Tabla de acumulados --}}
    @if ($tarjetasAcumuladasPorJugador && $tarjetasAcumuladasPorJugador->isNotEmpty())
    {{-- 📱 Vista móvil (tarjetas) --}}
    <div class="space-y-4 md:hidden mt-2">
        @foreach ($tarjetasAcumuladasPorJugador as $estadistica)
        @php
        $jugador = $estadistica->jugador;
        $equipo = $jugador->equipo->nombre ?? 'Sin equipo';
        $amarillas = $estadistica->total_tarjetas_amarillas_acumuladas;
        $suspendido = $amarillas >= 5 ? true : false;
        @endphp

        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow p-4">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-gray-900 dark:text-gray-100 font-semibold">
                    {{ strtoupper($jugador->apellido) }} {{ strtoupper($jugador->nombre) }}
                </h3>

            </div>

            <p class="text-sm text-gray-700 dark:text-gray-300">
                <strong>DNI:</strong> {{ $jugador->documento }}
            </p>
            <p class="text-sm text-gray-700 dark:text-gray-300">
                <strong>Equipo:</strong> {{ ucwords($equipo) }}
            </p>

            <div class="mt-2">
                <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                    🟡 Total Amarillas:
                    <span class="ml-2 px-2 py-1 rounded text-white 
                        {{ $amarillas >= 5 ? 'bg-red-500' : 'bg-yellow-500' }}">
                        {{ $amarillas }}
                    </span>
                </p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- 💻 Vista escritorio (tabla) --}}
    <div class="hidden md:block relative overflow-x-auto shadow-md sm:rounded-lg mt-2">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-2x1 text-gray-700 uppercase bg-gray-50 dark:bg-purple-900 dark:text-[#FFC107]">
                <tr>
                    <th class="px-6 py-3">DNI</th>
                    <th class="px-6 py-3">Apellido y Nombre</th>
                    <th class="px-6 py-3">Equipo</th>
                    <th class="px-6 py-3 text-center">Total Amarillas</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($tarjetasAcumuladasPorJugador as $estadistica)
                @php
                $jugador = $estadistica->jugador;
                $equipo = $jugador->equipo->nombre ?? 'Sin equipo';
                $amarillas = $estadistica->total_tarjetas_amarillas_acumuladas;
                $suspendido = $amarillas >= 5 ? true : false;
                @endphp
                <tr
                    class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                    <td class="px-6 py-4">{{ $jugador->documento }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ strtoupper($jugador->apellido) }} {{ strtoupper($jugador->nombre) }}
                    </td>
                    <td class="px-6 py-4">{{ ucwords($equipo) }}</td>
                    <td class="px-6 py-4 text-center font-bold">{{ $amarillas }}</td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- 📄 Paginación --}}
    <div class="pagination mt-2">
        {{ $tarjetasAcumuladasPorJugador->links() }}
    </div>
</div>
@elseif(!empty($tarjetasAcumuladasPorJugador))
<a href="#"
    class="block max-w-2x1 p-6 m-3 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
    <p class="font-normal text-gray-700 dark:text-[#FFC107]">No se encontraron jugadores</p>
</a>
@endif
</div>