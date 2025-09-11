<div><x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Editar Equipos') }}
        </h2>
        <div class="flex items-center space-x-4">
        <a href="{{ route('equipo.index') }}" class=" text-white px-4 py-2 rounded flex items-center gap-2 hover:underline"> <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            Volver</a>
        </div>
    </x-slot>
    <div class="max-w-sm w-full lg:max-w-full lg:flex">

        <div class="w-full p-4 text-center bg-white border border-gray-200 rounded-lg shadow-sm sm:p-8 dark:bg-gray-800 dark:border-gray-700">
            <flux:separator class="bt-2" />
            <form wire:submit.prevent="actualizarEquipo" class="max-w-2xl mx-auto mt-4" enctype="multipart/form-data">
                @csrf
                <div class="grid md:grid-cols-2 md:gap-6">
                    <!-- NOMBRE -->
                    <div class="relative z-0 w-full mb-5 group">
                        <input wire:model="nombre" type="text" name="nombre" id="floating_first_name" value="{{ old('nombre') }}"
                            class="text-[#873600] block  py-2.5 px-0 w-full text-basic font-semibold  border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer @error('nombre') border-red-500 @enderror"
                            placeholder=" " required />
                        <label for="floating_first_name"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Nombre
                            Equipo
                        </label>
                        @error('nombre')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- CIUDAD -->
                    <div class="relative z-0 w-full mb-5 group">
                        <input wire:model="ciudad" type="text" name="ciudad" id="floating_last_name" value="{{ old('apellido') }}"
                            class="text-[#873600] block  py-2.5 px-0 w-full text-basic font-semibold  border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer @error('nombre') border-red-500 @enderror"
                            placeholder=" " />
                        <label for="floating_last_name"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Ciudad</label>
                        @error('ciudad')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="grid md:grid-cols-2 md:gap-6">
                    <!-- PROVINCIA -->
                    <div class="relative z-0 w-full mb-5 group">
                        <input wire:model="provincia" type="text" name="provincia" id="floating_first_name" value="{{ old('provincia') }}"
                            class="text-[#873600] block  py-2.5 px-0 w-full text-basic font-semibold  border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer @error('nombre') border-red-500 @enderror"
                            placeholder=" " />
                        <label for="floating_first_name"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Provincia
                        </label>

                    </div>
                    <!-- COD POS -->
                    <div class="relative z-0 w-full mb-5 group">
                        <input wire:model="cod_pos" type="number" name="cod_pos" id="floating_last_name" value="{{ old('apellido') }}"
                            class="text-[#873600] block  py-2.5 px-0 w-full text-basic font-semibold  border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer @error('nombre') border-red-500 @enderror"
                            placeholder=" " />
                        <label for="floating_last_name"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Cod.
                            Pos.</label>
                        @error('cod_pos')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                {{-- DESCRIPCION Y IS-ACTIVE --}}
                <div class="grid md:grid-cols-1 md:gap-6">
                    <div class="relative z-0 w-full mb-5 group">

                        <label for="message"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descripción</label>
                        <textarea wire:model="descripcion" id="message" rows="4" name="descripcion"
                            class="text-[#873600] block  py-2.5 px-0 w-full text-basic font-semibold  border-0 border-b-2 border-gray-300  dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer @error('nombre') border-red-500 @enderror"
                            placeholder="Escriba una descripción aquí..."></textarea>

                        @error('provincia')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
                {{-- FIN DESCRIPCION Y IS-ACTIVE --}}





                <div class="flex justify-between mt-4">
                    <button type="submit" class="bg-blue-950 text-white px-4 py-2 rounded flex items-center gap-2 cursor-pointer hover:bg-blue-800">
                        <svg class="w-6 h-6 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M11 16h2m6.707-9.293-2.414-2.414A1 1 0 0 0 16.586 4H5a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V7.414a1 1 0 0 0-.293-.707ZM16 20v-6a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v6h8ZM9 4h6v3a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1V4Z" />
                        </svg>

                        Guardar
                    </button>


                </div>
            </form>
        </div>

    </div>


</div>