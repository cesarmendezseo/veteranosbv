<div class="container mx-auto p-4 max-w-4xl">
    {{-- Encabezado --}}
    <div class="bg-blue-900 text-white p-4 shadow-md rounded-t-lg flex justify-between items-center relative z-10">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Editar Campeonato') }}
        </h2>

        <a href="{{ route('campeonato.index') }}"
            class="inline-flex items-center gap-2 hover:bg-blue-800 px-3 py-1 rounded transition-colors text-white">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <span>Volver</span>
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 p-6 shadow-md rounded-b-lg border border-gray-200 dark:border-gray-700">
        {{-- Mensaje de éxito --}}
        @if (session()->has('success'))
        <div class="p-3 mb-6 bg-green-100 border border-green-400 text-green-800 rounded flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </div>
        @endif

        <form wire:submit.prevent="editar">
            {{-- Sección Básica --}}
            <div class="grid gap-6 mb-6 md:grid-cols-2">
                <div class="col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Nombre del
                        Campeonato</label>
                    <input type="text" wire:model.defer="nombre"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    @error('nombre') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="formato"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Formato</label>
                    <select id="formato" wire:model.live="formato"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">-- Selecciona una opción --</option>
                        <option value="todos_contra_todos">Todos contra todos</option>
                        <option value="grupos">Por grupos</option>
                        <option value="eliminacion_simple">Eliminación Simple</option>
                        <option value="eliminacion_doble">Doble Eliminación</option>
                    </select>
                    @error('formato') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="categoria_id"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Categoría</label>
                    <select id="categoria_id" wire:model="categoria_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">-- Selecciona una categoría --</option>
                        @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                        @endforeach
                    </select>
                    @error('categoria_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Parámetros dinámicos según formato --}}
            <div class="grid gap-6 mb-6 md:grid-cols-2">
                @if ($formato === 'todos_contra_todos' || $formato === 'eliminacion_simple' || $formato ===
                'eliminacion_doble')
                <div class="relative z-0 w-full group">
                    <label for="total_equipos" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Cantidad total de equipos
                    </label>
                    <input type="number" id="total_equipos" wire:model.defer="total_equipos" {{-- Asegúrate que coincida
                        con la propiedad de la clase --}} min="2"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                    @error('total_equipos')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                @endif

                @if ($formato === 'grupos')
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cantidad de
                        grupos</label>
                    <input type="number" wire:model.defer="cantidad_grupos" min="1"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                    @error('cantidad_grupos') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Equipos por
                        grupo</label>
                    <input type="number" wire:model.defer="equipos_por_grupo" min="1"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                    @error('equipos_por_grupo') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                @endif
            </div>

            <hr class="my-8 border-gray-200 dark:border-gray-700">

            {{-- Configuración de puntos (Ligas y Grupos) --}}
            @if (in_array($formato, ['todos_contra_todos', 'grupos']))
            <h3 class="text-lg font-bold mb-4 text-blue-900 dark:text-blue-400 uppercase tracking-wider">Configurar
                Puntajes</h3>
            <div class="grid gap-6 mb-8 md:grid-cols-3">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Victoria</label>
                    <input type="number" wire:model.defer="puntos_victoria"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:text-white">
                    @error('puntos_victoria') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Empate</label>
                    <input type="number" wire:model.defer="puntos_empate"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:text-white">
                    @error('puntos_empate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Derrota</label>
                    <input type="number" wire:model.defer="puntos_derrota"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:text-white">
                    @error('puntos_derrota') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <h3 class="text-lg font-bold mb-4 text-blue-900 dark:text-blue-400 uppercase tracking-wider">Criterios de
                Desempate</h3>
            <div class="mb-8 max-w-xl">
                <div class="bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <p class="text-xs text-gray-500 mb-4">* Ordena la prioridad de arriba hacia abajo</p>
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($criterios as $index => $criterioItem)
                        <li
                            class="flex justify-between items-center py-2.5 border-b border-gray-300 text-sm dark:border-gray-600">
                            <span class="capitalize text-gray-800 dark:text-white">
                                {{-- CAMBIO AQUÍ: Usar corchetes en lugar de flecha --}}
                                {{ str_replace('_', ' ', $criterioItem['criterio']) }}
                            </span>

                            <div class="flex space-x-2">
                                {{-- Usamos el $index del foreach para las funciones --}}
                                <button type="button" wire:click="moveCriterioUp({{ $index }})"
                                    class="text-blue-600 hover:underline disabled:opacity-30" @if($loop->first) disabled
                                    @endif>↑</button>

                                <button type="button" wire:click="moveCriterioDown({{ $index }})"
                                    class="text-blue-600 hover:underline disabled:opacity-30" @if($loop->last) disabled
                                    @endif>↓</button>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <h3 class="text-lg font-bold mb-4 text-blue-900 dark:text-blue-400 uppercase tracking-wider">Fair Play
                (Puntos de Penalización)</h3>
            <div class="grid gap-6 mb-8 md:grid-cols-3">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Tarjeta
                        Amarilla</label>
                    <input type="number" wire:model.defer="puntos_tarjeta_amarilla"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:text-white">
                    @error('puntos_tarjeta_amarilla') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Doble
                        Amarilla</label>
                    <input type="number" wire:model.defer="puntos_doble_amarilla"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:text-white">
                    @error('puntos_doble_amarilla') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Roja Directa</label>
                    <input type="number" wire:model.defer="puntos_tarjeta_roja"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:text-white">
                    @error('puntos_tarjeta_roja') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Botones de Acción --}}
            <div class="flex flex-wrap gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-blue-900 hover:bg-blue-800 text-white px-6 py-2.5 rounded-lg font-medium transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                        <polyline points="17 21 17 13 7 13 7 21" />
                        <polyline points="7 3 7 8 15 8" />
                    </svg>
                    <span>Guardar Cambios</span>
                </button>

                <a href="{{ route('campeonato.index') }}"
                    class="inline-flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2.5 rounded-lg font-medium transition-all">
                    <span>Cancelar</span>
                </a>
            </div>
        </form>
    </div>

    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Livewire.on('mostrar-error', ({ mensaje }) => {
                Swal.fire({
                    icon: 'error',
                    title: '¡Ups!',
                    text: mensaje,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                });
            });
        });
    </script>
    @endpush
</div>