<div>
    
         <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Fixture') }}
        </h2>
        <div class="flex items-center space-x-4">
        </div>
    
        <!--movil-->
        <div class="md:hidden flex ">
            <a href="{{ route('fixture.index') }}" class="px-3 py-2  text-white rounded flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </a>

            @adminOrCan()
                <a href="{{ route('fixture.automatico') }}" class="px-3 py-2  text-white rounded flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-badge-plus-icon lucide-badge-plus">
                        <path
                            d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z" />
                        <line x1="12" x2="12" y1="8" y2="16" />
                        <line x1="8" x2="16" y1="12" y2="12" />
                    </svg><span class="text-xs">Automatico</span>
                </a>

                <button wire:click="exportar"
                    class="cursor-pointer px-3 py-2  text-white rounded disabled:opacity-50 flex items-center gap-1"
                    @disabled(!$jornadaFiltro)>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-download-icon lucide-download">
                        <path d="M12 15V3" />
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <path d="m7 10 5 5 5-5" />
                    </svg>
                </button>
            @endadminOrCan
        </div>
        <!-- escritorio-->
        <div class="hidden md:flex  gap-4">
            <a href="{{ route('fixture.index') }}" class="px-3 py-2  text-white rounded flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg> Volver
            </a>

            @adminOrCan()
                <a href="{{ route('fixture.automatico') }}" class="px-3 py-2  text-white rounded flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-badge-plus-icon lucide-badge-plus">
                        <path
                            d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z" />
                        <line x1="12" x2="12" y1="8" y2="16" />
                        <line x1="8" x2="16" y1="12" y2="12" />
                    </svg> Crear Auto.
                </a>

                <button wire:click="exportar"
                    class="cursor-pointer px-3 py-2  text-white rounded disabled:opacity-50 flex items-center gap-1"
                    @disabled(!$jornadaFiltro)>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-download-icon lucide-download">
                        <path d="M12 15V3" />
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <path d="m7 10 5 5 5-5" />
                    </svg>Exportar
                </button>
            @endadminOrCan
        </div>
    </x-slot>
    <div class="mb-4 grid grid-cols-1 md:grid-cols-1 gap-4 bg-gray-200 p-3 rounded-lg shadow-md">

        <!-- Filtros -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-4 bg-gray-200 p-3 rounded-lg shadow-md">

            <!-- Fecha -->
            <input wire:model.live="fechaFiltro" type="date"
                class="hidden md:block bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Fecha encuentro" />

            <!-- Fecha Encuentro -->
            <select wire:model.live="jornadaFiltro" id="fecha_jornada"
                class="cursor-pointer bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                >
                <option value="">Fecha</option>
                @foreach ($jornadas as $jornada)
                    <option value="{{ $jornada }}">{{ $jornada }}</option>
                @endforeach
            </select>
            <!-- Estado -->
            <select wire:model.live="estadoFiltro"
                class="cursor-pointer hidden md:block bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

                <option value="">-- Estado --</option>
                <option value="programado">Programado</option>
                <option value="pendiente">Pendiente</option>
                <option value="jugado">Jugado</option>
            </select>

            <!-- Grupo -->
            <select wire:model.live="grupoFiltro"
                class="cursor-pointer  bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

                <option value="">-- Grupo --</option>
                @foreach ($grupos ?? [] as $grupo)
                    <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option>
                @endforeach
            </select>

            <!-- Equipo Local -->
            <input wire:model.live="equipoLocalFiltro" type="text" placeholder="Equipo Local"
                class="hidden md:block bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />

            <!-- Equipo Visitante -->
            <input wire:model.live="equipoVisitanteFiltro" type="text" placeholder="Equipo Visitante"
                class="hidden md:block bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
        </div>

    </div>

    <div class="overflow-x-auto border border-gray-300 dark:bg-gray-900 p-4 shadow-md sm:rounded-lg">
        @foreach ($encuentrosAgrupados as $nombreCancha => $encuentros)
            <div class="mb-6">
                <!-- Nombre de la cancha -->

                @php
                    $grupos = $encuentros->pluck('grupo.nombre')->unique()->filter();
                @endphp
                <div
                    class="mb-2 text-center p-4 rounded-b-sm text-lg font-semibold text-gray-900 dark:text-gray-900 bg-accent flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 40" width="32" height="20"
                        class="inline-block">
                        <!-- Fondo -->
                        <rect x="0" y="0" width="64" height="40" fill="#4CAF50" stroke="#2E7D32"
                            stroke-width="1" />
                        <!-- Línea central -->
                        <line x1="32" y1="0" x2="32" y2="40" stroke="white"
                            stroke-width="1" />
                        <!-- Círculo central -->
                        <circle cx="32" cy="20" r="5" stroke="white" stroke-width="1"
                            fill="none" />
                        <circle cx="32" cy="20" r="1" fill="white" />
                        <!-- Áreas grandes -->
                        <rect x="0" y="10" width="6" height="20" stroke="white" stroke-width="1"
                            fill="none" />
                        <rect x="58" y="10" width="6" height="20" stroke="white" stroke-width="1"
                            fill="none" />
                        <!-- Arcos -->
                        <path d="M0,14 A4,4 0 0,1 0,26" stroke="white" stroke-width="1" fill="none" />
                        <path d="M64,14 A4,4 0 0,0 64,26" stroke="white" stroke-width="1" fill="none" />
                    </svg>

                    <p class="m-0">
                        "{{ strtoupper($nombreCancha) }}"
                        @if ($grupos && $grupos->isNotEmpty())
                            <span class="bg-[#eee16b] rounded-3xl px-2 py-1">
                                {{ strtoupper($grupos->implode(', ')) }}
                            </span>
                        @endif
                    </p>
                </div>

                <!-- Tabla de encuentros - Versión Desktop -->
                <div class="hidden sm:block ">
                    <table
                        class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 shadow-md sm:rounded-lg">
                        <thead class="text-xs text-gray-100 uppercase bg-gray-500 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-2 sm:px-4 py-2 text-center">Fecha</th>

                                <th class="px-2 sm:px-4 py-2 text-center">Fecha Jornada</th>

                                <th class="px-2 sm:px-4 py-2 text-center">Local</th>
                                <th class="px-2 sm:px-4 py-2 text-center">Gol</th>
                                <th class="px-2 sm:px-4 py-2 text-center">Gol</th>
                                <th class="px-2 sm:px-4 py-2 text-center">Visitante</th>
                                <th class="px-2 sm:px-4 py-2 text-center">Estado</th>
                                <th colspan="3" class="px-2 sm:px-4 py-2 text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($encuentros as $encuentro)
                                <tr
                                    class=" border-b border-gray-400 dark:border-gray-200  hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <td class="px-2 sm:px-4 py-2 text-center">
                                        {{ \Carbon\Carbon::parse($encuentro->fecha)->format('d/m/Y') }}
                                    </td>
                                    {{-- <td class="px-2 sm:px-4 py-2 text-center">
                                {{ \Carbon\Carbon::parse($encuentro->hora)->format('H:i') }}
                            </td> --}}
                                    <td
                                        class="px-2 sm:px-4 py-2 text-center text-sm text-gray-800 font-semibold  dark:text-gray-200">
                                        {{ $encuentro->fecha_encuentro }}
                                    </td>
                                    <td
                                        class="px-2 sm:px-4 py-2 text-sm text-gray-800 font-semibold  dark:text-gray-200">
                                        {{ strtoupper($encuentro->equipoLocal->nombre) }}
                                    </td>
                                    <td class="w-16 px-1 sm:px-2 py-2 text-center  dark:border-gray-200">
                                        <input type="number" wire:model="goles_local.{{ $encuentro->id }}"
                                            class="w-full px-1 sm:px-2 py-1 border border-gray-400 rounded text-center text-gray-950 bg-gray-200"
                                            min="0" />
                                    </td>
                                    <td class="w-16 px-1 sm:px-2 py-2 text-center ">
                                        <input type="number" wire:model="goles_visitante.{{ $encuentro->id }}"
                                            class="w-full px-1 sm:px-2 py-1 border  border-gray-400 rounded text-center text-gray-950 bg-gray-200"
                                            min="0" />
                                    </td>
                                    <td
                                        class="px-2 sm:px-4 py-2 text-sm font-semibold text-gray-800 dark:text-gray-200">
                                        {{ strtoupper($encuentro->equipoVisitante->nombre) }}
                                    </td>

                                    <td class="px-2 sm:px-4 py-2 text-center ">
                                        {{ strtoupper($encuentro->estado) }}
                                    </td>
                                    @adminOrCan('comision|cargar gol')
                                        <td class="px-1 sm:px-3 py-2">
                                            <div class="flex items-center justify-center space-x-1">
                                                <button wire:click="guardarGoles({{ $encuentro->id }})" title="Guardar"
                                                    class="cursor-pointer text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-3 sm:px-5 py-2 text-center me-1 sm:me-2 mb-1 sm:mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-save">
                                                        <path
                                                            d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
                                                        <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7" />
                                                        <path d="M7 3v4a1 1 0 0 0 1 1h7" />
                                                    </svg>
                                                </button>
                                            @endadminOrCan
                                            @adminOrCan('comision')
                                                <button wire:click="editEncuentro({{ $encuentro->id }})"
                                                    class="cursor-pointer text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-3 sm:px-5 py-2 text-center me-1 sm:me-2 mb-1 sm:mb-2 dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-800"
                                                    title="Editar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-square-pen">
                                                        <path
                                                            d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                        <path
                                                            d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z" />
                                                    </svg>
                                                </button>

                                                <button
                                                    wire:click="$dispatch('confirmar-baja', { id: {{ $encuentro->id }} })"
                                                    class="cursor-pointer text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-3 sm:px-5 py-2 text-center me-1 sm:me-2 mb-1 sm:mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900"
                                                    title="Eliminar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-trash-2">
                                                        <path d="M3 6h18" />
                                                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                        <line x1="10" x2="10" y1="11"
                                                            y2="17" />
                                                        <line x1="14" x2="14" y1="11"
                                                            y2="17" />
                                                    </svg>
                                                </button>

                                            </div>
                                        </td>
                                    @endadminOrCan
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!--///////////------ Versión Móvil ////////////////////// -->
                <div class="sm:hidden space-y-2 ">
                    @foreach ($encuentros as $encuentro)
                        <div
                            class="border border-gray-300 bg-gray-50 shadow-2xs rounded-lg overflow-hidden  text-gray-900 dark:bg-gray-800">
                            <!-- Encabezado con fecha y hora -->
                            <div class="bg-gray-800 dark:bg-gray-500 p-2 flex justify-between items-center">
                                <div class="font-medium text-white dark:text-gray-200">
                                    {{ \Carbon\Carbon::parse($encuentro->fecha)->format('d/m/Y') }}
                                </div>
                                <div class="font-medium text-white dark:text-gray-200">
                                    Fecha:
                                    {{ $encuentro->fecha_encuentro }}
                                </div>
                                <div class="font-medium text-white dark:text-gray-200">

                                    {{ \Carbon\Carbon::parse($encuentro->hora)->format('H:i') }}hs.
                                </div>
                                <div
                                    class="px-2 py-1 bg-gray-500 dark:bg-gray-600 rounded text-sm text-white dark:text-gray-200">

                                    {{ strtoupper($encuentro->estado) }}
                                </div>
                            </div>

                            <!-- Contenido del encuentro -->
                            <div class="p-3">
                                <!-- Equipo local -->
                                <div class="flex items-center justify-between mb-2">
                                    <span
                                        class="font-semibold text-gray-800 dark:text-gray-100">{{ strtoupper($encuentro->equipoLocal->nombre) }}</span>
                                    <input type="number" wire:model="goles_local.{{ $encuentro->id }}"
                                        class="w-16 px-2 py-1 border border-gray-300 rounded-lg text-center text-gray-950 bg-gray-100"
                                        min="0" />
                                </div>

                                <!-- Equipo visitante -->
                                <div class="flex items-center justify-between mb-3">
                                    <span
                                        class="font-semibold text-gray-800 dark:text-gray-100">{{ strtoupper($encuentro->equipoVisitante->nombre) }}</span>
                                    <input type="number" wire:model="goles_visitante.{{ $encuentro->id }}"
                                        class="w-16 px-2 py-1 border border-gray-300 rounded-lg text-center text-gray-950 bg-gray-100"
                                        min="0" />
                                </div>

                                <!-- Botones de acción -->
                                @adminOrCan('comision|cargar gol')
                                    <div class="flex justify-between space-x-2">
                                        <button wire:click="guardarGoles({{ $encuentro->id }})" title="Guardar"
                                            class="flex-1 flex items-center justify-center text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="mr-1">
                                                <path
                                                    d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
                                                <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7" />
                                                <path d="M7 3v4a1 1 0 0 0 1 1h7" />
                                            </svg>
                                            <span class="hidden xs:inline">Guardar</span>
                                        </button>
                                    @endadminOrCan
                                    @adminOrCan('comision')
                                        <button wire:click="editEncuentro({{ $encuentro->id }})"
                                            class="flex-1 flex items-center justify-center text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-2 py-2 dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-800"
                                            title="Editar">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="mr-1">
                                                <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                <path
                                                    d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z" />
                                            </svg>
                                            <span class="hidden xs:inline">Editar</span>
                                        </button>

                                        <button wire:click="$dispatch('confirmar-baja', { id: {{ $encuentro->id }} })"
                                            class="flex-1 flex items-center justify-center text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-2 py-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900"
                                            title="Eliminar">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="mr-1">
                                                <path d="M3 6h18" />
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                <line x1="10" x2="10" y1="11" y2="17" />
                                                <line x1="14" x2="14" y1="11" y2="17" />
                                            </svg>
                                            <span class="hidden xs:inline">Eliminar</span>
                                        </button>
                                    </div>
                                @endadminOrCan
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <div x-data="{ open: @entangle('showEditModal') }">
        <div x-show="open" x-on:static-modal.window="show = true" x-cloak
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow-xl w-full max-w-lg">
                <h2 class="text-xl font-semibold mb-4 text-center dark:text-gray-200">Editar Encuentro</h2>

                <div class="mb-4">
                    <label class="block text-sm dark:text-gray-200">Fecha:</label>
                    <input type="date" wire:model.defer="editFecha"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm dark:text-gray-200">Hora:</label>
                    <input type="time" wire:model.defer="editHora"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>
                <!-- ============================ BOTONES -->
                <div class="mb-4">
                    <label class="block text-sm dark:text-gray-200">Estado:</label>
                    <select wire:model.defer="editEstado"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="programado">Programado</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="Jugado">Jugado</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm dark:text-gray-200">Cancha:</label>
                    <select wire:model.defer="editCanchaId"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Seleccione una cancha</option>
                        @foreach ($canchas as $cancha)
                            <option value="{{ $cancha->id }}">{{ $cancha->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-4">
                    <button @click="open = false"
                        class="px-4 py-2 bg-red-500 text-white rounded-md flex items-center gap-2"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-circle-x-icon lucide-circle-x">
                            <circle cx="12" cy="12" r="10" />
                            <path d="m15 9-6 6" />
                            <path d="m9 9 6 6" />
                        </svg>Cancelar</button>
                    <button wire:click="guardarEdicion"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-save">
                            <path
                                d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
                            <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7" />
                            <path d="M7 3v4a1 1 0 0 0 1 1h7" />
                        </svg>
                        Guardar
                    </button>

                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            document.addEventListener('livewire:initialized', () => {



                Livewire.on('confirmar-baja', ({
                    id
                }) => {
                    Swal.fire({
                        title: 'CUIDADO...',
                        text: "¿Estás seguro de Eliminar el encuentro?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, Eliminar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Llamás al método del componente Livewire y le pasás el parámetro
                            Livewire.dispatch('eliminar-encuentro', {
                                encuentroId: id
                            });
                        }
                    });
                });
                //========================================
                Livewire.on('Baja', () => {
                    Swal.fire(
                        'Error!!',
                        'El encuentro no se puede eliminar porque se encuentra jugado.',
                        'warning'
                    );
                });
                //========================================
                Livewire.on('eliminado', () => {
                    Swal.fire(
                        '¡Eliminado!',
                        'El encuentro ha sido eliminado correctamente.',
                        'success'
                    ).then(() => {
                        // Opcional: Recargar la página o hacer alguna acción adicional
                        Livewire.dispatch('refresh');
                    });
                });



            });
        </script>
    @endpush
    <!--  -->
</div>
