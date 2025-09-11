 <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

     <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Crear Categorias') }}
        </h2>
        <div class="flex items-center space-x-4">
         <a href="{{ route('categoria.index') }}" class=" text-white px-4 py-2 rounded flex items-center gap-2 hover:underline"> <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                 <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
             </svg>
             Volver</a>
        </div>
     </x-slot>  
     <div class="max-w-sm w-full lg:max-w-full lg:flex">
         <div class="w-full p-4 text-center bg-white border border-gray-200 rounded-lg shadow-sm sm:p-8 dark:bg-gray-800 dark:border-gray-700">
             <form wire:submit.prevent="guardar" class="max-w-2xl mx-auto" enctype="multipart/form-data">
                 @csrf
                 <div class="grid md:grid-cols-1 md:gap-6">
                     <!-- NOMBRE -->
                     <div class="relative z-0 w-full mb-5 group">
                         <input wire:model="nombre" type="text" name="nombre" id="floating_first_name" value="{{ old('nombre') }}"
                             class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer @error('nombre') border-red-500 @enderror"
                             placeholder=" " required />
                         <label for="floating_first_name"
                             class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Nombre
                             Categoría
                         </label>
                         @error('nombre')
                         <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                         @enderror
                     </div>

                     {{-- DESCRIPCION Y IS-ACTIVE --}}
                     <div class="grid md:grid-cols-1 md:gap-6">
                         <div class="relative z-0 w-full mb-5 group">

                             <label for="message"
                                 class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descripción</label>
                             <textarea wire:model="descripcion" id="message" rows="4" name="descripcion"
                                 class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                 placeholder="Escriba una descripción aquí..."></textarea>

                             @error('provincia')
                             <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                             @enderror
                         </div>

                     </div>
                     {{-- FIN DESCRIPCION Y IS-ACTIVE --}}




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
                 </div>
             </form>
         </div>
     </div>
 </div>