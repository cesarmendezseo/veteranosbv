<div class="container mx-auto p-4">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Campeonato Crear') }}
        </h2>
        <div class="flex items-center space-x-4">
        <a href="{{route('campeonato.index')}}" class="inline-flex items-center gap-2 mt-4 hover:underline text-white px-4 py-2 rounded ">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <span>Volver</span>
        </a>
        </div>
     </x-slot>
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        @if (session()->has('success'))
        <div class="p-2 mb-4 bg-green-100 border text-green-800 rounded">
            {{ session('success') }}
        </div>
        @endif

        <form wire:submit.prevent="save" class="max-w-md mx-auto">
            <div class="relative z-0 w-full mb-5 group">
                <label
                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Nombre</label>
                <input type="text" wire:model.defer="nombre"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                @error('nombre')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="grid gap-6 mb-6 md:grid-cols-2">
                <div class="relative z-0 w-full group">
                    <label for="formato"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Formato</label>
                    <select id="formato" wire:model.live="formato"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">-- Selecciona una opción --</option>
                        <option value="todos_contra_todos">Todos contra todos</option>
                        <option value="grupos">Por grupos</option>
                    </select>
                    @error('formato')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                @if ($formato === 'todos_contra_todos')
                <div class="relative z-0 w-full group">
                    <label for="total_equipos"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cantidad total de
                        equipos</label>
                    <input type="number" id="total_equipos" wire:model.defer="total_equipos" min="2"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    @error('total_equipos')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                @endif

                @if ($formato === 'grupos')
                <div class="relative z-0 w-full group">
                    <label for="cantidad_grupos"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cantidad de grupos</label>
                    <input type="number" id="cantidad_grupos" wire:model.defer="cantidad_grupos" min="1"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    @error('cantidad_grupos')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="relative z-0 w-full group">
                    <label for="equipos_por_grupo"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Equipos por grupo</label>
                    <input type="number" id="equipos_por_grupo" wire:model.defer="equipos_por_grupo" min="1"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    @error('equipos_por_grupo')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                @endif
            </div>
            {{-- categoria --}}
            <div class="mb-6">
                <label for="categoria_id"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Categoría</label>
                <select id="categoria_id" wire:model="categoria_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="">-- Selecciona una categoría --</option>
                    @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                    @endforeach
                </select>
                @error('categoria_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- configuracion de puntos --}}
            <h1
                class="text-xl my-7 text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                Configurar puntajes</h1>

            <div class="grid gap-6 mb-6 md:grid-cols-3">

                <div class="relative z-0 w-full mb-5 group">
                    <label
                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Puntos
                        por victoria</label>
                    <input type="number" wire:model.defer="puntos_ganado"
                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                    @error('puntos_victoria')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="relative z-0 w-full mb-5 group">
                    <label
                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Empate</label>
                    <input type="number" wire:model.defer="puntos_empatado"
                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                    @error('puntos_empate')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="relative z-0 w-full mb-5 group">
                    <label
                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Puntos
                        por derrota</label>
                    <input type="number" wire:model.defer="puntos_perdido"
                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                    @error('puntos_derrota')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            {{-- fin configuracion de puntos --}}
            {{-- CONFIGURAR CRITERIO DESEMPATE --}}
            <h1
                class="text-xl my-7 text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                Configurar Criterio desempate</h1>


            <div class="relative z-0 w-full mb-5 group ">
                <div
                    class="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                    <h3 class="peer-focus:font-medium text-sm font-semibold text-gray-900 dark:text-white">
                        Criterios de desempate (de mayor a menor)
                    </h3>
                    <ul class="space-y-2 mt-4">
                        @foreach ($criterios as $index => $criterio)
                        <li
                            class="block w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer flex justify-between items-center py-2.5">
                            <span class="capitalize">{{ str_replace('_', ' ', $criterio) }}</span>
                            <div class="flex space-x-2">
                                <button type="button" wire:click="moveCriterioUp({{ $index }})"
                                    class="text-blue-600 hover:underline text-sm">↑</button>
                                <button type="button" wire:click="moveCriterioDown({{ $index }})"
                                    class="text-blue-600 hover:underline text-sm">↓</button>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            {{-- FIN CONFIGURAR CRITERIO DESEMPATE --}}

            {{-- CONFIGURAR FAIR PLAY --}}
            <h1
                class="text-xl my-7 text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                Configurar Fair Play</h1>

            <div class="grid gap-6 mb-6 md:grid-cols-2">
                <div class="mb-3  text-gray-900 md:text-4xl dark:text-white">
                    <label class="text-sm">Puntaje tarjeta amarilla</label>
                    <input type="number" wire:model.defer="puntos_tarjeta_amarilla"
                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2
                border-gray-300 appearance-none dark:text-white dark:border-gray-600
                dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                    @error('puntos_tarjeta_amarilla')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3  text-gray-900 md:text-4xl dark:text-white">
                    <label class="text-sm">Puntaje doble amarilla</label>
                    <input type="number" wire:model.defer="puntos_doble_amarilla"
                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2
                border-gray-300 appearance-none dark:text-white dark:border-gray-600
                dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                    @error('puntos_doble_amarilla')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3  text-gray-900 md:text-4xl dark:text-white">
                    <label class="text-sm">Puntaje tarjeta roja directa</label>
                    <input type="number" wire:model.defer="puntos_tarjeta_roja"
                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2
                border-gray-300 appearance-none dark:text-white dark:border-gray-600
                dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                    @error('puntos_tarjeta_roja')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <button type="submit" class="inline-flex items-center gap-2 mt-4  bg-blue-950 hover:bg-blue-800 text-white px-4 py-2 rounded ">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-save-icon lucide-save">
                    <path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
                    <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7" />
                    <path d="M7 3v4a1 1 0 0 0 1 1h7" />
                </svg> <span>Guardar</span></button>
            <a href="{{route('campeonato.index')}}" class="inline-flex items-center gap-2 mt-4  bg-blue-950 hover:bg-blue-800 text-white px-4 py-2 rounded ">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span>Volver</span>
            </a>
        </form>
    </div>
</div>