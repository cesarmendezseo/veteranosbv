<div>
    {{-- Contenedor principal del componente --}}
     <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Cargar Estadistica') }}
        </h2>
        <div class="flex items-center space-x-4">
        {{-- Botón para volver a la lista de categorías --}}
        <a href="{{ route('sanciones.index') }}" class=" text-white px-4 py-2 rounded flex items-center gap-2 hover:underline"> <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            Volver</a>
        </div>
    </x-slot>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 bg-gray-300 dark:bg-gray-800 p-4 rounded-lg shadow-lg">



        <div class="mb-4">
            <label class="block font-medium text-base dark:text-[#FFC107]">Fecha Jornada</label>
            <select wire:model.live="fechaSeleccionada"
                class="bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">-- Selecciona una fecha --</option>
                @foreach ($fechasDisponibles as $jornada)
                <option value="{{ $jornada }}">Fecha N°:{{ $jornada }}</option>
                @endforeach
            </select>
        </div>


        @if ($encuentros)
        <div class="mb-4">
            <label class="block font-medium text-base dark:text-[#FFC107]">Local VS Visitante</label>
            <select wire:model.live="encuentroSeleccionado"
                class="bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">-- Selecciona un encuentro --</option>
                @foreach ($encuentros as $encuentro)
                <option value="{{ $encuentro->id }}"> {{ strtoupper($encuentro->equipoLocal->nombre) }} ....vs.... {{ strtoupper($encuentro->equipoVisitante->nombre) }}
                </option>
                @endforeach
            </select>
        </div>
        @endif
    </div>


    @if ($jugadoresLocal && $jugadoresVisitante)
    <form wire:submit.prevent="guardarDatos">
        <style>
            /* Mostrar títulos de columna en móvil */
            @media (max-width: 768px) {
                td[data-label]::before {
                    content: attr(data-label) ": ";
                    font-weight: bold;
                    display: inline-block;
                    width: 130px;
                    color: #111827;
                    /* text-gray-900 */
                }
            }
        </style>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

            {{-- LOCAL --}}
            <div class="bg-blue-900 text-white p-2 shadow-md rounded flex justify-between items-center relative">
                <h2 class="text-1xl font-extrabold text-gray-100 p-2">Local :
                    <span class="text-accent">{{ strtoupper($this->nombreLocal) }}</span>
                </h2>
            </div>
            <div class="max-h-96 overflow-y-auto bg-gray-200 rounded-lg shadow-md">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 block md:table">
                    <thead class="hidden md:table-header-group text-xs text-gray-100 uppercase bg-gray-500 dark:bg-gray-800 dark:text-gray-100">
                        <tr>
                            <th class="px-6 py-3">Apellido y Nombre</th>
                            <th class="px-6 py-3 text-center">Gol</th>
                            <th class="px-6 py-3 text-center">Amarilla</th>
                            <th class="px-6 py-3 text-center">Doble Amarilla</th>
                            <th class="px-6 py-3 text-center">Roja</th>
                        </tr>
                    </thead>
                    <tbody class="block md:table-row-group">
                        @foreach ($jugadoresLocal as $jugador)
                        <tr class="block md:table-row odd:bg-white even:bg-gray-300 border-b dark:border-gray-700">
                            <td data-label="Jugador" class="block md:table-cell px-6 py-4 font-semibold text-gray-900 dark:text-gray-700 text-left">
                                {{ strtoupper($jugador->apellido) }}, {{ strtoupper($jugador->nombre) }}
                            </td>
                            <td data-label="Gol" class="block md:table-cell px-6 py-4 text-left md:text-center">
                                <input type="number" wire:model.defer="datosJugadores.{{ $jugador->id }}.goles"
                                    class="w-full md:w-14 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg p-2.5" placeholder="0" />
                            </td>
                            <td data-label="Amarilla" class="block md:table-cell px-6 py-4 text-left md:text-center">
                                <input type="checkbox" wire:model.defer="datosJugadores.{{ $jugador->id }}.amarilla"
                                    class="w-5 h-5 text-blue-600 border-gray-300 rounded" />
                            </td>
                            <td data-label="Doble Amarilla" class="block md:table-cell px-6 py-4 text-left md:text-center">
                                <input type="checkbox" wire:model.defer="datosJugadores.{{ $jugador->id }}.doble_amarilla"
                                    class="w-5 h-5 text-blue-600 border-gray-300 rounded" />
                            </td>
                            <td data-label="Roja" class="block md:table-cell px-6 py-4 text-left md:text-center">
                                <input type="checkbox" wire:model.defer="datosJugadores.{{ $jugador->id }}.roja"
                                    class="w-5 h-5 text-blue-600 border-gray-300 rounded" />
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- VISITANTE --}}
            <div class="bg-blue-900 text-white p-2 shadow-md rounded flex justify-between items-center relative mt-4">
                <h2 class="text-1xl font-extrabold text-gray-100 p-2">Visitante :
                    <span class="text-accent">{{ strtoupper($this->nombreVisitante) }}</span>
                </h2>
            </div>
            <div class="max-h-96 overflow-y-auto bg-gray-200 rounded-lg shadow-md">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 block md:table">
                    <thead class="hidden md:table-header-group text-xs text-gray-100 uppercase bg-gray-500 dark:bg-gray-800 dark:text-gray-100">
                        <tr>
                            <th class="px-6 py-3">Apellido y Nombre</th>
                            <th class="px-6 py-3 text-center">Gol</th>
                            <th class="px-6 py-3 text-center">Amarilla</th>
                            <th class="px-6 py-3 text-center">Doble Amarilla</th>
                            <th class="px-6 py-3 text-center">Roja</th>
                        </tr>
                    </thead>
                    <tbody class="block md:table-row-group">
                        @foreach ($jugadoresVisitante as $jugador)
                        <tr class="block md:table-row odd:bg-white even:bg-gray-300 border-b dark:border-gray-700">
                            <td data-label="Jugador" class="block md:table-cell px-6 py-4 font-semibold text-gray-900 dark:text-gray-700 text-left">
                                {{ strtoupper($jugador->apellido) }}, {{ strtoupper($jugador->nombre) }}
                            </td>
                            <td data-label="Gol" class="block md:table-cell px-6 py-4 text-left md:text-center">
                                <input type="number" wire:model.defer="datosJugadores.{{ $jugador->id }}.goles"
                                    class="w-full md:w-14 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg p-2.5" placeholder="0" />
                            </td>
                            <td data-label="Amarilla" class="block md:table-cell px-6 py-4 text-left md:text-center">
                                <input type="checkbox" wire:model.defer="datosJugadores.{{ $jugador->id }}.amarilla"
                                    class="w-5 h-5 text-blue-600 border-gray-300 rounded" />
                            </td>
                            <td data-label="Doble Amarilla" class="block md:table-cell px-6 py-4 text-left md:text-center">
                                <input type="checkbox" wire:model.defer="datosJugadores.{{ $jugador->id }}.doble_amarilla"
                                    class="w-5 h-5 text-blue-600 border-gray-300 rounded" />
                            </td>
                            <td data-label="Roja" class="block md:table-cell px-6 py-4 text-left md:text-center">
                                <input type="checkbox" wire:model.defer="datosJugadores.{{ $jugador->id }}.roja"
                                    class="w-5 h-5 text-blue-600 border-gray-300 rounded" />
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- BOTÓN GUARDAR --}}
        <button type="submit"
            class="inline-flex items-center gap-2 cursor-pointer mt-4 bg-blue-950 hover:bg-blue-800 text-white px-4 py-2 rounded">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-save">
                <path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
                <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7" />
                <path d="M7 3v4a1 1 0 0 0 1 1h7" />
            </svg>
            <span>Guardar</span>
        </button>
    </form>
    @endif




    {{-- Scripts para SweetAlert --}}

    @push('js')
    <script>
        document.addEventListener('livewire:initialized', () => {



            Livewire.on('confirmar-baja', ({
                id
            }) => {


                Swal.fire({
                    title: 'CUIDADO...',
                    text: "¿Estás seguro de borrar al el Campeonato?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, Borrar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('eliminar-campeonato', {
                            id: id
                        });
                    }
                });
            });

            Livewire.on('seleccionar-campeonato', () => {
                Swal.fire(
                    '¡Atención!',
                    'Debe seleccionar un Torneo y un encuentro.',
                    'info'
                );
            });
            Livewire.on('ok', () => {
                Swal.fire(
                    'Ok!',
                    'Guardado correctamente.',
                    'success'
                );
            });
            //para errroes de validación
            Livewire.on('alertaError', (event) => {
                const message = event.message; // Accede a los datos del evento
                Swal.fire({
                    icon: 'error',
                    title: 'Errores de validación',
                    text: message.replace(/\n/g, '\n'),
                    customClass: {
                        popup: 'text-sm'
                    }
                });
            });
        });
    </script>
    @endpush
</div>
{{-- Fin del componente Livewire --}}