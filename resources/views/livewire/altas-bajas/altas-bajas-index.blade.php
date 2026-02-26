<div class="p-4 bg-gray-100 rounded-lg mb-4 shadow-md">
    <div class="bg-blue-900 text-white p-4 shadow-md rounded flex justify-between items-center relative z-10"">
        <h2 class=" font-semibold text-xl text-gray-100 leading-tight">
        {{ __('Historial de Jugadores') }}
        </h2>
        <div>
            <a href="{{ route('altas-bajas.historial') }}" class="cursor-pointer hover:underline">Historial por
                Fechas</a>
        </div>
    </div>
    <input wire:model.lazy="dni" wire:keydown.enter="buscar" type="text" placeholder="Buscar por DNI"
        class="mt-2 flex-grow bg-gray-50 mb-2 border border-gray-500 text-gray-900  text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-500 dark:border-gray-600 dark:placeholder-gray-300 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />



    <!-- movil -->
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($jugadores as $jugador)
        <div
            class="rounded-xl shadow-md bg-gray-100 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 p-4 flex justify-between items-start">

            <!-- Datos del jugador -->
            <div>
                <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                    DNI: {{ $jugador->documento }}
                </p>
                <p class="text-base font-bold text-gray-900 dark:text-white">
                    {{ strtoupper($jugador->nombre) }} {{ strtoupper($jugador->apellido) }}
                </p>

            </div>

            <!-- Menú de acciones -->



            <div class="flex items-center justify-end gap-2">
                {{-- VER --}}
                <button wire:click="verHistorial({{ $jugador->id }})" title="Ver"
                    class="cursor-pointer text-blue-600 hover:text-white border border-blue-600 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-xs px-2 py-1 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>

                </button>
            </div>

        </div>
        @empty
        <p class="text-center p-4 text-sm dark:text-gray-300">No hay jugadores</p>
        @endforelse
    </div>
    {{ $jugadores->links() }}

    {{-- Sección de alta --}}
    @if ($mostrarAlta)
    <div class="mt-4 p-4 bg-gray-100 border rounded dark:bg-gray-400">
        <h3 class="text-base font-bold mb-2 dark:text-gray-900">Seleccionar equipo para alta</h3>
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
            <select wire:model="equipoSeleccionado"
                class="bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-60 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                >
                <option value="">-- Selecciona un equipo --</option>
                @foreach ($equipos->sortBy('nombre') as $equipo)
                <option value="{{ $equipo->id }}">
                    {{ strtoupper($equipo->nombre) }}
                </option>
                @endforeach
            </select>

            <button wire:click="darDeAlta"
                class="cursor-pointer inline-flex items-center gap-2 mt-4  bg-blue-950 hover:bg-blue-800 text-white px-4 py-2 rounded ">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-save-icon lucide-save">
                    <path
                        d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
                    <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7" />
                    <path d="M7 3v4a1 1 0 0 0 1 1h7" />
                </svg> <span>Guardar</span></button>
            <a href="{{ route('altas-bajas.index') }}"
                class=" cursor-pointer inline-flex items-center gap-2 mt-4  bg-blue-950 hover:bg-blue-800 text-white px-4 py-2 rounded ">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span>Volver</span>
            </a>
        </div>
    </div>
    @endif

    {{-- Historial --}}
    @if (count($historial))
    <div class="mt-4 overflow-x-auto bg-gray-100">
        <h3
            class="bg-blue-900 text-white p-2 shadow-md rounded flex justify-between items-center relative font-semibold">
            Historial de equipos</h3>
        <table class=" w-full border table-auto text-sm shadow-md">
            <thead class=" bg-gray-700 dark:bg-gray-500 text-gray-100 dark:text-black shadow-md">
                <tr class="">
                    <th class="p-2">Equipo</th>
                    <th class="p-2">Campeonato</th>
                    <th class="p-2">Fecha de Baja</th>
                    <th class="p-2">Fecha de Alta</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($historial as $h)
                <tr
                    class="border-t  odd:bg-gray-200 odd:text-gray-800 font-semibold even:text-gray-800 even:bg-gray-300 dark:odd:bg-gray-900 dark:even:bg-gray-800">
                    <td class="p-2  semi-bold dark:text-white">{{ strtoupper($h->equipo) }}</td>
                    <td class="p-2  semi-bold dark:text-white">{{ strtoupper($h->campeonato) }}</td>

                    <td class="p-2 dark:text-gray-100 text-center">
                        @if ($h->fecha_baja)
                        {{ \Carbon\Carbon::parse($h->fecha_baja)->format('d/m/Y') }}
                        @else
                        <div class="flex justify-center">
                            <svg class="w-8 h-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        @endif
                    </td>
                    <td class="p-2 dark:text-gray-100 text-center">
                        {{ \Carbon\Carbon::parse($h->fecha_alta)->format('d/m/Y') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif



</div>