 <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
     <x-navbar titulo="Tabla de Posiciones">
         <!--    <button wire:click="crear"
             class="px-3 py-2  text-white rounded flex items-center gap-1 cursor-pointer">
             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-badge-plus-icon lucide-badge-plus">
                 <path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z" />
                 <line x1="12" x2="12" y1="8" y2="16" />
                 <line x1="8" x2="16" y1="12" y2="12" />
             </svg> Crear
         </button> -->
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
                         <a href="{{ route('tabla-posiciones.ver', $campeonato->id) }}"
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
                         <a href="{{ route('tabla-posiciones.ver', $campeonato->id) }}" class="px-3 py-2 text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-600">Ver</a>

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