{{-- resources/views/livewire/configuracion-campeonato.blade.php --}}
<div class="space-y-6 p-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-4">Configuración del Campeonato: {{ $campeonato->nombre }}</h2>
        <p class="text-gray-600 mb-6">Formato: <span class="font-semibold">{{ ucwords(str_replace('_', ' ',
                $campeonato->formato)) }}</span></p>

        @if (session()->has('mensaje'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('mensaje') }}
        </div>
        @endif

        @foreach($fases as $indice => $fase)
        <div class="border border-gray-200 rounded-lg p-6 mb-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold">Fase {{ $indice + 1 }}: {{ $fase['nombre'] }}</h3>
                <span class="text-sm text-gray-500">Tipo: {{ ucwords(str_replace('_', ' ', $fase['tipo_fase']))
                    }}</span>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre de la Fase</label>
                <input type="text" wire:model="fases.{{ $indice }}.nombre"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error("fases.{$indice}.nombre")
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mt-4">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="checkbox" wire:model="fases.{{ $indice }}.tiene_liguilla"
                        wire:change="habilitarLiguilla({{ $indice }})"
                        class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="text-gray-700 font-medium">¿Habilitar liguilla al finalizar esta fase?</span>
                </label>
            </div>

            @if($fase['tiene_liguilla'])
            <div class="mt-6 bg-gray-50 p-6 rounded-lg border border-gray-200">
                <h4 class="font-semibold text-lg mb-4 text-gray-800">⚙️ Configuración de Liguilla</h4>

                @if($campeonato->formato === 'todos_contra_todos')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Equipos para Liguilla Superior
                        </label>
                        <input type="number" wire:model="fases.{{ $indice }}.config_liguilla.equipos_superiores" min="2"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Los mejores N equipos clasificarán</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Equipos para Liguilla Inferior
                        </label>
                        <input type="number" wire:model="fases.{{ $indice }}.config_liguilla.equipos_inferiores" min="0"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Los equipos restantes (0 para omitir)</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Formato Liguilla Superior
                        </label>
                        <select wire:model="fases.{{ $indice }}.config_liguilla.formato_superior"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                            <option value="eliminacion_simple">Eliminación Simple</option>
                            <option value="doble_eliminacion">Doble Eliminación</option>
                            <option value="todos_contra_todos">Todos contra Todos</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Formato Liguilla Inferior
                        </label>
                        <select wire:model="fases.{{ $indice }}.config_liguilla.formato_inferior"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                            <option value="eliminacion_simple">Eliminación Simple</option>
                            <option value="doble_eliminacion">Doble Eliminación</option>
                            <option value="todos_contra_todos">Todos contra Todos</option>
                        </select>
                    </div>
                </div>
                @elseif($campeonato->formato === 'por_grupos')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Criterio de Clasificación
                        </label>
                        <select wire:model="fases.{{ $indice }}.config_liguilla.criterio_clasificacion"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                            <option value="mejores_2_por_grupo">Los mejores 2 de cada grupo</option>
                            <option value="primeros_y_mejores_segundos">Primeros y mejores segundos</option>
                            <option value="mejor_3ero">Primeros, segundos y mejor tercero</option>
                            <option value="personalizado">Personalizado</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Total de Equipos que Clasifican
                        </label>
                        <input type="number" wire:model="fases.{{ $indice }}.config_liguilla.equipos_clasifican" min="2"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Formato de la Liguilla
                        </label>
                        <select wire:model="fases.{{ $indice }}.config_liguilla.formato_liguilla"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                            <option value="eliminacion_simple">Eliminación Simple</option>
                            <option value="doble_eliminacion">Doble Eliminación</option>
                            <option value="todos_contra_todos">Todos contra Todos</option>
                        </select>
                    </div>
                </div>
                @endif
            </div>
            @endif
        </div>
        @endforeach

        <div class="flex justify-end space-x-4 mt-6">
            <a href="{{ route('campeonato.index') }}"
                class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Cancelar
            </a>
            <button wire:click="guardarConfiguracion"
                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                💾 Guardar Configuración
            </button>
        </div>
    </div>
</div>