<div class=" w-full">
    <div class="flex flex-col min-h-screen">
        {{-- La barra de navegación se quedará en la parte superior --}}
        <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Cargar Jugador') }}
        </h2>
        <div class="flex items-center space-x-4">
            {{-- Botón para volver a la lista de categorías --}}
            <a href="{{ route('jugadores.index') }}" class=" text-white px-4 py-2 rounded flex items-center gap-2 hover:underline">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Volver
            </a>
        </div>
         </x-slot>


        <div class="w-full p-4 text-center bg-white border border-gray-200 rounded-lg shadow-sm sm:p-8 dark:bg-gray-800 dark:border-gray-700">

            <form wire:submit.prevent="guardar" class="max-w-2xl mx-auto" enctype="multipart/form-data">
                @csrf
                <div class="grid md:grid-cols-3 md:gap-6">
                    <!-- NOMBRE -->
                    <div class="relative z-0 w-full mb-5 group">
                        <input wire:model="nombre" type="text" name="nombre" id="floating_first_name" value="{{ old('nombre') }}"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer @error('nombre') border-red-500 @enderror"
                            placeholder=" " required />
                        <label for="floating_first_name"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Nombre
                        </label>
                        @error('nombre')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- APELLIDO -->
                    <div class="relative z-0 w-full mb-5 group">
                        <input wire:model="apellido" type="text" name="ciudad" id="floating_last_name" value="{{ old('ciudad') }}"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer @error('apellido') border-red-500 @enderror"
                            placeholder=" " />
                        <label for="floating_last_name"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Apellido</label>
                        @error('apellido')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- TIPO DOCUMENTO -->
                    <div class="relative z-0 w-full mb-5 group">

                        <select wire:model="tipo_documento" id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <!--  <option value="">Tipo</option> -->
                            <option value="dni">DNI</option>
                            <option value="cuil">CUIL</option>

                        </select>
                    </div>

                    <!-- DOCUMENTO -->
                    <div class="relative z-0 w-full mb-5 group">
                        <input wire:model="documento" type="number" name="documento" id="floating_first_name" value="{{ old('documento') }}"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer @error('provincia') border-red-500 @enderror"
                            placeholder=" " />
                        <label for="floating_first_name"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">DNI
                        </label>

                    </div>
                    <!-- FECHA NACIMIENTO -->
                    <div class="relative z-0 w-full mb-5 group">
                        <div class="relative max-w-sm">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                </svg>
                            </div>
                            <input wire:model="nacimiento" datepicker id="default-datepicker" type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date">
                        </div>

                    </div>
                    <!-- SOCIO -->
                    <div class="relative z-0 w-full mb-5 group">
                        <input wire:model="socio" type="number" name="socio" id="floating_first_name" value="{{ old('socio') }}"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer @error('provincia') border-red-500 @enderror"
                            placeholder=" " />
                        <label for="floating_first_name"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">N° Socio
                        </label>
                    </div>

                    <!-- TELEFONO -->
                    <div class="relative z-0 w-full mb-5 group">
                        <input wire:model="telefono" type="number" name="telefono" id="floating_first_name" value="{{ old('telefono') }}"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer @error('provincia') border-red-500 @enderror"
                            placeholder=" " />
                        <label for="floating_first_name"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Teléfono
                        </label>
                    </div>

                    <!-- EMAIL -->
                    <div class="relative z-0 w-full mb-5 group">
                        <input wire:model="email" type="email" name="email" id="floating_first_name" value="{{ old('email') }}"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer @error('provincia') border-red-500 @enderror"
                            placeholder=" " />
                        <label for="floating_first_name"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email
                        </label>
                    </div>

                    <!-- direccion -->
                    <div class="relative z-0 w-full mb-5 group">
                        <input wire:model="direccion" type="text" name="direccion" id="floating_first_name" value="{{ old('direccion') }}"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer @error('provincia') border-red-500 @enderror"
                            placeholder=" " />
                        <label for="floating_first_name"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Dirección
                        </label>
                    </div>

                    <!-- CIUDAD -->
                    <div class="relative z-0 w-full mb-5 group">
                        <input wire:model="ciudad" type="text" name="ciudad" id="floating_first_name" value="{{ old('ciudad') }}"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer @error('provincia') border-red-500 @enderror"
                            placeholder=" " />
                        <label for="floating_first_name"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Ciudad
                        </label>
                    </div>
                    @error('ciudad')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <!-- PROVINCIA -->
                    <div class="relative z-0 w-full mb-5 group">
                        <input wire:model="provincia" type="text" name="provincia" id="floating_first_name" value="{{ old('provincia') }}"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer @error('provincia') border-red-500 @enderror"
                            placeholder=" " />
                        <label for="floating_first_name"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Provincia
                        </label>
                    </div>
                    @error('provincia')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <!-- COD POS -->
                    <div class="relative z-0 w-full mb-5 group">
                        <input wire:model="cod_pos" type="number" name="cod_pos" id="floating_first_name" value="{{ old('cod_pos') }}"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer @error('provincia') border-red-500 @enderror"
                            placeholder=" " />
                        <label for="floating_first_name"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Cod. Pos.
                        </label>
                    </div>
                    @error('cod_pos')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <!-- EQUIPO -->
                    <div class="relative z-0 w-full mb-5 group">
                        <select wire:model="equipo_seleccionado" id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="">Seleccionar equipo</option>
                            @foreach ($equipos as $equipo)
                            <option value="{{ $equipo->id }}">{{ strtoupper($equipo->nombre) }}</option>
                            @endforeach
                        </select>

                        {{-- Mostrar error justo debajo --}}
                        @error('equipo_seleccionado')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <label class="inline-flex items-center cursor-pointer">
                        <input wire:model="activo" type="checkbox" value="" class="sr-only peer">
                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-600"></div>
                        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Activo</span>
                    </label>
                </div>





                <div class="flex justify-between mt-4">
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-medium text-white inline-flex items-center bg-blue-950 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="mr-2 lucide lucide-save-icon lucide-save">
                            <path
                                d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
                            <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7" />
                            <path d="M7 3v4a1 1 0 0 0 1 1h7" />
                        </svg>

                        Guardar
                    </button>

                </div>
            </form>
        </div>

        @push('js')
        <script>
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('creado', ({
                    id
                }) => {
                    Swal.fire({
                        title: 'Exito...',
                        text: "Jugador creado correctamente",
                        icon: 'succes',

                        confirmButtonColor: '#3085d6',

                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Livewire.dispatch('index', {
                                id: id
                            });
                        }
                    });
                });
                //para errroes de validación
                Livewire.on('alertaError', (event) => {
                    const message = event.message; // Accede a los datos del evento
                    Swal.fire({
                        icon: 'error',
                        title: 'Errores de validación',
                        text: message.replace(/\n/g, '\n'),
                        customClass: {
                            popup: 'text-sm'
                        }
                    });
                });

            });
        </script>
        @endpush
    </div>
</div>