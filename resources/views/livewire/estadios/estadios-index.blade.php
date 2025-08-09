 <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
     <flux:navbar>
         <button wire:click="crear"
             class="px-5 py-2.5 gap-4 text-sm font-medium text-white inline-flex items-center bg-blue-950 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 shadow">
             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-badge-plus-icon lucide-badge-plus">
                 <path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z" />
                 <line x1="12" x2="12" y1="8" y2="16" />
                 <line x1="8" x2="16" y1="12" y2="12" />
             </svg>
             Crear
         </button>
         <h1 class="text-2xl font-bold text-gray-900 dark:text-white ml-4">Estadios</h1>
         @if(session('success'))
         <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
             {{ session('success') }}
         </div>
         @endif

     </flux:navbar>
     <flux:separator class="mb-2" />
     <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
         <thead class="text-xs text-gray-100 uppercase bg-gray-500 dark:bg-gray-700 dark:text-gray-400">
             <tr>
                 <th scope="col" class="px-6 py-3">
                     Nombre
                 </th>
                 <th scope="col" class="px-6 py-3">
                     Dirección
                 </th>
                 <th scope="col" class="px-6 py-3">
                     Ciudad
                 </th>
                 <th scope="col" class="px-6 py-3 text-center hidden sm:table-cell">
                     Acción
                 </th>
             </tr>
         </thead>
         <tbody>
             @foreach ($estadios as $estadio)
             <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">

                 <th scope=" row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                     <div class="text-base font-semibold">{{ucwords($estadio->nombre)}}</div>
                 </th>
                 <th scope=" row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                     <div class="font-normal text-gray-500"> {{ucfirst($estadio->direccion)}}</div>
                 </th>
                 <th scope=" row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                     <div class="font-normal text-gray-500"> {{ucwords($estadio->ciudad)}}</div>
                 </th>
                 <!-- BOTONES DE ACCION PARA PANTALLAS MOVIL -->
                 <td class="px-6 py-4 text-center">
                     <!-- Para pantallas medianas en adelante -->
                     <div class="hidden md:flex gap-2 justify-center">
                         {{-- Editar --}}
                         <a href="{{ route('estadios.editar', $estadio->id) }}"
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
                         {{-- Borrar --}}
                         <button wire:click="$dispatch('confirmar-baja',{id: {{ $estadio->id }} })"
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
                         <button wire:click="verEstadio({{ $estadio->id }})"
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
                     </div>

                     <!-- Para móviles: Menú desplegable -->
                     <div x-data="{ open: false }" class="relative md:hidden">
                         <button @click="open = !open"
                             class="text-gray-600 hover:text-black focus:outline-none">
                             <!-- Icono tres puntos -->
                             <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="2">
                                 <path stroke-linecap="round" stroke-linejoin="round"
                                     d="M12 6v.01M12 12v.01M12 18v.01" />
                             </svg>
                         </button>

                         <!-- Menú -->
                         <div x-show="open" @click.away="open = false"
                             class="absolute right-0 mt-2   z-50">
                             <!-- EDITAR -->
                             <a href="{{ route('estadios.editar', $estadio->id) }}"
                                 class="mb-1 text-white bg-gradient-to-r from-teal-400 via-teal-500 to-teal-600 hover:bg-gradient-to-br
                            focus:ring-4 focus:outline-none focus:ring-teal-300 dark:focus:ring-teal-800
                            shadow-lg shadow-teal-500/50 dark:shadow-lg dark:shadow-teal-800/80
                            font-medium rounded-full text-sm
                            h-10 w-10 flex items-center justify-center"
                                 title="Editar">
                                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                     <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                 </svg>
                             </a>

                             {{-- Borrar --}}
                             <button wire:click="$dispatch('confirmar-baja',{ id: {{ $estadio->id }} })"
                                 class="mb-1 text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br
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
                             <button wire:click="verEstadio({{ $estadio->id }})"
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
                         </div>
                     </div>
                 </td>
             </tr>
             @endforeach


         </tbody>
     </table>

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
                 Detalles del Estadio
             </h2>

             @if($estadioSeleccionada)
             <div class="grid grid-cols-1 md:grid-cols-1 gap-y-4 gap-x-6 text-gray-700 dark:text-[#efb810]">

                 <div class="grid grid-cols-1 md:grid-cols-1 gap-y-4 gap-x-6 text-[#efb810] font-semibold dark:text-gray-300">

                     <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                         <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Nombre :</strong> {{ ucwords(strtolower($estadioSeleccionada->nombre)) }}
                     </p>
                     <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                         <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Direccion :</strong> {{ ucwords(strtolower($estadioSeleccionada->direccion)) }}
                     </p>
                     <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                         <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Ciudad :</strong> {{ ucwords(strtolower($estadioSeleccionada->ciudad)) }}
                     </p>
                     <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                         <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Provincia :</strong> {{ ucwords(strtolower($estadioSeleccionada->provincia)) }}
                     </p>
                     <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                         <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Cod.Pos. :</strong> {{ ucwords(strtolower($estadioSeleccionada->cod_pos)) }}
                     </p>

                 </div>

                 @else
                 <p class="text-center text-gray-600 dark:text-gray-400 py-4">No se ha seleccionado ningún estadio para mostrar.</p>
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
                     text: "¿Estás seguro de borrar al el Estadio?",
                     icon: 'warning',
                     showCancelButton: true,
                     confirmButtonColor: '#3085d6',
                     cancelButtonColor: '#d33',
                     confirmButtonText: 'Sí, Borrar'
                 }).then((result) => {
                     if (result.isConfirmed) {
                         Livewire.dispatch('eliminar-estadio', {
                             id: id
                         });
                     }
                 });
             });

             Livewire.on('baja', () => {
                 Swal.fire(
                     '¡Baja exitosa!',
                     'El Estadio se ha borrado correctamente.',
                     'success'
                 );
             });

         });
     </script>
     @endpush
 </div>