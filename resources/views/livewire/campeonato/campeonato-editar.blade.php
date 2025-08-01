<div class="container mx-auto p-4">

    @if (session()->has('success'))
    <div class="p-2 mb-4 bg-green-100 border text-green-800 rounded">
        {{ session('success') }}
    </div>
    @endif

    <form wire:submit.prevent="editar" class="max-w-md mx-auto">
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
                <input type="number" wire:model.defer="puntos_victoria"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                @error('puntos_victoria')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="relative z-0 w-full mb-5 group">
                <label
                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Empate</label>
                <input type="number" wire:model.defer="puntos_empate"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                @error('puntos_empate')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="relative z-0 w-full mb-5 group">
                <label
                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Puntos
                    por derrota</label>
                <input type="number" wire:model.defer="puntos_derrota"
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


        <div class="relative z-0 w-full mb-5 group">
            <div class="block max-w-xl p-6 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                    Criterios de desempate (de mayor a menor)
                </h3>

                <ul class="space-y-2 mt-4">

                    @foreach ($criterios as $criterioItem)

                    <li class="flex justify-between items-center py-2.5 border-b border-gray-300 text-sm dark:border-gray-600">
                        <span class="capitalize text-gray-800 dark:text-white">
                            {{ $criterioItem->criterio }}

                        </span>
                        <div class="flex space-x-2">
                            <button type="button" wire:click="moveCriterioUp({{  $loop->index}})" class="text-blue-600 hover:underline">↑</button>
                            <button type="button" wire:click="moveCriterioDown({{  $loop->index}})" class="text-blue-600 hover:underline">↓</button>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

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

        <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Guardar</button>
        <a href="{{route('campeonato.index')}}" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Volver
        </a>
    </form>
</div>