<div>
    {{-- Selectores de año, campeonato y fecha --}}
    <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Año -->
        <div>
            <select wire:model.live="anioSeleccionadoEditar"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">-- Selecciona un año --</option>
                @foreach ($aniosDisponiblesEditar as $anio)
                <option value="{{ $anio }}">{{ $anio }}</option>
                @endforeach
            </select>
        </div>

        @if ($campeonatosEditar)
        <!-- Campeonato -->
        <div>
            <select wire:model.live="campeonato_id_Editar"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">-- Selecciona un campeonato --</option>
                @foreach ($campeonatosEditar as $campeonato)
                <option value="{{ $campeonato->id }}">{{ $campeonato->nombre }}
                    ({{ $campeonato->created_at->format('Y') }})
                </option>
                @endforeach
            </select>
        </div>
        @endif

        <!-- Fecha Encuentro -->
        @if ($campeonato_id_Editar)
        <div>
            <input type="text" id="jornadaFiltroEditar" wire:model.live="jornadaFiltroEditar"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        </div>
        @endif
    </div>

    <div>
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400"">
            <thead class=" text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th class="px-6 py-3 text-xs md:text-sm">Fecha</th>
                <th class="px-6 py-3 text-xs md:text-sm">Hora</th>
                <th class="px-6 py-3 text-xs md:text-sm">Jornada</th>
                <th class="px-6 py-3 text-xs md:text-sm">Cancha</th>
                <th class="px-6 py-3 text-xs md:text-sm">Local</th>
                <th class="px-6 py-3 text-xs md:text-sm">Visitante</th>
                <th class="px-6 py-3 text-xs md:text-sm">Acción</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($encuentros as $encuentro)
                <tr>
                    @if ($encuentroEditId == $encuentro->id)
                    <!-- Modo Edición -->
                    <td class="px-4 py-2 border dark:text-gray-800">
                        <input wire:model="fecha_edit" type="date" class="border rounded px-2 py-1 w-full">
                    </td>
                    <td class="px-4 py-2 border dark:text-gray-800">
                        <input wire:model="hora_edit" type="time" class="border rounded px-2 py-1 w-full">
                    </td>
                    <td class="px-4 py-2 border dark:text-gray-800 ">
                        <input wire:model="fecha_encuentro_edit" type="number" min="1"
                            class="border rounded px-2 py-1 w-full">
                    </td>
                    <td class="px-4 py-2 border dark:text-gray-800">
                        <select wire:model="cancha_id_edit" class="border rounded px-2 py-1 w-full">
                            @foreach ($canchas as $cancha)
                            <option value="{{ $cancha->id }}">{{ $cancha->nombre }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-4 py-2 border dark:text-gray-200">{{ $encuentro->equipoLocal->nombre }}</td>
                    <td class="px-4 py-2 border dark:text-gray-200">{{ $encuentro->equipoVisitante->nombre }}
                    </td>
                    <td class="px-4 py-2 border">

                        <div class="flex space-x-2">

                            <button wire:click="updateEncuentro"
                                class="px-3 py-2 text-sm font-medium text-center inline-flex items-center text-white bg-[#19ac3e] rounded-lg hover:bg-[#41e22c] focus:ring-4 focus:outline-none focus:ring-[#FFECB3]"
                                title="Guardar">

                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 15v2a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-2m-8 1V4m0 12-4-4m4 4 4-4" />
                                </svg>

                            </button>

                            {{-- ===============BOTON CANCELAR============== --}}
                            <button wire:click="resetEdit"
                                class="px-3 py-2 text-sm font-medium text-center inline-flex items-center text-white bg-[#1740f8] rounded-lg hover:bg-[#2caee2] focus:ring-4 focus:outline-none focus:ring-[#FFECB3]"
                                title="Cancelar">

                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M14.502 7.046h-2.5v-.928a2.122 2.122 0 0 0-1.199-1.954 1.827 1.827 0 0 0-1.984.311L3.71 8.965a2.2 2.2 0 0 0 0 3.24L8.82 16.7a1.829 1.829 0 0 0 1.985.31 2.121 2.121 0 0 0 1.199-1.959v-.928h1a2.025 2.025 0 0 1 1.999 2.047V19a1 1 0 0 0 1.275.961 6.59 6.59 0 0 0 4.662-7.22 6.593 6.593 0 0 0-6.437-5.695Z" />
                                </svg>


                            </button>
                        </div>
                    </td>
                    @else
                    <!-- Modo Visualización -->
                    <td class="px-4 py-2 border dark:text-gray-200">
                        {{ \Carbon\Carbon::parse($encuentro->fecha)->format('d/m/Y') }}
                    </td>
                    <td class="px-4 py-2 border dark:text-gray-200">{{ $encuentro->hora }}</td>
                    <td class="px-4 py-2 border dark:text-gray-200 text-center">
                        {{ $encuentro->fecha_encuentro }}
                    </td>
                    <td class="px-4 py-2 border dark:text-gray-200">{{ $encuentro->cancha->nombre }}</td>
                    <td class="px-4 py-2 border dark:text-gray-200">{{ $encuentro->equipoLocal->nombre }}</td>
                    <td class="px-4 py-2 border dark:text-gray-200">{{ $encuentro->equipoVisitante->nombre }}
                    </td>
                    <td class="px-4 py-2 border dark:text-gray-200">

                        @adminOrCan('cargar gol')
                        {{-- =======BOTON EDITAR --}}
                        <button wire:click="editEncuentro({{ $encuentro->id }})"
                            class="px-3 py-2 text-sm font-medium text-center inline-flex items-center text-white bg-[#FFC107] rounded-lg hover:bg-[#FFD54F] focus:ring-4 focus:outline-none focus:ring-[#FFECB3]"
                            title="Editar">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M14 4.182A4.136 4.136 0 0 1 16.9 3c1.087 0 2.13.425 2.899 1.182A4.01 4.01 0 0 1 21 7.037c0 1.068-.43 2.092-1.194 2.849L18.5 11.214l-5.8-5.71 1.287-1.31.012-.012Zm-2.717 2.763L6.186 12.13l2.175 2.141 5.063-5.218-2.141-2.108Zm-6.25 6.886-1.98 5.849a.992.992 0 0 0 .245 1.026 1.03 1.03 0 0 0 1.043.242L10.282 19l-5.25-5.168Zm6.954 4.01 5.096-5.186-2.218-2.183-5.063 5.218 2.185 2.15Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
@endadminOrCan
@adminOrCan('comision')
                        <!-- Botón para borrar -->
                        <button wire:click="$dispatch('delete-prompt', { id: {{ $encuentro->id }} })"
                            class="px-3 py-2 text-sm font-medium text-center inline-flex items-center text-white bg-[#f12019] rounded-lg hover:bg-[#d17d2f] focus:ring-4 focus:outline-none focus:ring-[#FFECB3]"
                            title="Eliminar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-circle-x-icon lucide-circle-x">
                                <circle cx="12" cy="12" r="10" />
                                <path d="m15 9-6 6" />
                                <path d="m9 9 6 6" />
                            </svg>

                        </button>
                        @endadminOrCan
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @push('js')
    <script>
        document.addEventListener('livewire:initialized', () => {
            /*  @this.on('swal', (event) => {
                 const data = event
                 swal.fire({
                     icon: data[0]['icon'],
                     title: data[0]['title'],
                     text: data[0]['text'],

                 });
             }) */
            Livewire.on('delete-prompt', ({
                id
            }) => {
                Swal.fire({
                    title: 'CUIDADO...Estás seguro de Eliminar?',
                    text: "Esta acción no se puede deshacer",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('confirm-delete', {
                            id: id
                        });
                    }
                });
            });


            Livewire.on('deleted', () => {
                Swal.fire(
                    'Eliminado',
                    'El registro ha sido borrado exitosamente.',
                    'success'
                );
            });
        });
    </script>
    @endpush

</div>