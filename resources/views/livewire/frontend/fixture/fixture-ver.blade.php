<div>
    <x-layouts.app.frontend :title="__('Fixture')">
        <div class="w-full">
            <div class="bg-blue-900 text-white p-2 shadow-md rounded flex justify-between items-center relative z-10">
                <h1 class="text-lg font-bold">Tabla de posiciones</h1>

                <!--Nav para móvil (se muestra hasta md)  -->
                <nav class="flex md:hidden space-x-4">
                    <a href="{{ route('frontend.fixture.index') }}" class="text-white px-4 py-2 rounded flex items-center gap-2 hover:underline">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </a>
                </nav>

                <!--Nav para escritorio (md en adelante)  -->
                <nav class="hidden md:flex space-x-4">
                    <a href="{{ route('frontend.fixture.index') }}" class="text-white px-4 py-2 rounded flex items-center gap-2 hover:underline">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>Volver
                    </a>
                </nav>
            </div>
            <div class="mb-4 grid grid-cols-1 md:grid-cols-1 gap-4 bg-gray-200 p-3 rounded-lg shadow-md">

                <!-- Filtros -->
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-4 bg-gray-200 p-3 rounded-lg shadow-md">

                    <!-- Fecha -->
                    <input wire:model.live="fechaFiltro" type="date"
                        class="hidden md:block bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500  w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Fecha encuentro" />

                    <!-- Fecha Encuentro -->

                    <input type="text" id="jornadaFiltro" wire:model.live="jornadaFiltro"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Fecha Jornada" />

                    <!-- Estado -->
                    <select wire:model.live="estadoFiltro"
                        class="hidden md:block bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

                        <option value="">-- Estado --</option>
                        <option value="programado">Programado</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="jugado">Jugado</option>
                    </select>

                    <!-- Grupo -->
                    <select wire:model.live="grupoFiltro"
                        class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

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


                <!-- Tabla -->
                {{-- @if (isset($encuentros) && $encuentros->count())
                @else
                    <p class="mt-4 text-gray-500">No hay encuentros que coincidan con los filtros.</p>
                @endif --}}
                {{-- --}}

            </div>

            <div class="overflow-x-auto border border-gray-300 dark:bg-gray-900 p-4 shadow-md sm:rounded-lg">
                @foreach ($encuentrosAgrupados as $nombreCancha => $encuentros)
                <div class="mb-6">
                    <!-- Nombre de la cancha -->

                    @php
                    $grupos = $encuentros->pluck('grupo.nombre')->unique()->filter();
                    @endphp
                    <div class="mb-2 text-center p-4 rounded-b-sm text-lg font-semibold text-gray-900 dark:text-gray-900 bg-accent flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 40" width="32" height="20" class="inline-block">
                            <!-- Fondo -->
                            <rect x="0" y="0" width="64" height="40" fill="#4CAF50" stroke="#2E7D32" stroke-width="1" />
                            <!-- Línea central -->
                            <line x1="32" y1="0" x2="32" y2="40" stroke="white" stroke-width="1" />
                            <!-- Círculo central -->
                            <circle cx="32" cy="20" r="5" stroke="white" stroke-width="1" fill="none" />
                            <circle cx="32" cy="20" r="1" fill="white" />
                            <!-- Áreas grandes -->
                            <rect x="0" y="10" width="6" height="20" stroke="white" stroke-width="1" fill="none" />
                            <rect x="58" y="10" width="6" height="20" stroke="white" stroke-width="1" fill="none" />
                            <!-- Arcos -->
                            <path d="M0,14 A4,4 0 0,1 0,26" stroke="white" stroke-width="1" fill="none" />
                            <path d="M64,14 A4,4 0 0,0 64,26" stroke="white" stroke-width="1" fill="none" />
                        </svg>

                        <p class="m-0">
                            "{{ strtoupper($nombreCancha) }}"
                            @if($grupos && $grupos->isNotEmpty())
                            <span class="bg-[#eee16b] rounded-3xl px-2 py-1">
                                {{ strtoupper($grupos->implode(', ')) }}
                            </span>
                            @endif
                        </p>
                    </div>

                    <!-- Tabla de encuentros - Versión Desktop -->
                    <div class="hidden sm:block ">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 shadow-md sm:rounded-lg">
                            <thead class="text-xs text-gray-100 uppercase bg-gray-500 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-2 sm:px-4 py-2 text-center">Fecha</th>
                                    <th class="px-2 sm:px-4 py-2 text-center">Hora</th>
                                    <th class="px-2 sm:px-4 py-2 text-center">Local</th>
                                    <th class="px-2 sm:px-4 py-2 text-center">Gol</th>
                                    <th class="px-2 sm:px-4 py-2 text-center">Gol</th>
                                    <th class="px-2 sm:px-4 py-2 text-center">Visitante</th>
                                    <th class="px-2 sm:px-4 py-2 text-center">Estado</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($encuentros as $encuentro)

                                <tr
                                    class=" border-b border-gray-400 dark:border-gray-200  hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <td class="px-2 sm:px-4 py-2 text-center">
                                        {{ \Carbon\Carbon::parse($encuentro->fecha)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-2 sm:px-4 py-2 text-center">
                                        {{ \Carbon\Carbon::parse($encuentro->hora)->format('H:i') }}
                                    </td>
                                    <td class="px-2 sm:px-4 py-2 text-sm text-gray-800 font-semibold  dark:text-gray-200">
                                        {{ strtoupper($encuentro->equipoLocal->nombre) }}
                                    </td>
                                    <td
                                        class="w-16 px-1 sm:px-2 py-2 text-center  dark:border-gray-200">
                                        <input type="number" wire:model="goles_local.{{ $encuentro->id }}"
                                            class="w-full px-1 sm:px-2 py-1 border border-gray-400 rounded text-center text-gray-950 bg-gray-200"
                                            min="0" />

                                    </td>

                                    <td
                                        class="w-16 px-1 sm:px-2 py-2 text-center ">
                                        <input type="number" wire:model="goles_visitante.{{ $encuentro->id }}"
                                            class="w-full px-1 sm:px-2 py-1 border  border-gray-400 rounded text-center text-gray-950 bg-gray-200"
                                            min="0" />
                                    </td>
                                    <td class="px-2 sm:px-4 py-2 text-sm font-semibold text-gray-800 dark:text-gray-200">
                                        {{strtoupper( $encuentro->equipoVisitante->nombre )}}
                                    </td>

                                    <td class="px-2 sm:px-4 py-2 text-center ">
                                        {{ strtoupper($encuentro->estado) }}
                                    </td>


                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!--///////////------ Versión Móvil ////////////////////// -->
                    <div class="sm:hidden space-y-2 ">
                        @foreach ($encuentros as $encuentro)
                        <div class="border border-gray-300 bg-gray-50 shadow-2xs rounded-lg overflow-hidden  text-gray-900 dark:bg-gray-800">
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
                                    <span class="font-semibold text-gray-800 dark:text-gray-100">
                                        {{ strtoupper($encuentro->equipoLocal->nombre) }}
                                    </span>

                                    <span class="w-12 h-12 flex items-center justify-center rounded-full bg-gray-200 text-gray-900 font-bold">
                                        {{ $goles_local[$encuentro->id] ?? 0 }}
                                    </span>
                                </div>

                                <!-- Equipo visitante -->
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-semibold text-gray-800 dark:text-gray-100">
                                        {{ strtoupper($encuentro->equipoVisitante->nombre) }}
                                    </span>

                                    <span class="w-12 h-12 flex items-center justify-center rounded-full bg-gray-200 text-gray-900 font-bold">
                                        {{ $goles_visitante[$encuentro->id] ?? 0 }}
                                    </span>
                                </div>

                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
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
    </x-layouts.app.frontend>
</div>