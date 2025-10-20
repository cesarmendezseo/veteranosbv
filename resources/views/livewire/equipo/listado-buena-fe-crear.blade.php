<div class="relative overflow-x-auto shadow-md sm:rounded-lg dark:bg-gray-600">
    <div class="bg-blue-900 text-white p-2 shadow-md rounded flex justify-between items-center relative z-10"">
        <h2 class=" font-semibold text-xl text-gray-100 leading-tight">
        {{ __('Agragar jugador a Listado de Buena Fe') }}
        </h2>

    </div>
    <div class="grid grid-cols-3 gap-4 mb-4 bg-gray-500 m-2 text-white p-2 rounded ">

        <div class=" ">
            <label>Equipo</label>
            <select wire:model="equipoSeleccionado"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

                <option value="">Seleccione...</option>
                @foreach($equipos as $e)
                <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Buscar jugador</label>
            <input type="text" wire:model.live.debounce.500ms="buscar" placeholder="Nombre, apellido o DNI" x-data
                x-on:focus-input.window="setTimeout(() => $el.focus(), 50)"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

        </div>
    </div>
    <div class=" p-2">
        @if($buscar && count($jugadores) > 0)
        <div
            class="mb-4 bg-gray-800 border border-gray-300 text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            @foreach($jugadores as $jug)
            <div class="flex justify-between items-center border-b py-1">
                <span>{{ $jug->apellido }}, {{ $jug->nombre }} - DNI ( {{ $jug->documento }} )</span>
                <button wire:click="agregarJugador({{ $jug->id }})"
                    class="bg-green-700 text-white hover:bg-green-500 rounded-full font-semibold hover:text-black px-2 py-2  cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </button>
            </div>
            @endforeach
        </div>
        @endif

        @if(count($jugadoresSeleccionados) > 0)
        <h3 class="font-semibold mb-2">Jugadores seleccionados:</h3>
        <table class="w-full border-collapse border border-gray-400 mb-4">
            <thead class="bg-blue-900 text-white p-4">
                <tr>
                    <th class="border px-2 dark:text-gray-100">#</th>
                    <th class="border px-2 dark:text-gray-100">Nombre</th>
                    <th class="border px-2 dark:text-gray-100">DNI</th>
                    <th class="border px-2 dark:text-gray-100">Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jugadoresSeleccionados as $i => $jug)
                <tr class="bg-gray-500 border border-gray-300 text-gray-100">
                    <td class="border px-2">{{ $i + 1 }}</td>
                    <td class="border px-2">{{ $jug['apellido'] }}, {{ $jug['nombre'] }}</td>
                    <td class="border px-2">{{ $jug['documento'] }}</td>
                    <td class="border px-2 text-center">
                        <button wire:click="quitarJugador({{ $i }})"
                            class="bg-red-500 text-white px-2 m-1 py-1 hover:bg-red-400 rounded cursor-pointer">Quitar</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <button wire:click="guardar" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 cursor-pointer">
            Guardar Lista Buena Fe
        </button>
        @endif
    </div>

</div>