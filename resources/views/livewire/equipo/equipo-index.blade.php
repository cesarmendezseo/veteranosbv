<div>
   <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Equipos') }}
        </h2>
        <div class="flex items-center space-x-4">
     
        @adminOrCan('comision')
                    <a href="{{ route('equipo.crear') }}"
                        class=" text-white px-4 py-2 rounded flex items-center gap-2 hover:underline"> <svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-badge-plus-icon lucide-badge-plus">
                            <path
                                d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z" />
                            <line x1="12" x2="12" y1="8" y2="16" />
                            <line x1="8" x2="16" y1="12" y2="12" />
                        </svg>
                        Crear</a>
                @endadminOrCan
        </div>
    </x-slot>
   
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-100 uppercase bg-gray-500 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Logo
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Nombre
                    </th>
                    <th scope="col" class="px-6 py-3 hidden sm:table-cell">
                        Ciudad
                    </th>
                    <th scope="col" class="px-6 py-3 hidden sm:table-cell">
                        Provincia
                    </th>
                    <th scope="col" class="px-6 py-3 hidden sm:table-cell">
                        Cod. Pos.
                    </th>

                    <th scope="col" class="px-6 py-3 hidden sm:table-cell">
                        Descripción
                    </th>

                    <th scope="col" class="px-6 py-3">
                        Acción
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($equipos as $equipo)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            @if ($equipo->logo)
                                <div class="mb-4 flex justify-center">
                                    <div
                                        class="w-14 h-14 rounded-full border-4 border-gray-300 shadow-lg overflow-hidden">
                                        <img src="{{ asset('storage/' . $equipo->logo) }}"
                                            alt="Logo {{ $equipo->nombre }}" class="w-full h-full object-cover" />
                                    </div>
                                </div>
                            @else
                                <div
                                    class="w-14 h-14 rounded-full border-4 border-gray-300 shadow-lg overflow-hidden flex items-center justify-center">
                                    <div
                                        class="flex animate-pulse  items-center justify-center w-full h-48 bg-gray-300 rounded-sm sm:w-96 dark:bg-gray-700">
                                        <svg class="w-10 h-10 text-gray-200 dark:text-gray-600" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                                            <path
                                                d="M18 0H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm-5.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm4.376 10.481A1 1 0 0 1 16 15H4a1 1 0 0 1-.895-1.447l3.5-7A1 1 0 0 1 7.468 6a.965.965 0 0 1 .9.5l2.775 4.757 1.546-1.887a1 1 0 0 1 1.618.1l2.541 4a1 1 0 0 1 .028 1.011Z" />
                                        </svg>
                                    </div>
                                </div>
                            @endif
                        </th>

                        <th scope=" row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ strtoupper($equipo->nombre) }}
                        </th>
                        <td class="px-6 py-4 hidden sm:table-cell">
                            {{ strToupper($equipo->ciudad) }}
                        </td>
                        <td class="px-6 py-4 hidden sm:table-cell">
                            {{ strToupper($equipo->provincia) }}
                        </td>
                        <td class="px-6 py-4 hidden sm:table-cell">
                            {{ strToupper($equipo->cod_pos) }}
                        </td>
                        <td class="px-6 py-4 hidden sm:table-cell">
                            {{ strToupper($equipo->descripcion) }}
                        </td>

                        <!-- BOTONES DE ACCION PARA PANTALLAS MOVIL -->
                        <td class="px-6 py-4 text-right">

                            <!-- Para móviles: Menú desplegable -->

                            <!------------------------------------------->
                            <div x-data="{ open: false, dropUp: false, alignRight: false }" class="relative inline-block text-left">
                                <!-- Botón -->
                                <button x-ref="trigger"
                                    @click="
                                            dropUp = false;
                                            alignRight = false;
                                            open = !open;
                                            $nextTick(() => {
                                                const menu = $refs.menu;
                                                const rect = menu.getBoundingClientRect();
                                                
                                                // Ajustar vertical
                                                if (rect.bottom > window.innerHeight) dropUp = true;

                                                // Ajustar horizontal
                                                const spaceRight = window.innerWidth - rect.left;
                                                if (spaceRight < rect.width + 8) alignRight = true;
                                            });
                                        "
                                    class="px-4 py-2  text-gray-800 dark:text-gray-100 rounded cursor-pointer">
                                    <!-- Icono tres puntos -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 " fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 6v.01M12 12v.01M12 18v.01" />
                                    </svg>
                                </button>

                                <!-- Menú -->
                                <div x-show="open" x-ref="menu" x-transition x-cloak
                                    :class="[
                                        dropUp ? 'bottom-full mb-2' : 'top-full mt-2',
                                        alignRight ? 'right-0 left-auto' : 'left-0'
                                    ]"
                                    class="absolute w-48 bg-white   dark:bg-gray-700 dark:text-gray-100 rounded shadow-lg z-50 overflow-auto"
                                    style="max-height: calc(100vh - 4rem);" @click.outside="open = false">
                                    <a href="{{ route('equipo.editar', $equipo->id) }}"
                                        class="block px-4 py-2 hover:bg-gray-100  hover:underline   font-medium rounded-lg text-sm px-2 text-center inline-flex items-center dark:focus:ring-[#3b5998]/55  mb-2"
                                        title="Editar">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg><span class="ml-1">Editar</span>
                                    </a>

                                    {{-- Borrar --}}
                                    <button wire:click="borrar({{ $equipo->id }})"
                                        class="block px-4 py-2 hover:bg-gray-100  hover:underline   font-medium rounded-lg text-sm px-2 text-center inline-flex items-center dark:focus:ring-[#3b5998]/55  mb-2"
                                        title="Borrar">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg><span class="ml-1">Borrar</span>
                                    </button>

                                    {{-- Logo --}}
                                    <a href="{{ route('equipo.logo.upload', $equipo->id) }}"
                                        class="block px-4 py-2 hover:bg-gray-100  hover:underline   font-medium rounded-lg text-sm px-2 text-center inline-flex items-center dark:focus:ring-[#3b5998]/55  mb-2"
                                        title="Subir logo">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-camera-icon lucide-camera h-6 w-6">
                                            <path
                                                d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z" />
                                            <circle cx="12" cy="13" r="3" />
                                        </svg><span class="ml-1">Logo</span>
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>