<div
    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">


    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold mb-4">Registrar Sanción</h2>
        <button wire:click="actualizarCumplimientosSanciones"
            class="bg-[#FFC107] hover:bg-[#d6a82b] text-gray-800     font-bold py-2 px-4 mb-2 rounded">
            Actualizar Sanciones
        </button>
    </div>
    <hr class="m-2">


    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>

            <label for="first_name" class="block m-2 dark:text-[#FFC107]">Buscar por
                DNI</label>
            <input type="text" wire:model="buscarJugador" wire:keydown.enter="buscarJugadorSancion"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-500 dark:border-gray-600 dark:placeholder-gray-800 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Ingrese documento y presione Enter">

        </div>
        <div class="md:col-span-2">
            <label class="block m-2 dark:text-[#FFC107]">Jugador</label>
            <select wire:model="jugador_id"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">Seleccionar jugador</option>
                @foreach ($jugadores as $jugador)
                <option value="{{ $jugador->id }}">
                    {{ $jugador->documento }} __ {{ $jugador->apellido }} {{ $jugador->nombre }}
                    - ({{ $jugador->equipo->nombre ?? 'Sin equipo' }})
                </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block m-2 dark:text-[#FFC107]">Campeonato</label>
            <select wire:model="campeonato_id"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

                <option value="">Seleccionar campeonato</option>
                @foreach ($campeonatos as $camp)
                <option value="{{ $camp->id }}">{{ $camp->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block m-2 dark:text-[#FFC107]">Sanción</label>
            <select wire:model="motivo"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-25 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">Tipo Sancion</option>
                <option value="5 amarillas">5 amarillas</option>
                <option value="doble amarilla">Doble amarilla</option>
                <option value="roja directa">Roja directa</option>
            </select>
        </div>
    </div>
    <hr class="m-5">
    <div class="grid grid-cols-1 md:grid-cols-2 mt-4 gap-4">
        <div class="grid grid-cols-1 md:grid-cols-2 mt-4 gap-4">
            <div>
                <label class="block m-2 dark:text-[#FFC107]">Jornada de sanción</label>
                <input type="number" wire:model="fecha_sancion"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="fecha jornada de encuentro " required />
            </div>

            <div>
                <label class="block m-2 dark:text-[#FFC107]">Cantidad de fechas</label>
                <input type="number" wire:model="partidos_sancionados"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder=" 1 " required min="1" />
            </div>

        </div>
        <div>
            <label class="block m-2 dark:text-[#FFC107]">Observacion</label>
            <textarea wire:model="observacion" id="message" rows="4"
                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Escriba aquí una observación..."></textarea>
        </div>
    </div>


    <button wire:click="guardar" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">
        Guardar sanción
    </button>

    @push('js')
    <script>
        document.addEventListener('livewire:initialized', () => {

            Livewire.on('guardar-sancion', () => {
                Swal.fire({
                    'title': 'Guardado',
                    'text': 'La sanción ha sido guardado correctamente',
                    'icon': 'success'
                });
            });

            Livewire.on('actualizar-sancion', () => {
                Swal.fire({
                    'title': 'Correcto!',
                    'text': 'La sanción ha sido actualizado correctamente',
                    'icon': 'success'
                });
            });


        })
    </script>
    @endpush