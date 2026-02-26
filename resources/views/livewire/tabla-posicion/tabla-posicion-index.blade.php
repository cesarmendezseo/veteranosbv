<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <div class="bg-blue-900 text-white p-2 shadow-md rounded flex justify-between items-center relative z-10"">
        <h2 class=" font-semibold text-xl text-gray-100 leading-tight">
        {{ __('Tabla de Posiciones') }}
        </h2>


    </div>
    <div class="mt-2">
        <input type="text" wire:model.live="search" placeholder="Buscar por nombre del campeonato "
            class="hidden md:table border rounded px-3 py-2.5 w-full mb-2" />
    </div>

    <flux:separator class="mb-2" />
    <table class="hidden md:table w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-100 uppercase bg-gray-500 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Campeonato
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
            <tr
                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">

                <th scope=" row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <div class="text-base font-semibold">{{ strtoupper($campeonato->nombre) }}</div>
                </th>
                <th scope=" row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <div class="font-normal ">
                        @if ($campeonato->formato === 'todos_contra_todos')
                        Todos contra Todos
                        @else
                        {{ ucfirst($campeonato->formato) }}
                    </div>
                    @endif
                </th>
                <th scope=" row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    @if ($campeonato->formato === 'todos_contra_todos')
                    @foreach ($campeonato->grupos as $grupo)
                    <div class="font-normal "> {{ $grupo->cantidad_equipos }} </div>
                    @endforeach
                    @else
                    <div class="font-normal "> {{ $campeonato->cantidad_equipos_grupo }} x Grupo</div>
                    @endif

                </th>

                <th scope=" row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <div class="font-normal text-gray-500 dark:text-white"> {{ ucfirst($campeonato->categoria->nombre)
                        }}</div>
                </th>


                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('tabla-posiciones.ver', $campeonato->id) }}" title="Ver"
                            class="cursor-pointer text-blue-600 hover:text-white border border-blue-600 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-xs px-2 py-1 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
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
            <input type="text" wire:model.live="search" placeholder="Buscar por nombre del campeonato o por año"
                class="border border-gray-500 rounded px-3 py-2.5 w-full mb-2" />
        </div>
        @foreach ($campeonatos as $campeonato)
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 border dark:border-gray-700">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white"><span
                        class="text-gray-600 dark:text-accent">Torneo:</span> {{ ucwords($campeonato->nombre) }}
                </h3>
                <div x-data="{ open: false }" class="relative" @keydown.escape.window="open=false"
                    @scroll.window="open=false">
                    <button @click="open = !open"
                        class="cursor-pointer text-gray-600 dark:text-gray-300 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v.01M12 12v.01M12 18v.01" />
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false"
                        class="absolute right-0 mt-2 bg-white dark:bg-gray-700 shadow rounded-lg z-50 flex flex-col p-4 ">
                        <a href="{{ route('tabla-posiciones.ver', $campeonato->id) }}"
                            class="flex items-center gap-2 hover:underline text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <span class="ml-1">Ver</span>
                        </a>

                    </div>
                </div>
            </div>
            <div class="text-gray-700 dark:text-gray-300">
                {{-- <p><span class="font-semibold">Formato:</span>
                    @if ($campeonato->formato === 'todos_contra_todos')
                    Todos contra Todos
                    @else
                    {{ ucfirst($campeonato->formato) }}
                @endif
                </p> --}}
                <p><span class="font-semibold">Categoría:</span> {{ ucfirst($campeonato->categoria->nombre) }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{ $campeonatos->links() }}


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