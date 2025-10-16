<div>
    <div class="bg-blue-900 text-white p-2 shadow-md rounded flex justify-between items-center relative z-10">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Fixture Eliminatoria editar') }}
        </h2>

        <div class="flex gap-2">
            <a href="{{ route('fixture.index') }}"
                class="md:hidden px-3 py-2 text-white rounded flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </a>
            @adminOrCan()
            <button wire:click="exportar"
                class="md:hidden cursor-pointer px-3 py-2 text-white rounded disabled:opacity-50 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-download">
                    <path d="M12 15V3" />
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                    <path d="m7 10 5 5 5-5" />
                </svg>
            </button>
            @endadminOrCan


            <div class="hidden md:flex gap-4">
                <a href="{{ route('fixture.index') }}" class="px-3 py-2 text-white rounded flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Volver
                </a>
                @adminOrCan()
                <button wire:click="exportar"
                    class="cursor-pointer px-3 py-2 text-white rounded disabled:opacity-50 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-download">
                        <path d="M12 15V3" />
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <path d="m7 10 5 5 5-5" />
                    </svg>
                    Exportar
                </button>
                @endadminOrCan
            </div>
        </div>
    </div>

    <div class="bg-gray-200 p-2 rounded-lg shadow-md">
        {{-- Boton para mostrar filtros (Solo Móvil) --}}
        <div class="md:hidden mb-4">
            <button wire:click="$toggle('mostrarFiltros')"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md">
                {{ $mostrarFiltros ? 'Aplicar' : 'Filtros' }}
            </button>
        </div>

        <div @class([ 'flex flex-col gap-3 md:grid md:grid-cols-3 lg:grid-cols-6 md:gap-4' , 'hidden'=> !$mostrarFiltros
            && !request()->is('md') ,
            ])>
            <input wire:model.live="fechaFiltro" type="date"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" />

            <select wire:model.live="estadoFiltro"
                class="cursor-pointer bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">-- Estado --</option>
                <option value="programado">Programado</option>
                <option value="pendiente">Pendiente</option>
                <option value="jugado">Jugado</option>
            </select>

            <select wire:model.live="faseFiltro"
                class="cursor-pointer bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">-- Fase --</option>
                @foreach($this->fases as $fase)
                <option value="{{ $fase }}">{{ ucfirst($fase) }}</option>
                @endforeach
            </select>

            <input wire:model.live="equipoLocalFiltro" type="text" placeholder="Equipo Local"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />

            <input wire:model.live="equipoVisitanteFiltro" type="text" placeholder="Equipo Visitante"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />

            {{-- Espacio para un filtro más si se necesitara el lg:grid-cols-6, sino se puede omitir este item o cambiar
            la grilla --}}
            <div></div>

        </div>
    </div>


    <div class="overflow-x-auto border border-gray-300 dark:bg-gray-900 p-4 shadow-md sm:rounded-lg">
        @foreach ($encuentrosAgrupados as $nombreCancha => $encuentros)
        <div class="mb-6">
            @php
            $grupos = $encuentros->pluck('grupo.nombre')->unique()->filter();
            @endphp
            <div
                class="mb-2 text-center p-4 rounded-b-sm text-lg font-semibold text-gray-900 dark:text-gray-900 bg-accent flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 40" width="32" height="20" class="inline-block">
                    <rect x="0" y="0" width="64" height="40" fill="#4CAF50" stroke="#2E7D32" stroke-width="1" />
                    <line x1="32" y1="0" x2="32" y2="40" stroke="white" stroke-width="1" />
                    <circle cx="32" cy="20" r="5" stroke="white" stroke-width="1" fill="none" />
                    <circle cx="32" cy="20" r="1" fill="white" />
                    <rect x="0" y="10" width="6" height="20" stroke="white" stroke-width="1" fill="none" />
                    <rect x="58" y="10" width="6" height="20" stroke="white" stroke-width="1" fill="none" />
                    <path d="M0,14 A4,4 0 0,1 0,26" stroke="white" stroke-width="1" fill="none" />
                    <path d="M64,14 A4,4 0 0,0 64,26" stroke="white" stroke-width="1" fill="none" />
                </svg>

                <p class="m-0 text-white dark:text-gray-900">
                    "{{ strtoupper($nombreCancha) }}"
                </p>
            </div>

            <div class="hidden sm:block ">
                <table
                    class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 shadow-md sm:rounded-lg">
                    <thead class="text-xs text-gray-100 uppercase bg-gray-500 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-2 sm:px-4 py-2 text-center">Fecha</th>
                            <th class="px-2 sm:px-4 py-2 text-center">Local</th>
                            <th class="px-2 sm:px-4 py-2 text-center">Goles Local</th>
                            <th class="px-2 sm:px-4 py-2 text-center">Goles Visit</th>
                            <th class="px-2 sm:px-4 py-2 text-center">Visitante</th>
                            <th class="px-2 sm:px-4 py-2 text-center">Fase</th>
                            <th class="px-2 sm:px-4 py-2 text-center">Estado</th>
                            <th class="px-2 sm:px-4 py-2 text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($encuentros as $encuentro)
                        <tr
                            class=" border-b border-gray-400 dark:border-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <td class="px-2 sm:px-4 py-2 text-center">
                                {{ \Carbon\Carbon::parse($encuentro->fecha)->format('d/m/Y') }}
                            </td>

                            <td class="px-2 sm:px-4 py-2 text-sm text-gray-800 font-semibold dark:text-gray-200">
                                {{ strtoupper($encuentro->equipoLocal->nombre) }}
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Penales</p>
                                </div>
                            </td>
                            <td class="w-16 px-1 sm:px-2 py-2 text-center dark:border-gray-200">
                                <input type="number" wire:model="goles_local.{{ $encuentro->id }}"
                                    class="w-full px-1 sm:px-2 py-1 border border-gray-400 rounded text-center text-gray-950 bg-gray-200"
                                    min="0" />
                                <div>
                                    <input type="number" wire:model="penal_local.{{ $encuentro->id }}"
                                        class="w-full px-1 sm:px-2 py-1 border border-gray-400 rounded text-center text-gray-950 bg-gray-200 mt-1"
                                        min="0" />
                                </div>
                            </td>
                            <td class="w-16 px-1 sm:px-2 py-2 text-center ">
                                <input type="number" wire:model="goles_visitante.{{ $encuentro->id }}"
                                    class="w-full px-1 sm:px-2 py-1 border border-gray-400 rounded text-center text-gray-950 bg-gray-200"
                                    min="0" />
                                <div>
                                    <input type="number" wire:model="penal_visitante.{{ $encuentro->id }}"
                                        class="w-full px-1 sm:px-2 py-1 border border-gray-400 rounded text-center text-gray-950 bg-gray-200 mt-1"
                                        min="0" />
                                </div>
                            </td>
                            <td class="px-2 sm:px-4 py-2 text-sm font-semibold text-gray-800 dark:text-gray-200">
                                {{ strtoupper($encuentro->equipoVisitante->nombre) }}
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Penales</p>
                                </div>
                            </td>

                            <td class="px-2 sm:px-4 py-2 text-center ">
                                {{ strtoupper($encuentro->fase) }}
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
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-save">
                                            <path
                                                d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
                                            <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7" />
                                            <path d="M7 3v4a1 1 0 0 0 1 1h7" />
                                        </svg>
                                    </button>
                                    {{-- EDITAR --}}
                                    @adminOrCan('comision')
                                    <button wire:click="editEncuentro({{ $encuentro->id }})"
                                        class="cursor-pointer text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-3 sm:px-5 py-2 text-center me-1 sm:me-2 mb-1 sm:mb-2 dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-800"
                                        title="Editar">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-square-pen">
                                            <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                            <path
                                                d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z" />
                                        </svg>
                                    </button>
                                    {{-- ELIMINAR --}}
                                    <button wire:click="eliminarEncuentro({{ $encuentro->id }})"
                                        class="cursor-pointer text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-3 sm:px-5 py-2 text-center me-1 sm:me-2 mb-1 sm:mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900"
                                        title="Eliminar">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-trash-2">
                                            <path d="M3 6h18" />
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                            <line x1="10" x2="10" y1="11" y2="17" />
                                            <line x1="14" x2="14" y1="11" y2="17" />
                                        </svg>
                                    </button>
                                    @endadminOrCan
                                </div>
                            </td>
                            @endadminOrCan
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="sm:hidden space-y-2 ">
                @foreach ($encuentros as $encuentro)
                <div
                    class="border border-gray-300 bg-gray-50 shadow-2xs rounded-lg overflow-hidden text-gray-900 dark:bg-gray-800">
                    <div class="bg-gray-800 dark:bg-gray-500 p-2 flex justify-between items-center">
                        <div class="font-medium text-white dark:text-gray-200">
                            {{ \Carbon\Carbon::parse($encuentro->fecha)->format('d/m/Y') }}
                        </div>
                        <div class="font-medium text-white dark:text-gray-200 text-sm">
                            {{ ucwords( $encuentro->fase) }} - {{ \Carbon\Carbon::parse($encuentro->hora)->format('H:i')
                            }}hs.
                        </div>
                        <div
                            class="px-2 py-1 bg-gray-500 dark:bg-gray-600 rounded text-sm text-white dark:text-gray-200">
                            {{ strtoupper($encuentro->estado) }}
                        </div>
                    </div>

                    <div class="p-3">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-semibold text-gray-800 dark:text-gray-100">{{
                                strtoupper($encuentro->equipoLocal->nombre) }}</span>
                            <div class="flex gap-2">
                                <input type="number" wire:model="goles_local.{{ $encuentro->id }}"
                                    class="w-12 px-1 py-1 border border-gray-300 rounded-lg text-center text-gray-950 bg-gray-100"
                                    min="0" placeholder="Gol" />
                                <label for="" class="dark:text-white">Penal</label>
                                <input type="number" wire:model="penal_local.{{ $encuentro->id }}"
                                    class="w-12 px-1 py-1 border border-gray-300 rounded-lg text-center text-gray-950 bg-gray-100"
                                    min="0" placeholder="Pen." />
                            </div>
                        </div>

                        <div class="flex items-center justify-between mb-3">
                            <span class="font-semibold text-gray-800 dark:text-gray-100">{{
                                strtoupper($encuentro->equipoVisitante->nombre) }}</span>
                            <div class="flex gap-2">

                                <input type="number" wire:model="goles_visitante.{{ $encuentro->id }}"
                                    class="w-12 px-1 py-1 border border-gray-300 rounded-lg text-center text-gray-950 bg-gray-100"
                                    min="0" placeholder="Gol" />
                                <div class="flex gap-2">
                                    <label for="" class="dark:text-white">Penal</label>
                                    <input type="number" wire:model="penal_visitante.{{ $encuentro->id }}"
                                        class="w-12 px-1 py-1 border border-gray-300 rounded-lg text-center text-gray-950 bg-gray-100"
                                        min="0" placeholder="Penal" />
                                </div>
                            </div>
                        </div>

                        @adminOrCan('comision|cargar gol')
                        <div class="flex justify-between space-x-2">
                            <button wire:click="guardarGoles({{ $encuentro->id }})" title="Guardar"
                                class="flex-1 flex items-center justify-center text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="mr-1">
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
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="mr-1">
                                    <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                    <path
                                        d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z" />
                                </svg>
                                <span class="hidden xs:inline">Editar</span>
                            </button>

                            <button wire:click="eliminarEncuentro({{ $encuentro->id }})"
                                class="flex-1 flex items-center justify-center text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-2 py-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900"
                                title="Eliminar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="mr-1">
                                    <path d="M3 6h18" />
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                    <line x1="10" x2="10" y1="11" y2="17" />
                                    <line x1="14" x2="14" y1="11" y2="17" />
                                </svg>
                                <span class="hidden xs:inline">Eliminar</span>
                            </button>
                            @endadminOrCan
                        </div>

                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

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

                <div class="mb-4">
                    <label class="block text-sm dark:text-gray-200">Estado:</label>
                    <select wire:model="editEstado" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg 
               focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 
               dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 
               dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Seleccione un estado</option>
                        <option value="programado">Programado</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="jugado">Jugado</option>
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
                        class="px-4 py-2 bg-red-500 text-white rounded-md flex items-center gap-2 cursor-pointer"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-circle-x-icon lucide-circle-x">
                            <circle cx="12" cy="12" r="10" />
                            <path d="m15 9-6 6" />
                            <path d="m9 9 6 6" />
                        </svg>Cancelar</button>
                    <button wire:click="guardarEdicion"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md flex items-center gap-2 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-save">
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

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

</div>