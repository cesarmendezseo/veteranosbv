<div>
    {{-- Contenedor principal del componente --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        {{-- SELECCIONAMOS EL CAMPEONATO --}}
        <div class="mb-4">
            <label class="block font-medium text-base dark:text-[#FFC107]">Torneo</label>
            <select wire:model.live="campeonatoId"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">Seleccione un torneo</option>
                @foreach ($campeonatos as $campeonato)
                <option value="{{ $campeonato->id }}">{{ strtoupper($campeonato->nombre) }}</option>
                @endforeach
            </select>
        </div>

        {{-- SI EXISTE EL CAMPEONATO --}}
        @if ($campeonatoId)
        <div class="mb-4">
            <label class="block font-medium text-base dark:text-[#FFC107]">Fecha Jornada</label>
            <select wire:model.live="fechaSeleccionada"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">-- Selecciona una fecha --</option>
                @foreach ($fechasDisponibles as $jornada)
                <option value="{{ $jornada }}">Fecha N°:{{ $jornada }}</option>
                @endforeach
            </select>
        </div>
        @endif

        @if ($encuentros)
        <div class="mb-4">
            <label class="block font-medium text-base dark:text-[#FFC107]">Local VS Visitante</label>
            <select wire:model.live="encuentroSeleccionado"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
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
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <div class="p-2 bg-[#FFC107] dark:bg-[#FFC107]">
                <h2 class="text-1xl font-extrabold text-[#FF4500] dark:text-[#050505] p-2">Local :
                    <span class="text-black">{{ strtoupper($this->nombreLocal) }}</span>
                </h2>
            </div>
            <div class="max-h-96 overflow-y-auto shadow-lg rounded-lg">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-100 uppercase bg-gray-500 dark:bg-gray-800 dark:text-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-3 sticky top-0 bg-gray-500">Apellido y Nombre</th>
                            <th scope="col" class="px-6 py-3 text-center align-middle sticky top-0 bg-gray-500">Gol</th>
                            <th scope="col" class="px-6 py-3 text-center align-middle sticky top-0 bg-gray-500">Amarilla</th>
                            <th scope="col" class="px-6 py-3 text-center align-middle sticky top-0 bg-gray-500">Doble Amarilla</th>
                            <th scope="col" class="px-6 py-3 text-center align-middle sticky top-0 bg-gray-500">Roja</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jugadoresLocal as $index => $jugador)
                        <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                            <input type="hidden" wire:model.defer="datosJugadores.{{ $jugador->id }}.equipo_id">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ strtoupper($jugador->apellido) }}, {{ strtoupper($jugador->nombre) }}
                            </th>
                            <td class="px-6 py-4 text-center align-middle">
                                <input type="number" id="gol_local_{{ $jugador->id }}"
                                    wire:model.defer="datosJugadores.{{ $jugador->id }}.goles"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 
                               focus:border-blue-500 block w-14 p-2.5 dark:bg-gray-700 dark:border-gray-600 
                               dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="0" />
                            </td>
                            <td class="px-6 py-4 text-center align-middle">
                                <input id="amarilla_local_{{ $jugador->id }}" type="checkbox"
                                    wire:model.defer="datosJugadores.{{ $jugador->id }}.amarilla"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm 
                               focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 
                               focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            </td>
                            <td class="px-6 py-4 text-center align-middle">
                                <input id="doble_amarilla_local_{{ $jugador->id }}" type="checkbox"
                                    wire:model.defer="datosJugadores.{{ $jugador->id }}.doble_amarilla"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm 
                               focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 
                               focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            </td>
                            <td class="px-6 py-4 text-center align-middle">
                                <input id="roja_local_{{ $jugador->id }}" type="checkbox"
                                    wire:model.defer="datosJugadores.{{ $jugador->id }}.roja"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm 
                               focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 
                               focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


            <div class="p-2 bg-[#FFC107] dark:bg-[#FFC107]">
                <h2 class="text-1xl font-extrabold text-[#FF4500] dark:text-[#050505] p-2">Visitante :
                    <span class="text-black">{{ strtoupper($this->nombreVisitante) }}</span>
                </h2>
            </div>
            <div class="max-h-96 overflow-y-auto shadow-lg rounded-lg">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-100 uppercase bg-gray-500 dark:bg-gray-800 dark:text-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-3 sticky top-0 bg-gray-500">Apellido y Nombre</th>
                            <th scope="col" class="px-6 py-3 text-center align-middle sticky top-0 bg-gray-500">Gol</th>
                            <th scope="col" class="px-6 py-3 text-center align-middle sticky top-0 bg-gray-500">Amarilla</th>
                            <th scope="col" class="px-6 py-3 text-center align-middle sticky top-0 bg-gray-500">Doble Amarilla</th>
                            <th scope="col" class="px-6 py-3 text-center align-middle sticky top-0 bg-gray-500">Roja</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jugadoresVisitante as $index => $jugador)
                        <tr
                            class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                            <input type="hidden" wire:model.defer="datosJugadores.{{ $jugador->id }}.equipo_id">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ strtoupper($jugador->apellido) }}, {{ strtoupper($jugador->nombre) }}
                            </th>
                            <td class="px-6 py-4 text-center align-middle">
                                <input type="number" id="gol_visitante_{{ $jugador->id }}"
                                    wire:model.defer="datosJugadores.{{ $jugador->id }}.goles"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-14 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="0" />
                            </td>
                            <td class="px-6 py-4 text-center align-middle">
                                <input id="amarilla_visitante_{{ $jugador->id }}" type="checkbox"
                                    wire:model.defer="datosJugadores.{{ $jugador->id }}.amarilla"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            </td>
                            <td class="px-6 py-4 text-center align-middle">
                                <input id="doble_amarilla_visitante_{{ $jugador->id }}" type="checkbox"
                                    wire:model.defer="datosJugadores.{{ $jugador->id }}.doble_amarilla"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            </td>
                            <td class="px-6 py-4 text-center align-middle">
                                <input id="roja_visitante_{{ $jugador->id }}" type="checkbox"
                                    wire:model.defer="datosJugadores.{{ $jugador->id }}.roja"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <button type="submit" class="flex mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" class="items-center mr-2 lucide lucide-save-icon lucide-save">
                <path
                    d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
                <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7" />
                <path d="M7 3v4a1 1 0 0 0 1 1h7" />
            </svg>
            Guardar
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