<div>

    <div class="bg-blue-900 text-white p-2 shadow-md rounded flex justify-between items-center relative z-10"">
        <h2 class=" font-semibold text-xl text-gray-100 leading-tight">
        {{ __('Registrar Sanciones') }}
        </h2>
        {{-- Botón para volver a la lista de categorías --}}
        <div class="flex"><a href="{{ route('sanciones.index') }}"
                class="cursor-pointer text-white px-4 py-2 rounded flex items-center gap-2 hover:underline"> <svg
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Volver</a>

            <div class="hidden md:flex"><button wire:click="actualizarCumplimientosSanciones"
                    class="bg-[#FFC107] cursor-pointer hover:bg-[#d6a82b] text-gray-800     font-bold py-2 px-4 mb-2 rounded">
                    Actualizar Sanciones
                </button>
            </div>
        </div>
    </div>

    <div
        class="grid grid-cols-1 md:grid-cols-5 gap-4 bg-gray-200 border border-gray-500 dark:bg-gray-700 p-4 rounded-lg shadow-md">
        <div>

            <label for="first_name" class="block m-2 dark:text-white ">Buscar Jugador
            </label>
            <input type="text" wire:model="buscarJugador" wire:keydown.enter="buscarJugadorSancion"
                class="bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-500 dark:border-gray-600 dark:placeholder-gray-300 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Ingrese documento y presione Enter">
        </div>

    </div>
    @if($buscarJugador && count($jugadores) > 0)
    <div
        class="mb-4 bg-gray-500 border border-gray-300 text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        @foreach($jugadores as $jug)
        <div class="flex justify-between items-center border-b py-1">
            <span>{{ $jug->apellido }}, {{ $jug->nombre }} - DNI ( {{ $jug->documento }} )</span>
            <button wire:click="agregarJugador({{ $jug->id }})"
                class="bg-green-500 text-white hover:bg-green-400 px-2 py-1 rounded cursor-pointer">Seleccionar</button>
        </div>
        @endforeach
    </div>
    @endif
    @if($jugadorSeleccionado)
    <div class="bg-blue-100 border border-blue-300 text-blue-900 rounded-lg p-4 mb-4">
        <strong>{{ $jugadorSeleccionado['apellido'] }}, {{ $jugadorSeleccionado['nombre'] }}</strong><br>
        DNI: {{ $jugadorSeleccionado['documento'] }}
        {{-- Podés agregar más datos o botones aquí --}}
    </div>
    @endif
    <hr class="m-5">
    <div
        class="grid grid-cols-1 md:grid-cols-3 mt-4 gap-4  bg-gray-200 border border-gray-500 dark:bg-gray-700 p-4 rounded-lg shadow-md">

        <div>
            <label class="block m-2 dark:text-white ">Sanción</label>
            <select wire:model="motivo"
                class="bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-50 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">Tipo </option>
                <option value="5 amarillas">5 amarillas</option>
                <option value="doble amarilla">Doble amarilla</option>
                <option value="roja directa">Roja directa</option>
            </select>
        </div>

        <div>
            <label class="block m-2 dark:text-white ">Jornada de sanción</label>
            <input type="number" wire:model="fecha_sancion"
                class="bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="fecha jornada de encuentro " required />
        </div>

        <div>
            <label class="block m-2 dark:text-white ">Cantidad de fechas</label>
            <input type="number" wire:model="partidos_sancionados"
                class="bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder=" 1 " required min="1" />
        </div>

    </div>
    <div>
        <label class="block m-2 dark:text-white ">Observacion</label>
        <textarea wire:model="observacion" id="message" rows="4"
            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-500 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Escriba aquí una observación..."></textarea>
    </div>



    <div class="flex gap-4 items-center">
        <button wire:click="guardar"
            class="cursor-pointer inline-flex items-center gap-2 mt-4  bg-blue-950 hover:bg-blue-800 text-white px-4 py-2 rounded ">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-save-icon lucide-save">
                <path
                    d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
                <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7" />
                <path d="M7 3v4a1 1 0 0 0 1 1h7" />
            </svg> <span>Guardar</span></button>

        <button wire:click="actualizarCumplimientosSanciones"
            class=" md:hidden cursor-pointer inline-flex items-center gap-2 mt-4 font-semibold  bg-[#fce307] hover:bg-[#5a5207] hover:text-gray-300 text-black  px-4 py-2 rounded">
            Actualizar Sanciones
        </button>

    </div>

</div>