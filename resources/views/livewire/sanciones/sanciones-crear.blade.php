<div>
    {{-- Encabezado --}}
    <div class="bg-blue-900 text-white p-2 shadow-md rounded flex justify-between items-center relative z-10">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Registrar Sanciones') }}
        </h2>
        <div class="flex">
            <a href="{{ route('sanciones.index') }}"
                class="cursor-pointer text-white px-4 py-2 rounded flex items-center gap-2 hover:underline">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Volver
            </a>
        </div>
    </div>

    {{-- Buscador de jugador --}}
    <div
        class="grid grid-cols-1 md:grid-cols-5 gap-4 bg-gray-200 border border-gray-500 dark:bg-gray-700 p-4 rounded-lg shadow-md">
        <div>
            <label class="block m-2 dark:text-white">Buscar Jugador</label>
            <input type="text" wire:model.live="buscarJugador" wire:keydown.enter.prevent="buscarJugadorSancion"
                class="bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-500 dark:border-gray-600 dark:placeholder-gray-300 dark:text-white"
                placeholder="Ingrese Dni o Nombre y presione Enter">
        </div>
    </div>

    {{-- Resultados de búsqueda --}}
    @if($buscarJugador && count($jugadores) > 0)
    <div class="mb-4 bg-gray-500 border border-gray-300 text-gray-100 text-sm rounded-lg p-2.5">
        @foreach($jugadores as $jug)
        <div class="flex justify-between items-center border-b py-2">
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-3 shadow-sm hover:shadow-md transition">
                <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                    <span class="font-semibold text-gray-900 dark:text-gray-100 text-sm sm:text-base">
                        {{ strtoupper($jug->apellido) }}, {{ strtoupper($jug->nombre) }}
                    </span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        DNI: {{ $jug->documento }}
                    </span>
                </div>
                <div>
                    <span
                        class="inline-block bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 text-xs font-medium px-3 py-1 rounded-full mt-1 sm:mt-0">
                        {{ $jug->equiposPorCampeonato->first() ? strtoupper($jug->equiposPorCampeonato->first()->nombre)
                        : 'SIN EQUIPO' }}
                    </span>
                </div>
            </div>
            <button wire:click="agregarJugador({{ $jug->id }})"
                class="bg-blue-900 text-white hover:bg-blue-700 px-2 py-1 rounded cursor-pointer">
                <div class="flex p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-user-round-plus-icon lucide-user-round-plus">
                        <path d="M2 21a8 8 0 0 1 13.292-6" />
                        <circle cx="10" cy="8" r="5" />
                        <path d="M19 16v6" />
                        <path d="M22 19h-6" />
                    </svg>
                    <span class="hidden sm:flex ml-2">Agregar</span>
                </div>
            </button>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Jugador seleccionado --}}
    @if($jugadorSeleccionado)
    <div class="bg-blue-100 border border-blue-300 text-blue-900 rounded-lg p-4 mb-4">
        <strong>{{ $jugadorSeleccionado['apellido'] }}, {{ $jugadorSeleccionado['nombre'] }}</strong><br>
        DNI: {{ $jugadorSeleccionado['documento'] }}, Equipo:
        {{ $jugadorSeleccionado->equiposPorCampeonato->first() ?
        strtoupper($jugadorSeleccionado->equiposPorCampeonato->first()->nombre) : 'SIN EQUIPO' }}
    </div>
    @endif

    <hr class="m-5">

    {{-- Formulario de sanción --}}
    <div
        class="grid grid-cols-1 md:grid-cols-2 mt-4 gap-4 bg-gray-200 border border-gray-500 dark:bg-gray-700 p-4 rounded-lg shadow-md">

        @if($jugadorSeleccionado)
        <div class="">
            <label class="block m-2 dark:text-white">Seleccionar fecha o fase</label>
            <select wire:model="fechaBuscada" wire:change="cargarPartidosPorFecha"
                class="bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">-- Seleccionar --</option>
                @foreach($opcionesFechaFase as $opcion)
                <option value="{{ $opcion }}">{{ ucfirst($opcion) }}</option>
                @endforeach
            </select>
        </div>
        @if($partidoJugadorInfo)
        <div class="max-w-md text-sm text-blue-800 bg-blue-100 border border-blue-300 rounded p-2 flex items-center">
            <strong class="mr-2">Partido:</strong>
            <span class="truncate">{{ $partidoJugadorInfo }}</span>
        </div>
        @endif
        @endif
    </div>
    <div
        class="grid grid-cols-1 md:grid-cols-3 mt-4 gap-4 bg-gray-200 border border-gray-500 dark:bg-gray-700 p-4 rounded-lg shadow-md">
        <div>
            <label class="block m-2 dark:text-white">Sanción</label>
            <select wire:model="motivo"
                class="bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">Tipo</option>
                <option value="5 amarillas">5 amarillas</option>
                <option value="doble amarilla">Doble amarilla</option>
                <option value="roja directa">Roja directa</option>
            </select>
        </div>

        {{-- <div>
            <label class="block m-2 dark:text-white">Jornada de sanción</label>
            <input type="number" wire:model="fecha_sancion"
                class="bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                placeholder="Ej: 1, 2, 3 o fase" required />
        </div> --}}

        <div>
            <label class="block m-2 dark:text-white">Cantidad de fechas</label>
            <input type="number" wire:model="partidos_sancionados" min="1"
                class="bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                placeholder="1" required />
        </div>
    </div>

    {{-- Observación --}}
    <div class="mt-4">
        <label class="block m-2 dark:text-white">Observación</label>
        <textarea wire:model="observacion" rows="4"
            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            placeholder="Escriba aquí una observación..."></textarea>
    </div>

    {{-- Botones --}}
    <div class="flex gap-4 items-center mt-4 mb-15">
        <button wire:click="guardar"
            class="cursor-pointer inline-flex items-center gap-2 bg-blue-950 hover:bg-blue-800 text-white px-4 py-2 rounded">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-save">
                <path
                    d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
                <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7" />
                <path d="M7 3v4a1 1 0 0 0 1 1h7" />
            </svg>
            <span>Guardar</span>
        </button>



        <button wire:click="actualizarCumplimientosSanciones"
            class="md:hidden cursor-pointer inline-flex items-center gap-2 font-semibold bg-[#fce307] hover:bg-[#5a5207] hover:text-gray-300 text-black px-4 py-2 rounded">
            Actualizar Sanciones
        </button>
    </div>
</div>