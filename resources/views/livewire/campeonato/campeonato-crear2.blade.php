<div class="container mx-auto p-4 max-w-4xl">
    <div class="bg-blue-900 text-white p-4 shadow-md rounded-t-lg flex justify-between items-center relative z-10">
        <div>
            <h2 class="font-bold text-xl text-gray-100 leading-tight">
                {{ __('Configuración de Nuevo Campeonato') }}
            </h2>
            <p class="text-xs text-blue-200">Define las reglas, el formato y la estructura de la competición</p>
        </div>
        <a href="{{route('campeonato.index')}}"
            class="inline-flex items-center gap-2 hover:bg-blue-800 text-white px-4 py-2 rounded-lg transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <span>Volver</span>
        </a>
        {{-- @if($campeonato->fase_regular_terminada && !$campeonato->liguilla_generada)
        <div class="p-4 bg-yellow-100 border-l-4 border-yellow-500">
            <p class="text-yellow-700 font-bold">La fase regular ha finalizado.</p>
            <button wire:click="generarFaseEliminatoria" class="bg-blue-600 text-white px-4 py-2 rounded">
                Generar Liguilla Superior e Inferior
            </button>
        </div>
        @endif --}}
    </div>

    <div
        class="bg-white dark:bg-gray-800 p-8 rounded-b-lg shadow-xl border-x border-b border-gray-200 dark:border-gray-700">
        <form wire:submit.prevent="save">

            <div class="mb-10">
                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-400 mb-4 flex items-center gap-2">
                    <span
                        class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">1</span>
                    Información General
                </h3>
                <div class="grid gap-6 md:grid-cols-2">
                    <div class="relative z-0 w-full group">
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Nombre del
                            Campeonato</label>
                        <input type="text" wire:model.defer="nombre" placeholder="Ej: Torneo Apertura 2024"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        @error('nombre') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="relative z-0 w-full group">
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Categoría</label>
                        <select wire:model="categoria_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">-- Selecciona una categoría --</option>
                            @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                            @endforeach
                        </select>
                        @error('categoria_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div
                class="mb-10 p-6 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-400 mb-4 flex items-center gap-2">
                    <span
                        class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">2</span>
                    Formato de Competición
                </h3>

                <div class="grid gap-6 md:grid-cols-2 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de
                            Torneo</label>
                        <select wire:model.live="formato"
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white font-bold">
                            <option value="">-- Selecciona Formato --</option>
                            <option value="todos_contra_todos">Todos contra todos (Liga)</option>
                            <option value="grupos">Fase de Grupos + Eliminatorias</option>
                            <option value="eliminacion_simple">Eliminación Directa (Playoffs)</option>
                        </select>
                        @error('formato') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    @if ($formato === 'grupos')
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Nº
                                Grupos</label>
                            <input type="number" wire:model.defer="cantidad_grupos" min="1"
                                class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600">
                        </div>
                        <div>
                            <label
                                class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Equipos/Grupo</label>
                            <input type="number" wire:model.defer="equipos_por_grupo" min="1"
                                class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600">
                        </div>
                    </div>
                    @elseif($formato)
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Total Equipos
                            Participantes</label>
                        <input type="number" wire:model.defer="total_equipos" min="2"
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600">
                    </div>
                    @endif
                </div>

                @if ($formato === 'grupos')
                <div
                    class="flex items-center p-4 bg-blue-100/50 border border-blue-200 rounded-lg dark:bg-blue-900/20 dark:border-blue-800">
                    <input id="intergrupos" type="checkbox" wire:model.live="intergrupos_para_libres"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                    <label for="intergrupos" class="ms-2 text-sm font-medium text-blue-900 dark:text-blue-300">
                        <strong>Habilitar partidos intergrupos:</strong> Los equipos que queden libres en su grupo
                        jugarán entre sí para sumar puntos.
                    </label>
                </div>
                @endif
            </div>

            @if (in_array($formato, ['todos_contra_todos', 'grupos']))
            <div class="mb-10">
                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-400 mb-4 flex items-center gap-2">
                    <span
                        class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">3</span>
                    Sistema de Puntuación
                </h3>

                <div class="grid gap-6 md:grid-cols-3 mb-8">
                    <div
                        class="p-4 bg-green-50 dark:bg-green-900/10 rounded-lg border border-green-100 dark:border-green-900">
                        <label class="block mb-1 text-xs font-bold text-green-700 uppercase">Victoria</label>
                        <input type="number" wire:model.defer="puntos_ganado"
                            class="bg-transparent border-b-2 border-green-300 focus:border-green-500 border-x-0 border-t-0 w-full text-xl font-bold text-green-900 dark:text-green-400 outline-none">
                    </div>
                    <div
                        class="p-4 bg-yellow-50 dark:bg-yellow-900/10 rounded-lg border border-yellow-100 dark:border-yellow-900">
                        <label class="block mb-1 text-xs font-bold text-yellow-700 uppercase">Empate</label>
                        <input type="number" wire:model.defer="puntos_empatado"
                            class="bg-transparent border-b-2 border-yellow-300 focus:border-yellow-500 border-x-0 border-t-0 w-full text-xl font-bold text-yellow-900 dark:text-yellow-400 outline-none">
                    </div>
                    <div class="p-4 bg-red-50 dark:bg-red-900/10 rounded-lg border border-red-100 dark:border-red-900">
                        <label class="block mb-1 text-xs font-bold text-red-700 uppercase">Derrota</label>
                        <input type="number" wire:model.defer="puntos_perdido"
                            class="bg-transparent border-b-2 border-red-300 focus:border-red-500 border-x-0 border-t-0 w-full text-xl font-bold text-red-900 dark:text-red-400 outline-none">
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-8 items-start">
                    <div
                        class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-700 dark:border-gray-600">
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-4 border-b pb-2 italic">Prioridad
                            de Desempate</h4>
                        <ul class="space-y-3">
                            @foreach ($criterios as $index => $criterio)
                            <li
                                class="flex justify-between items-center p-2 bg-gray-50 dark:bg-gray-800 rounded border border-gray-100 dark:border-gray-700">
                                <span class="text-sm font-medium dark:text-gray-300">
                                    <span class="text-blue-600 font-bold mr-2">{{ $index + 1 }}.</span> {{
                                    ucfirst(str_replace('_', ' ', $criterio)) }}
                                </span>
                                <div class="flex gap-1">
                                    <button type="button" wire:click="moveCriterioUp({{ $index }})"
                                        class="p-1 hover:bg-blue-200 rounded text-blue-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7" />
                                        </svg>
                                    </button>
                                    <button type="button" wire:click="moveCriterioDown({{ $index }})"
                                        class="p-1 hover:bg-blue-200 rounded text-blue-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <div
                        class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-700 dark:border-gray-600">
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-4 border-b pb-2 italic">
                            Penalización Fair Play</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase">Tarjeta Amarilla</label>
                                <input type="number" wire:model.defer="puntos_tarjeta_amarilla"
                                    class="block w-full text-sm border-gray-300 rounded-md dark:bg-gray-800">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase">Doble Amarilla</label>
                                <input type="number" wire:model.defer="puntos_doble_amarilla"
                                    class="block w-full text-sm border-gray-300 rounded-md dark:bg-gray-800">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase">Roja Directa</label>
                                <input type="number" wire:model.defer="puntos_tarjeta_roja"
                                    class="block w-full text-sm border-gray-300 rounded-md dark:bg-gray-800">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if (in_array($formato, ['todos_contra_todos', 'grupos']))
            <div
                class="mb-10 p-6 bg-blue-50 dark:bg-gray-900/30 border-2 border-dashed border-blue-200 dark:border-blue-800 rounded-xl">
                <div class="flex items-center gap-3 mb-6">
                    <input id="tiene_liguilla" type="checkbox" wire:model.live="tiene_liguilla"
                        class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                    <label for="tiene_liguilla"
                        class="text-lg font-bold text-blue-900 dark:text-blue-400 cursor-pointer">
                        ¿Habilitar Fase Eliminatoria (Liguilla)?
                    </label>
                </div>

                @if ($tiene_liguilla)
                <div class="grid gap-6 md:grid-cols-2 animate-fade-in-down">
                    @if ($formato === 'todos_contra_todos')
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Clasificados Fase
                            Superior</label>
                        <input type="number" wire:model.defer="config_liguilla.equipos_superiores"
                            class="bg-white border border-gray-300 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Clasificados Fase
                            Inferior (Opcional)</label>
                        <input type="number" wire:model.defer="config_liguilla.equipos_inferiores" placeholder="0"
                            class="bg-white border border-gray-300 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700">
                    </div>
                    @endif

                    @if ($formato === 'grupos')
                    <div class="md:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Criterio de
                            Clasificación</label>
                        <select wire:model.live="config_liguilla.criterio_clasificacion"
                            class="bg-white border border-gray-300 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700">
                            <option value="">-- Selecciona --</option>
                            <option value="primero_cada_grupo">Solo el 1ro de cada grupo</option>
                            <option value="mejores_por_grupo">Los N mejores de cada grupo</option>
                            <option value="mejores_terceros">1ros, 2dos y mejores 3ros</option>
                        </select>
                    </div>
                    @endif

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Formato
                            Eliminatorias</label>
                        <select wire:model.defer="config_liguilla.formato_superior"
                            class="bg-white border border-gray-300 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700">
                            <option value="eliminacion_simple">Eliminación Simple (IDA)</option>
                            <option value="eliminacion_doble">Eliminación Simple (IDA y VUELTA)</option>
                            <option value="doble_eliminacion">Doble Eliminación (Winner/Loser Brackets)</option>
                        </select>
                    </div>
                </div>
                @endif
            </div>
            @endif

            <div class="mb-10">
                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-400 mb-4 flex items-center gap-2">
                    <span
                        class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300">5</span>
                    Generación de Calendario (Fixture)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label
                        class="flex items-center p-4 border rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <input type="radio" value="automatico" wire:model="tipo_fixture" name="tipo_fixture"
                            class="w-4 h-4 text-blue-600">
                        <div class="ml-4">
                            <span class="block font-bold text-gray-900 dark:text-white">Automático (Round Robin)</span>
                            <span class="text-xs text-gray-500">El sistema emparejará a los equipos por fecha
                                automáticamente.</span>
                        </div>
                    </label>
                    <label
                        class="flex items-center p-4 border rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <input type="radio" value="manual" wire:model="tipo_fixture" name="tipo_fixture"
                            class="w-4 h-4 text-blue-600">
                        <div class="ml-4">
                            <span class="block font-bold text-gray-900 dark:text-white">Carga Manual</span>
                            <span class="text-xs text-gray-500">Tú mismo crearás los partidos y horarios uno por
                                uno.</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="border-t pt-8">
                <div class="flex flex-col md:flex-row gap-4 justify-between items-center">
                    <div
                        class="text-sm text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600">
                        <span class="font-bold">Resumen:</span> {{ ucfirst(str_replace('_', ' ', $formato)) }}
                        @if($formato === 'grupos') ({{ $cantidad_grupos }} grupos) @endif
                        | Fixture: {{ ucfirst($tipo_fixture) }}
                    </div>

                    <div class="flex gap-4">
                        <a href="{{route('campeonato.index')}}"
                            class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-100">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="cursor-pointer inline-flex items-center gap-2 px-8 py-2.5 text-sm font-bold text-white bg-blue-900 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 transition shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            GUARDAR CAMPEONATO
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>