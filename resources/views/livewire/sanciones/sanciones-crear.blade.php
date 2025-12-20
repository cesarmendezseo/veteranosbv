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
        class="grid grid-cols-1 md:grid-cols-5 gap-4 bg-gray-200 border border-gray-500 dark:bg-gray-700 p-4 rounded-lg shadow-md mt-4">
        <div class="md:col-span-2">
            <label class="block m-2 dark:text-white font-bold">Buscar Jugador</label>
            <input type="text" wire:model.live="buscarJugador" wire:keydown.enter.prevent="buscarJugadorSancion"
                class="bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-500 dark:border-gray-600 dark:placeholder-gray-300 dark:text-white"
                placeholder="Ingrese Dni o Nombre y presione Enter">
        </div>
    </div>

    {{-- Resultados de búsqueda --}}
    @if($buscarJugador && count($jugadores) > 0)
    <div class="mb-4 bg-gray-500 border border-gray-300 text-gray-100 text-sm rounded-lg p-2.5 mt-2">
        @foreach($jugadores as $jug)
        <div class="flex justify-between items-center border-b border-gray-400 py-2">
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-3 shadow-sm flex-grow mr-4">
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
                class=" bg-blue-900 text-white hover:bg-blue-700 px-4 py-2 rounded cursor-pointer transition">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 21a8 8 0 0 1 13.292-6" />
                        <circle cx="10" cy="8" r="5" />
                        <path d="M19 16v6" />
                        <path d="M22 19h-6" />
                    </svg>
                    <span class="hidden sm:flex ml-2 font-bold text-xs uppercase">Elegir</span>
                </div>
            </button>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Jugador seleccionado --}}
    @if($jugadorSeleccionado)
    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-900 rounded-lg p-4 mb-4 mt-4 shadow">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-xs uppercase font-bold text-blue-600">Jugador a Sancionar:</p>
                <h3 class="text-lg font-bold">{{ strtoupper($jugadorSeleccionado['apellido']) }}, {{
                    strtoupper($jugadorSeleccionado['nombre']) }}</h3>
                <p class="text-sm">DNI: {{ $jugadorSeleccionado['documento'] }} | Equipo:
                    <span class="font-bold">{{ $jugadorSeleccionado->equiposPorCampeonato->first() ?
                        strtoupper($jugadorSeleccionado->equiposPorCampeonato->first()->nombre) : 'SIN EQUIPO' }}</span>
                </p>
            </div>
            <button wire:click="$set('jugadorSeleccionado', null)"
                class="cursor-pointer text-red-500 hover:text-red-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
    @endif

    <hr class="my-6 border-gray-400">

    {{-- Formulario de sanción --}}
    <div
        class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-200 border border-gray-500 dark:bg-gray-700 p-6 rounded-lg shadow-lg">

        {{-- Bloque Izquierdo: Origen y Motivo --}}
        <div class="space-y-4">
            <div>
                <label class="block mb-1 font-bold dark:text-white">1. Origen (Jornada/Fase)</label>
                <select wire:model.live="fechaBuscada"
                    class="cursor-pointer bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-800 dark:text-white">
                    <option value="">-- Seleccionar donde ocurrió la falta --</option>
                    @foreach($opcionesFechaFase as $opcion)
                    <option value="{{ $opcion }}">{{ ucfirst($opcion) }}</option>
                    @endforeach
                </select>
                @if($partidoJugadorInfo)
                <div class="mt-2 text-xs font-bold text-blue-700 bg-blue-200 p-2 rounded border border-blue-400">
                    PARTIDO: {{ $partidoJugadorInfo }}
                </div>
                @endif
            </div>

            <div>
                <label class="block mb-1 font-bold dark:text-white">2. Tipo de Falta (Motivo)</label>
                <select wire:model="motivo"
                    class="cursor-pointer bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-800 dark:text-white">
                    <option value="">-- Seleccionar Motivo --</option>
                    <option value="5 amarillas">Acumulación de 5 amarillas</option>
                    <option value="doble amarilla">Doble amarilla</option>
                    <option value="roja directa">Roja directa</option>
                    <option value="agresion">Agresión Física / Verbal</option>
                    <option value="otros">Otros motivos</option>
                </select>
            </div>
        </div>

        {{-- Bloque Derecho: La Sanción --}}
        <div class="space-y-4 bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-400">
            <div>
                <label class="block mb-1 font-bold dark:text-blue-400 italic">3. Definir Pena</label>
                <select wire:model.live="tipo_medida"
                    class="cursor-pointer w-full rounded-lg border-1 border-gray-500 text-sm p-2.5 mb-4">
                    <option value="partidos">Sanción por Fechas de Juego</option>
                    <option value="tiempo">Sanción por Tiempo Cronológico</option>
                </select>
            </div>

            @if($tipo_medida === 'partidos')
            <div>
                <label class="block mb-1 text-sm font-semibold dark:text-white">Cantidad de fechas a cumplir:</label>
                <input type="number" wire:model="partidos_sancionados" min="1"
                    class="bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:text-white">
            </div>
            @else
            <div>
                <label class="block mb-1 text-sm font-semibold dark:text-white">Inhabilitado hasta el día:</label>
                <input type="date" wire:model="fecha_fin"
                    class="bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:text-white">

                <div class="grid grid-cols-2 gap-2 mt-3">
                    <button type="button" wire:click="setTiempo(1, 'year')"
                        class="cursor-pointer text-xs bg-gray-600 text-white p-2 rounded hover:bg-black"> +1 Año
                    </button>
                    <button type="button" wire:click="setTiempo(2, 'year')"
                        class="cursor-pointer text-xs bg-gray-600 text-white p-2 rounded hover:bg-black"> +2 Años
                    </button>
                    <button type="button" wire:click="setTiempo(6, 'month')"
                        class="cursor-pointer text-xs bg-gray-600 text-white p-2 rounded hover:bg-black"> +6 Meses
                    </button>
                    <button type="button" wire:click="setTiempo(3, 'month')"
                        class="cursor-pointer text-xs bg-gray-600 text-white p-2 rounded hover:bg-black"> +3 Meses
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Observación --}}
    <div class="mt-4">
        <label class="block m-2 dark:text-white font-bold">4. Resolución del Tribunal (Observaciones)</label>
        <textarea wire:model="observacion" rows="3"
            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            placeholder="Detalle los motivos del fallo aquí..."></textarea>
    </div>

    {{-- Botones de Acción --}}
    <div class="flex flex-col md:flex-row gap-4 items-center mt-6 mb-20">
        <button wire:click="guardar"
            class=" w-full md:w-auto cursor-pointer inline-flex items-center justify-center gap-2 bg-blue-950 hover:bg-blue-800 text-white font-bold px-8 py-3 rounded-lg shadow-lg transition transform hover:scale-105">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-save">
                <path
                    d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
                <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7" />
                <path d="M7 3v4a1 1 0 0 0 1 1h7" />
            </svg>
            <span>GUARDAR SANCIÓN DEFINITIVA</span>
        </button>

        <button wire:click="actualizarCumplimientosSanciones"
            class="w-full md:w-auto cursor-pointer inline-flex items-center justify-center gap-2 font-bold bg-yellow-400 hover:bg-yellow-500 text-black px-6 py-3 rounded-lg shadow transition">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8" />
                <path d="M21 3v5h-5" />
                <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16" />
                <path d="M3 21v-5h5" />
            </svg>
            Actualizar Estados
        </button>
    </div>
</div>