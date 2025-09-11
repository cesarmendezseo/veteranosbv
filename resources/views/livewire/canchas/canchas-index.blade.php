 <div class="relative overflow-x-auto shadow-md sm:rounded-lg">


    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Canchas') }}
        </h2>
        <div class="flex items-center space-x-4">
         @adminOrCan('comision')
             <a href="{{ route('canchas.crear') }}"
                 class="px-5 py-2.5 gap-4 text-sm font-medium text-white inline-flex items-center  hover:underline focus:ring-4  cursor-pointer rounded-lg text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 shadow">
                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="lucide lucide-badge-plus-icon lucide-badge-plus">
                     <path
                         d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z" />
                     <line x1="12" x2="12" y1="8" y2="16" />
                     <line x1="8" x2="16" y1="12" y2="12" />
                 </svg>
                 Crear
             </a>
         @endadminOrCan
        </div>
     </x-slot>
    
     <table class="hidden md:table w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
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
             @foreach ($canchas as $cancha)
                 <tr
                     class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">

                     <th scope=" row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                         <div class="text-base font-semibold">{{ ucwords($cancha->nombre) }}</div>
                     </th>
                     <th scope=" row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                         <div class="font-normal text-gray-500"> {{ ucfirst($cancha->direccion) }}</div>
                     </th>
                     <th scope=" row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                         <div class="font-normal text-gray-500"> {{ ucwords($cancha->ciudad) }}</div>
                     </th>
                     <!-- BOTONES DE ACCION  -->

                     <td class="px-6 py-4 text-right">
                         <!-- Para pantallas medianas en adelante -->
                         <div x-data="{ open: false, top: 0, left: 0 }" class="relative" @keydown.escape.window="open=false"
                             @scroll.window="open=false">
                             <button x-ref="trigger"
                                 @click="
                            open = !open;
                            $nextTick(() => {
                                const btn = $refs.trigger;
                                const rect = btn.getBoundingClientRect();
                                const menu = $refs.menu;

                                // Mostrar temporalmente para medir tamaño real
                                const prevDisplay = menu.style.display;
                                const prevVisibility = menu.style.visibility;
                                menu.style.visibility = 'hidden';
                                menu.style.display = 'block';

                                const mh = menu.offsetHeight;
                                const mw = menu.offsetWidth;

                                // Restaurar estado
                                menu.style.display = prevDisplay;
                                menu.style.visibility = prevVisibility;

                                // Posición por defecto: debajo del botón
                                let top = rect.bottom + 8;
                                let left = rect.right - mw;

                                // Si no entra abajo, abrir arriba
                                if (top + mh > window.innerHeight) {
                                    top = rect.top - mh - 8;
                                }

                                // Limitar a bordes laterales
                                left = Math.max(8, Math.min(left, window.innerWidth - mw - 8));

                                $data.top = top;
                                $data.left = left;
                            });
                        "
                                 class="text-gray-600 hover:text-black focus:outline-none dark:text-white cursor-pointer">
                                 <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                     <path stroke-linecap="round" stroke-linejoin="round"
                                         d="M12 6v.01M12 12v.01M12 18v.01" />
                                 </svg>
                             </button>

                             <div x-ref="menu" x-cloak x-show="open" @click.away="open=false"
                                 class="fixed z-50 flex flex-col gap-2 p-2 rounded-lg shadow-lg bg-gray-100 dark:bg-gray-800 dark:border dark:border-gray-700"
                                 :style="`top:${top}px; left:${left}px`" x-transition.opacity>
                                 <!--VER-->
                                 <a wire:click="verEstadio({{ $cancha->id }})"
                                     class="cursor-pointer flex items-center gap-2 hover:underline">
                                     <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="size-6">
                                         <path stroke-linecap="round" stroke-linejoin="round"
                                             d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                         <path stroke-linecap="round" stroke-linejoin="round"
                                             d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                     </svg>
                                     <span class="ml-1">Ver</span>
                                 </a>
                                 <!-- EDITAR -->
                                 @adminOrCan('comision')
                                     <a href="{{ route('canchas.editar', $cancha->id) }}"
                                         class="cursor-pointer flex items-center gap-2 hover:underline">
                                         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                             <path stroke-linecap="round" stroke-linejoin="round"
                                                 d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                         </svg>
                                         <span>Editar</span>
                                     </a>
                                     <!-- BORRAR -->
                                     <a wire:click="$dispatch('confirmar-baja',{id: {{ $cancha->id }} })"
                                         class="cursor-pointer flex items-center gap-2 hover:underline">
                                         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                             <path stroke-linecap="round" stroke-linejoin="round"
                                                 d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                         </svg>
                                         <span>Borrar</span>
                                     </a>
                                 @endadminOrCan
                             </div>
                         </div>

                     </td>
                 </tr>
             @endforeach
         </tbody>
     </table>
     <!-- Para móvil: tarjetas -->
     <div class="md:hidden  space-y-4 min-h-screen">
         @foreach ($canchas as $cancha)
             <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 border dark:border-gray-700">
                 <div class="flex justify-between items-center mb-2">
                     <h3 class="text-lg font-bold text-gray-900 dark:text-white"><span
                             class="text-gray-600 dark:text-accent">Nombre:</span> {{ ucwords($cancha->nombre) }}</h3>
                     <div x-data="{ open: false, top: 0, left: 0 }" class="relative" @keydown.escape.window="open=false"
                         @scroll.window="open=false">
                         <button x-ref="trigger"
                             @click="
                            open = !open;
                            $nextTick(() => {
                                const btn = $refs.trigger;
                                const rect = btn.getBoundingClientRect();
                                const menu = $refs.menu;

                                // Mostrar temporalmente para medir tamaño real
                                const prevDisplay = menu.style.display;
                                const prevVisibility = menu.style.visibility;
                                menu.style.visibility = 'hidden';
                                menu.style.display = 'block';

                                const mh = menu.offsetHeight;
                                const mw = menu.offsetWidth;

                                // Restaurar estado
                                menu.style.display = prevDisplay;
                                menu.style.visibility = prevVisibility;

                                // Posición por defecto: debajo del botón
                                let top = rect.bottom + 8;
                                let left = rect.right - mw;

                                // Si no entra abajo, abrir arriba
                                if (top + mh > window.innerHeight) {
                                    top = rect.top - mh - 8;
                                }

                                // Limitar a bordes laterales
                                left = Math.max(8, Math.min(left, window.innerWidth - mw - 8));

                                $data.top = top;
                                $data.left = left;
                            });
                        "
                             class="text-gray-600 hover:text-black focus:outline-none dark:text-white cursor-pointer">
                             <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                 <path stroke-linecap="round" stroke-linejoin="round"
                                     d="M12 6v.01M12 12v.01M12 18v.01" />
                             </svg>
                         </button>

                         <div x-ref="menu" x-cloak x-show="open" @click.away="open=false"
                             class="fixed z-50 flex flex-col gap-2 p-2 rounded-lg shadow-lg bg-gray-100 dark:bg-gray-800 dark:border dark:border-gray-700"
                             :style="`top:${top}px; left:${left}px`" x-transition.opacity>
                             <!--VER-->
                             <a wire:click="verEstadio({{ $cancha->id }})"
                                 class="cursor-pointer flex items-center gap-2 hover:underline">
                                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-6">
                                     <path stroke-linecap="round" stroke-linejoin="round"
                                         d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                     <path stroke-linecap="round" stroke-linejoin="round"
                                         d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                 </svg>
                                 <span class="ml-1">Ver</span>
                             </a>
                             <!-- EDITAR -->
                             @adminOrCan('comision')
                                 <a href="{{ route('canchas.editar', $cancha->id) }}"
                                     class="cursor-pointer flex items-center gap-2 hover:underline">
                                     <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                         <path stroke-linecap="round" stroke-linejoin="round"
                                             d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                     </svg>
                                     <span>Editar</span>
                                 </a>
                                 <!-- BORRAR -->
                                 <a wire:click="$dispatch('confirmar-baja',{id: {{ $cancha->id }} })"
                                     class="cursor-pointer flex items-center gap-2 hover:underline">
                                     <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                         <path stroke-linecap="round" stroke-linejoin="round"
                                             d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                     </svg>
                                     <span>Borrar</span>
                                 </a>
                             @endadminOrCan
                         </div>
                     </div>
                 </div>
                 <div class="text-gray-700 dark:text-gray-300">
                     <p><span class="font-semibold">Dirección:</span>
                         {{ ucfirst($cancha->direccion) }}
                     </p>
                     <p><span class="font-semibold">Ciudad:</span> {{ ucwords($cancha->ciudad) }}</p>
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
     <div x-data="{ show: false }" x-show="show" x-on:static-modal.window="show = true" x-cloak
         class="shadow-2xs fixed inset-0 z-50 flex items-center justify-center bg-[rgba(0,0,0,0.5)] p-4 sm:p-6">

         <div
             class="bg-gradient-custom bg-blue-900 dark:bg-gray-800 rounded-xl shadow-2xl max-w-xl w-full p-6 sm:p-8 relative transform transition-all scale-100 opacity-100 ease-out duration-300
                max-h-[90vh] overflow-y-auto">
             <!-- Clases añadidas aquí -->
             <!-- Botón de Cierre -->
             <button @click="show = false"
                 class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-200 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full p-1">
                 <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M6 18L18 6M6 6l12 12" />
                 </svg>
             </button>

             <h2
                 class="text-2xl font-extrabold mb-6 text-white dark:text-gray-100 text-center border-b pb-3 border-gray-200 dark:border-gray-700">
                 Detalles de la cancha.
             </h2>

             @if ($canchaSeleccionada)
                 <div class="grid grid-cols-1 md:grid-cols-1 gap-y-4 gap-x-6 text-gray-700 dark:text-[#efb810]">

                     <div
                         class="grid grid-cols-1 md:grid-cols-1 gap-y-4 gap-x-6 text-[#efb810] font-semibold dark:text-gray-300">

                         <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                             <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Nombre :</strong>
                             {{ ucwords(strtolower($canchaSeleccionada->nombre)) }}
                         </p>
                         <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                             <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Direccion :</strong>
                             {{ ucwords(strtolower($canchaSeleccionada->direccion)) }}
                         </p>
                         <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                             <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Ciudad :</strong>
                             {{ ucwords(strtolower($canchaSeleccionada->ciudad)) }}
                         </p>
                         <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                             <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Provincia :</strong>
                             {{ ucwords(strtolower($canchaSeleccionada->provincia)) }}
                         </p>
                         <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                             <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Cod.Pos. :</strong>
                             {{ ucwords(strtolower($canchaSeleccionada->cod_pos)) }}
                         </p>

                     </div>
                 @else
                     <p class="text-center text-gray-600 dark:text-gray-400 py-4">No se ha seleccionado ninguna cancha
                         para mostrar.</p>
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
