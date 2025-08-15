 <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
     <x-navbar titulo="Campeonatos">
         <button wire:click="crear"
             class="px-3 py-2  text-white rounded flex items-center gap-1 cursor-pointer">
             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-badge-plus-icon lucide-badge-plus">
                 <path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z" />
                 <line x1="12" x2="12" y1="8" y2="16" />
                 <line x1="8" x2="16" y1="12" y2="12" />
             </svg> Crear
         </button>
         <div class="mt-2">
             <input
                 type="text"
                 wire:model.live="search"

                 placeholder="Buscar por nombre del campeonato o por año"
                 class="hidden md:table border rounded px-3 py-2.5 w-full mb-2" />
         </div>
     </x-navbar>

     <flux:separator class="mb-2" />
     <table class="hidden md:table w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
         <thead class="text-xs text-gray-100 uppercase bg-gray-500 dark:bg-gray-700 dark:text-gray-400">
             <tr>
                 <th scope="col" class="px-6 py-3">
                     Nombre
                 </th>
                 <th scope="col" class="px-6 py-3">
                     Formato
                 </th>
                 <th scope="col" class="px-6 py-3 hidden sm:table-cell">
                     Cant. Equipos
                 </th>
                 <th scope="col" class="px-6 py-3">
                     Categoria
                 </th>
                 <th scope="col" class="px-6 py-3">
                     Acciones
                 </th>
             </tr>
         </thead>
         <tbody>
             @foreach ($campeonatos as $campeonato)
             <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">

                 <th scope=" row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                     <div class="text-base font-semibold">{{ucwords($campeonato->nombre)}}</div>
                 </th>
                 <th scope=" row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                     <div class="font-normal ">
                         @if($campeonato->formato === 'todos_contra_todos')
                         Todos contra Todos
                         @else
                         {{ucfirst($campeonato->formato)}}
                     </div>
                     @endif
                 </th>
                 <th scope=" row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                     @if($campeonato->formato === 'todos_contra_todos')

                     @foreach ($campeonato->grupos as $grupo)
                     <div class="font-normal "> {{ $grupo->cantidad_equipos}} </div>
                     @endforeach
                     @else
                     <div class="font-normal "> {{$campeonato->cantidad_equipos_grupo}} x Grupo</div>
                     @endif

                 </th>

                 <th scope=" row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                     <div class="font-normal text-gray-500"> {{ucfirst($campeonato->categoria->nombre)}}</div>
                 </th>


                 <!-- BOTONES  -->
                 <td class="px-6 py-4 text-right">
                     <!-- Para pantallas medianas en adelante -->
                     <div class="hidden md:flex gap-2 justify-end">
                         {{-- Editar --}}
                         <a href="{{ route('campeonato.editar', $campeonato->id) }}"
                             class="text-white bg-gradient-to-r from-teal-400 via-teal-500 to-teal-600 hover:bg-gradient-to-br
                            focus:ring-4 focus:outline-none focus:ring-teal-300 dark:focus:ring-teal-800
                            shadow-lg shadow-teal-500/50 dark:shadow-lg dark:shadow-teal-800/80
                            font-medium rounded-full text-sm
                            h-10 w-10 flex items-center justify-center"
                             title="Editar">
                             <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                 <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                             </svg>
                         </a>

                         {{-- BORRAR --}}
                         <button wire:click="$dispatch('confirmar-baja', { id: '{{ $campeonato->id }}' })"
                             class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br
                                focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800
                                shadow-lg shadow-red-500/50 dark:shadow-lg dark:shadow-red-800/80
                                font-medium rounded-full text-sm
                                h-10 w-10 flex items-center justify-center cursor-pointer"
                             title="Borrar">
                             <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                 <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                             </svg>
                         </button>
                         {{-- ver --}}
                         <button wire:click="verCampeonato({{ $campeonato->id }})"
                             class="text-white bg-gradient-to-r from-[#efb810] via-[#d4a105] to-[#8f6c03] hover:bg-gradient-to-br
                                focus:ring-4 focus:outline-none focus:ring-[#d8c897] dark:focus:ring-[#efb810]
                                shadow-lg shadow-[#efb71096] dark:shadow-lg dark:shadow-[#efb71070]
                                font-medium rounded-full text-sm h-10 w-10 flex items-center justify-center cursor-pointer"
                             title="Ver">
                             <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor" class="size-6">
                                 <path stroke-linecap="round" stroke-linejoin="round"
                                     d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                 <path stroke-linecap="round" stroke-linejoin="round"
                                     d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                             </svg>
                         </button>
                         {{-- ASIGNAR EQUIPO --}}
                         <a href="{{ route('asignar-equipos', $campeonato->id) }}"
                             class="mb-1 text-white bg-gradient-to-r from-teal-400 via-teal-500 to-teal-600 hover:bg-gradient-to-br
                            focus:ring-4 focus:outline-none focus:ring-teal-300 dark:focus:ring-teal-800
                            shadow-lg shadow-teal-500/50 dark:shadow-lg dark:shadow-teal-800/80
                            font-medium rounded-full text-sm
                            h-10 w-10 flex items-center justify-center"
                             title="Equipos">
                             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shirt-icon lucide-shirt">
                                 <path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z" />
                             </svg>

                         </a>



                     </div>

                 </td>
             </tr>
             @endforeach
         </tbody>
     </table>

     <!-- Para móvil: tarjetas -->
     <div class="md:hidden space-y-4 min-h-screen">
         <div class="mt-2 ">
             <input
                 type="text"
                 wire:model.live="search"

                 placeholder="Buscar por nombre del campeonato o por año"
                 class="border border-gray-500 rounded px-3 py-2.5 w-full mb-2" />
         </div>
         @foreach($campeonatos as $campeonato)
         <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 border dark:border-gray-700">
             <div class="flex justify-between items-center mb-2">
                 <h3 class="text-lg font-bold text-gray-900 dark:text-white"><span class="text-gray-600 dark:text-accent">Torneo:</span> {{ ucwords($campeonato->nombre) }}</h3>
                 <div x-data="{ open: false }" class="relative">
                     <button @click="open = !open" class="text-gray-600 dark:text-gray-300 focus:outline-none">
                         <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                             <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v.01M12 12v.01M12 18v.01" />
                         </svg>
                     </button>
                     <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 bg-white dark:bg-gray-700 shadow rounded-lg z-50 flex flex-col">
                         <a href="{{ route('campeonato.editar', $campeonato->id) }}" class="px-3 py-2 text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-600">Editar</a>
                         <a wire:click="$dispatch('confirmar-baja', { id: {{ $campeonato->id }} })" class="cursor-pointer px-3 py-2 text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-600">Borrar</a>
                         <a wire:click="verCampeonato({{ $campeonato->id }})" class="cursor-pointer px-3 py-2 text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-600">Ver</a>
                         <a href="{{ route('asignar-equipos', $campeonato->id) }}" class="px-3 py-2 text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-600">Asignar Equipo</a>
                     </div>
                 </div>
             </div>
             <div class="text-gray-700 dark:text-gray-300">
                 <p><span class="font-semibold">Formato:</span>
                     @if($campeonato->formato === 'todos_contra_todos')
                     Todos contra Todos
                     @else
                     {{ ucfirst($campeonato->formato) }}
                     @endif
                 </p>
                 <p><span class="font-semibold">Categoría:</span> {{ ucfirst($campeonato->categoria->nombre) }}</p>
             </div>
         </div>
         @endforeach
     </div>
     {{-- Modal --}}
     <style>
         [x-cloak] {
             display: none !important;
         }
     </style>
     <div x-data="{ show: false }"
         x-show="show"
         x-on:static-modal.window="show = true"
         x-cloak
         class="shadow-2xs fixed inset-0 z-50 flex items-center justify-center bg-[rgba(0,0,0,0.5)] p-4 sm:p-6">

         <div class="bg-gradient-custom dark:bg-gray-800 rounded-xl shadow-2xl max-w-xl w-full p-6 sm:p-8 relative transform transition-all scale-100 opacity-100 ease-out duration-300
                max-h-[90vh] overflow-y-auto"> <!-- Clases añadidas aquí -->
             <!-- Botón de Cierre -->
             <button @click="show = false"
                 class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-200 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full p-1">
                 <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                 </svg>
             </button>

             <h2 class="text-2xl font-extrabold mb-6 text-white dark:text-gray-100 text-center border-b pb-3 border-gray-200 dark:border-gray-700">
                 Detalles del Campeonato
             </h2>

             @if($campeonatoSeleccionado)
             <div class="grid grid-cols-1 md:grid-cols-1 gap-y-4 gap-x-6 text-gray-700 dark:text-[#efb810]">

                 <div class="grid grid-cols-1 md:grid-cols-1 gap-y-4 gap-x-6 text-[#efb810] font-semibold dark:text-gray-300">

                     <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                         <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Nombre Campeonato:</strong> {{ ucwords(strtolower($campeonatoSeleccionado->nombre)) }}
                     </p>
                     <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                         <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Cantidad de Grupos: </strong> {{ ucwords(strtolower($campeonatoSeleccionado->cantidad_grupos)) }}
                     </p>
                     <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                         <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Cantidad de Equipo x Grupo: </strong>{{$campeonatoSeleccionado->cantidad_equipos_grupo}}
                     </p>
                     <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                         <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Ptos Ganados:</strong> {{ ucwords(strtolower($campeonatoSeleccionado->puntos_ganado)) }}
                     </p>
                     <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                         <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Ptos Empatados:</strong> {{ ucwords(strtolower($campeonatoSeleccionado->puntos_empatado)) }}
                     </p>
                     <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                         <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Ptos Perdidos:</strong> {{ $campeonatoSeleccionado->puntos_perdido }}
                     </p>
                     <h4 class="text-1xl font-extrabold mb-6 text-white dark:text-gray-100 text-center border-b pb-3 border-gray-200 dark:border-gray-700">
                         Puntos por Tarjeta para Fair Play
                     </h4>
                     <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                         <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Puntos Tarjeta Amarilla:</strong> {{ ucwords(strtolower($campeonatoSeleccionado->puntos_tarjeta_amarilla)) }}
                     </p>
                     <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                         <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Puntos Doble Amarilla:</strong> {{ ucwords(strtolower($campeonatoSeleccionado->puntos_doble_amarilla)) }}
                     </p>
                     <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                         <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Puntos Tarjeta Roja:</strong> {{ ucwords(strtolower($campeonatoSeleccionado->puntos_tarjeta_roja)) }}
                     </p>
                     <h4 class="text-1xl font-extrabold mb-6 text-white dark:text-gray-100 text-center border-b pb-3 border-gray-200 dark:border-gray-700">
                         Criterios de Desempate
                     </h4>
                     <p class=">
                         <strong class=" font-semibold text-gray-400 dark:text-[#efb810]"></strong>

                         @foreach ($campeonatoSeleccionado->criterioDesempate as $criterio)

                     <div> Prioridad: {{ $criterio->orden }} - {{ ucwords(strtolower($criterio->criterio)) }}</div>
                     @endforeach
                     </p>

                 </div>

                 @else
                 <p class="text-center text-gray-600 dark:text-gray-400 py-4">No se ha seleccionado ningún campeonato para mostrar.</p>
                 @endif
             </div>
         </div>
     </div>



     @push('js')
     <script>
         document.addEventListener('livewire:initialized', () => {



             Livewire.on('confirmar-baja', ({
                 id
             }) => {


                 Swal.fire({
                     title: 'CUIDADO...',
                     text: "¿Estás seguro de borrar al el Campeonato?",
                     icon: 'warning',
                     showCancelButton: true,
                     confirmButtonColor: '#3085d6',
                     cancelButtonColor: '#d33',
                     confirmButtonText: 'Sí, Borrar'
                 }).then((result) => {
                     if (result.isConfirmed) {
                         Livewire.dispatch('eliminar-campeonato', {
                             id: id
                         });
                     }
                 });
             });

             Livewire.on('Baja', () => {
                 Swal.fire(
                     '¡Baja exitosa!',
                     'El campeonato se ha borrado correctamente.',
                     'success'
                 );
             });
         });
     </script>
     @endpush

 </div>