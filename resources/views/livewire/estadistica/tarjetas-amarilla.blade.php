<div>
    {{-- 🔍 Buscador --}}
    <div class="flex items-center space-x-2">
        <input type="text" wire:model.defer="buscarAmarillas" wire:keydown.enter="buscarJugadorAmarilla"
            class="flex-grow bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-500 dark:border-gray-600 dark:placeholder-gray-800 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            style="max-width: 300px;">
        <button wire:click="buscarJugadorAmarilla"
            class="px-4 py-2.5 text-sm font-medium text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-search-icon lucide-search mr-1">
                <path d="m21 21-4.34-4.34" />
                <circle cx="11" cy="11" r="8" />
            </svg>
            Buscar
        </button>
    </div>

    {{-- 🟡 Resultados por búsqueda --}}
    @if ($jugadorBuscadoAmarilla && $jugadorBuscadoAmarilla->isNotEmpty())
    {{-- 📱 Tarjetas para móvil --}}
    <div class="block sm:hidden space-y-3 mt-2">
        @foreach ($jugadorBuscadoAmarilla as $estadistica)
        @php
        $jugador = $estadistica->jugador;
        $equipo = $jugador->equipo->nombre ?? 'Sin equipo';
        $partido = $estadistica->estadisticable;
        $tipo = class_basename($estadistica->estadisticable_type);
        @endphp
        <div class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow p-4">
            <div class="text-sm font-semibold text-gray-700 dark:text-gray-200">
                {{ strtoupper($jugador->apellido) }} {{ strtoupper($jugador->nombre) }}
            </div>
            <div class="text-xs text-gray-600 dark:text-gray-300">
                <span class="font-semibold">DNI:</span> {{ $jugador->documento }}
            </div>
            <div class="text-xs text-gray-600 dark:text-gray-300">
                <span class="font-semibold">Equipo:</span> {{ ucwords($equipo) }}
            </div>
            <div class="text-xs text-gray-600 dark:text-gray-300">
                <span class="font-semibold">Tipo:</span>
                <span
                    class="inline-block px-2 py-0.5 rounded text-white text-[11px] bg-{{ $tipo === 'Encuentro' ? 'blue-600' : 'yellow-500' }}">
                    {{ $tipo }}
                </span>
            </div>
            <div class="text-xs text-gray-600 dark:text-gray-300">
                <span class="font-semibold">Fecha / Fase:</span>
                {{ $tipo === 'Encuentro' ? $partido->fecha_encuentro : $partido->fase }}
            </div>
        </div>
        @endforeach
    </div>

    {{-- 💻 Tabla para escritorio --}}
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-2 hidden sm:block">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-2x1 text-gray-100 uppercase bg-gray-500 dark:bg-purple-900 dark:text-[#FFC107]">
                <tr>
                    <th class="px-6 py-3">DNI</th>
                    <th class="px-6 py-3">Apellido y Nombre</th>
                    <th class="px-6 py-3">Equipo</th>
                    <th class="px-6 py-3">Tipo</th>
                    <th class="px-6 py-3">Fecha / Fase</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jugadorBuscadoAmarilla as $estadistica)
                @php
                $jugador = $estadistica->jugador;
                $equipo = $jugador->equipo->nombre ?? 'Sin equipo';
                $partido = $estadistica->estadisticable;
                $tipo = class_basename($estadistica->estadisticable_type);
                @endphp
                <tr
                    class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                    <td class="px-6 py-4">{{ $jugador->documento }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $jugador->apellido }} {{ $jugador->nombre }}
                    </td>
                    <td class="px-6 py-4">{{ $equipo }}</td>
                    <td class="px-6 py-4">
                        <span
                            class="badge bg-{{ $tipo === 'Encuentro' ? 'blue-600' : 'yellow-500' }} text-white px-2 py-1 rounded">
                            {{ $tipo }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        {{ $tipo === 'Encuentro' ? $partido->fecha_encuentro : $partido->fase }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @elseif(!empty($buscarAmarillas))
    <a href="#"
        class="block max-w-2x1 p-6 m-3 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
        <p class="font-normal text-gray-700 dark:text-[#FFC107]">No se encontraron jugadores</p>
    </a>
    @endif

    {{-- 🟡 Resultados generales si no hay búsqueda activa --}}
    @if ($amarillas && empty($jugadorBuscadoAmarilla))
    <div class="mt-2 space-y-4 sm:space-y-0 sm:overflow-x-auto">
        {{-- La tabla clásica se mantiene para pantallas medianas (sm) y superiores --}}
        <table class="hidden w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 sm:table">
            <thead class="text-2x1 text-gray-100 uppercase bg-gray-500 dark:bg-purple-900 dark:text-[#FFC107]">
                <tr>
                    <th class="px-6 py-3">DNI</th>
                    <th class="px-6 py-3">Apellido y Nombre</th>
                    <th class="px-6 py-3">Equipo</th>
                    <th class="px-6 py-3">Encuentro</th>
                    <th class="px-6 py-3">Fecha / Fase</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($amarillas as $estadistica)
                @php
                $jugador = $estadistica->jugador;
                $equipo = $jugador->equipo->nombre ?? 'Sin equipo';
                $partido = $estadistica->estadisticable;
                $tipo = class_basename($estadistica->estadisticable_type);
                @endphp
                <tr
                    class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                    <td class="px-6 py-4">{{ $jugador->documento }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ strtoupper($jugador->apellido) }} {{ strtoupper($jugador->nombre) }}
                    </td>
                    <td class="px-6 py-4">{{ ucwords($equipo) }}</td>
                    <td class="px-6 py-4">
                        <span
                            class="badge bg-{{ $tipo === 'Encuentro' ? 'blue-600' : 'yellow-500' }} text-white px-2 py-1 rounded">
                            {{ $partido->equipoLocal->nombre ?? '¿?' }}
                            vs
                            {{ $partido->equipoVisitante->nombre ?? '¿?' }}

                        </span>
                    </td>
                    <td class="px-6 py-4">
                        {{ $tipo === 'Encuentro' ? $partido->fecha_encuentro : $partido->fase }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Versión en "Tarjetas" para móvil (menos de sm) --}}
        <div class="sm:hidden space-y-4">
            @foreach ($amarillas as $estadistica)
            @php
            $jugador = $estadistica->jugador;
            $equipo = $jugador->equipo->nombre ?? 'Sin equipo';
            $partido = $estadistica->estadisticable;
            $tipo = class_basename($estadistica->estadisticable_type);
            @endphp
            <div class="p-4 bg-white dark:bg-gray-800 shadow-md rounded-lg border border-gray-200 dark:border-gray-700">

                {{-- Fila 1: Apellido y Nombre (Título) / Tipo (Badge) --}}
                <div class="flex justify-between items-start mb-2">
                    <div class="font-bold text-lg text-gray-900 dark:text-white leading-tight">
                        {{ strtoupper($jugador->apellido) }} {{ strtoupper($jugador->nombre) }}
                        <span class="block text-xs font-medium text-gray-500 dark:text-gray-300">
                            DNI: {{ $jugador->documento }}
                        </span>
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-300">Equipo: {{
                            ucwords($equipo) }}</span>
                    </div>


                </div>

                {{-- Fila 2: DNI / Equipo --}}
                <div class="flex justify-between items-center text-sm text-gray-600 dark:text-gray-400">
                    <div class="text-center align-middle">
                        Fecha:
                        <span class="badge bg-[#c4ab1d] font-semibold text-white px-1 py-1 rounded">
                            {{ $tipo === 'Encuentro' ? $partido->fecha_encuentro : $partido->fase }}
                        </span>
                    </div>

                </div>

                {{-- Fila 3: Fecha / Fase --}}
                <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    <span class="font-medium text-gray-500 dark:text-gray-300">Encuentro:</span>
                    <td class="px-6 py-4">
                        <span
                            class="badge bg-{{ $tipo === 'Encuentro' ? 'blue-600' : 'blue-600' }} text-white px-2 py-1 rounded">
                            {{ $partido->equipoLocal->nombre ?? '¿?' }}
                            vs
                            {{ $partido->equipoVisitante->nombre ?? '¿?' }}

                        </span>
                    </td>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif


</div>