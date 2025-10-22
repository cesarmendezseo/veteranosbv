<div class="relative overflow-x-auto shadow-md sm:rounded-lg dark:bg-gray-600">
    <div class="bg-blue-900 text-white p-2 shadow-md rounded flex justify-between items-center relative z-10"">
        <h2 class=" font-semibold text-xl text-gray-100 leading-tight">
        {{ __('Agragar jugador a Listado de Buena Fe') }}
        </h2>
        <a href="{{ route('listado-buena-fe') }}"
            class=" text-white px-4 py-2 rounded flex items-center gap-2 hover:underline"> <svg
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            Volver</a>
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
                <span>{{ strtoupper($jug->apellido) }}, {{ strtoupper($jug->nombre) }} - DNI ( {{ $jug->documento }}
                    )</span>
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
                    <td class="border px-2">{{ strtoupper($jug['apellido']) }}, {{ $jug['nombre'] }}</td>
                    <td class="border px-2">{{ strtoupper($jug['documento']) }}</td>
                    <td class="border px-2 text-center">
                        <button wire:click="quitarJugador({{ $i }})"
                            class="bg-red-500 text-white px-2 m-1 py-2 hover:bg-red-400 rounded-full cursor-pointer"><svg
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </button>
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