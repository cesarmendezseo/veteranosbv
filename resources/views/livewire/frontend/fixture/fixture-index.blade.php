<div>

    <div class="w-full">
        <div class="  w-full mt-2">
            <div class="bg-blue-900 text-white p-2 shadow-md rounded flex justify-between items-center relative z-10">
                <h1 class="text-lg font-bold">Fixture </h1>

                <!--Nav para móvil (se muestra hasta md)  -->
                <nav class="flex md:hidden space-x-4 ml-1">

                    <input type="text" wire:model.live="search" placeholder="Buscar por nombre del campeonato o por año"
                        class="border border-gray-500 rounded px-2 py-2 w-full " />
                </nav>

                <!--Nav para escritorio (md en adelante)  -->

                </nav>
            </div>
        </div>
        <div class="hidden sm:block">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
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

                            <th scope=" row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <div class="text-base font-semibold">{{ ucwords($campeonato->nombre) }}</div>
                            </th>
                            <th scope=" row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <div class="font-normal text-gray-500">
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
                                <div class="font-normal text-gray-500"> {{ $grupo->cantidad_equipos }} </div>
                            @endforeach
                        @else
                            <div class="font-normal text-gray-500"> {{ $campeonato->cantidad_equipos_grupo }} x Grupo
                            </div>
                        @endif

                    </th>

                    <th scope=" row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <div class="font-normal text-gray-500"> {{ ucfirst($campeonato->categoria->nombre) }}</div>
                    </th>


                    <!-- BOTONES DE ACCION PARA PANTALLAS MOVIL -->
                    <td class="px-6 py-4 text-right">
                        <!-- Para pantallas medianas en adelante -->
                        <div class="hidden md:flex gap-2 justify-end">
                            {{-- ver --}}

                            <a href="{{ route('frontend.fixture.verFixture', $campeonato->id) }}" wire:navigate
                                type="button"
                                class="text-[#0638a3] hover:underline   font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:focus:ring-[#3b5998]/55 me-2 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                <span class="ml-2">Ver</span>
                            </a>

                        </div>

                    </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="grid gap-4 md:hidden">
            @foreach ($campeonatos as $campeonato)
                <div
                    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-md p-4">

                    {{-- Nombre del campeonato --}}
                    <div class="flex justify-between items-center mb-2">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ ucwords($campeonato->nombre) }}</h2>

                        {{-- Menú acciones móvil --}}
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="text-gray-600 dark:text-gray-300 focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-900"
                                    viewBox="0 0 24 24" fill="currentColor">
                                    <circle cx="12" cy="6" r="1.5" />
                                    <circle cx="12" cy="12" r="1.5" />
                                    <circle cx="12" cy="18" r="1.5" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-collapse
                                class="absolute bg-[#0A2A5E] right-0 mt-2 z-50 flex flex-col gap-2 dark:bg-gray-800 dark:border dark:border-gray-700 p-2 rounded-l-lg">

                                <a href="{{ route('frontend.fixture.verFixture', $campeonato->id) }}"
                                    class="px-3 py-2 text-gray-100 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-600 flex items-center gap-4 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye">
                                        <path
                                            d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                    <span class="whitespace-nowrap">Ver</span>
                                </a>

                            </div>
                        </div>
                    </div>

                    {{-- Detalles del campeonato --}}
                    <div class="space-y-1 text-gray-700 dark:text-gray-300">
                        <div><span class="font-semibold">Formato:</span>
                            @if ($campeonato->formato === 'todos_contra_todos')
                                Todos contra Todos
                            @else
                                {{ ucfirst($campeonato->formato) }}
                            @endif
                        </div>

                        <div><span class="font-semibold">Cant. Equipos:</span>
                            @if ($campeonato->formato === 'todos_contra_todos')
                                @foreach ($campeonato->grupos as $grupo)
                                    <div>{{ $grupo->cantidad_equipos }}</div>
                                @endforeach
                            @else
                                {{ $campeonato->cantidad_equipos_grupo }} x Grupo
                            @endif
                        </div>

                        <div><span class="font-semibold">Categoría:</span>
                            {{ ucfirst($campeonato->categoria->nombre) }}</div>
                    </div>


                </div>
            @endforeach
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
