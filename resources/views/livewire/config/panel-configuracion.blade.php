<div>
    <div class="space-y-4 bg-gray-100 dark:bg-gray-900 p-6 rounded shadow-md">

        @if (session('ok'))
        <div class="p-3 bg-green-100 text-green-700 rounded">
            {{ session('ok') }}
        </div>
        @endif
        <div class="bg-blue-900 text-white p-4 shadow-md rounded flex justify-between items-center relative z-10">
            <h2 class="text-xl font-bold">Configuración de Inicio</h2>
        </div>
        {{-- *********************TITULO DE LA PAGINA****************** --}}
        <div class="mb-4 p-4 bg-gray-300 dark:text-white dark:bg-gray-700 rounded-lg space-y-4">
            @adminOrCan('administrador')
            <!-- Campo: Título -->
            <div>
                <label for="first_name" class="text-lg">Título de la página</label>
                <p class="text-xs text-gray-600 dark:text-gray-200 mb-1">
                    Es el nombre que aparece al lado del logo
                </p>

                <input type="text" wire:model="titulo" id="first_name" class="bg-gray-50 dark:bg-gray-800 border shadow-2xs border-default-medium 
                   text-heading text-sm rounded-base focus:ring-brand focus:border-brand 
                   block w-full px-3 py-2.5 placeholder:text-body" placeholder="Ej: ABV Futsal" required />
            </div>

            <!-- Botón Cargar Logo -->
            <div>
                <a href="{{ route('cargar.logo') }}" class="inline-block bg-blue-500 text-white px-4 py-3 rounded 
                  cursor-pointer hover:bg-blue-700">
                    Cargar Logo
                </a>
            </div>
            <div class="mb-4 p-4 bg-gray-300 dark:text-white dark:bg-gray-700 rounded-lg space-y-4">
                <div>
                    <label for="first_name" class="block mb-2.5  font-titulo text-heading">Footer de la página</label>
                    <input type="text" wire:model="textoFooter" id="first_name"
                        class="bg-gray-50 dark:bg-gray-800 border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
                        placeholder="" required />
                </div>

            </div>
            <hr class="mb-4 border-gray-400">
            <div class="flex gap-4 p-2">

                <div class="mb-4">
                    <a href="{{ route('cargar.imagenPrincipal') }}"
                        class="bg-blue-500  text-white px-4 py-3 rounded cursor-pointer hover:bg-blue-700">
                        Cargar Imagen Principal
                    </a>
                </div>
            </div>

        </div>





        {{-- -------------------LEYENDAS--------------------------- --}}
        <div class=" mb-4 p-4 bg-gray-300 dark:text-white dark:bg-gray-700 rounded-lg space-y-4 ">
            <label for=" message" class="block mb-2.5 text-base font-titulo text-heading">Leyenda de la página
                principal 1</label>
            <textarea id="message" rows="4" wire:model="leyendaPrincipal1"
                class="bg-neutral-secondary-medium border bg-gray-50 dark:text-white dark:bg-gray-800 border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-3.5 shadow-xs placeholder:text-body"
                placeholder="Escribe aqui lo que quieras que aparezca en la pagina princial..."></textarea>
        </div>
        <div class="mb-4 p-4 bg-gray-300 dark:text-white dark:bg-gray-700 rounded-lg space-y-4">
            <label for="message" class="block mb-2.5 text-base font-titulo text-heading">Leyenda de la página
                principal 2</label>
            <textarea id="message" rows="4" wire:model="leyendaPrincipal2"
                class="bg-gray-50 border border-default-medium text-heading dark:text-white dark:bg-gray-800 text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-3.5 shadow-xs placeholder:text-body"
                placeholder="Escribe aqui lo que quieras que aparezca en la pagina princial..."></textarea>
        </div>
        <div class="mb-4 p-4 bg-gray-300 dark:text-white dark:bg-gray-700 rounded-lg space-y-4">
            <label for="message" class="block mb-2.5 text-base font-titulo text-heading">Leyenda de la página
                principal 3</label>
            <textarea id="message" rows="4" wire:model="leyendaPrincipal3"
                class="border bg-gray-50 border-default-medium text-heading dark:text-white dark:bg-gray-800 text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-3.5 shadow-xs placeholder:text-body"
                placeholder="Escribe aqui lo que quieras que aparezca en la pagina princial..."></textarea>
        </div>

        @endadminOrCan
        {{-- *********************campeoanto principal********************* --}}
        <div class="mb-4 p-4 bg-gray-300 dark:text-white dark:bg-gray-700 rounded-lg space-y-4">
            <form class="">
                <label for="countries" class="text-lg "><span class="">Campeonato
                        Principal</span>
                    <p class="text-xs text-gray-600 mb-2 dark:text-gray-200 ">Seleccione el campeonato que quiere que
                        aparezca
                        en la pagina principal</p>
                </label>
                <select id="countries" wire:model="campeonatoPrincipal"
                    class="bg-gray-50 block w-full px-3 py-2.5 bg-neutral-secondary-medium border dark:bg-gray-800 border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-2xs placeholder:text-body">
                    <option value="">-- Seleccionar --</option>
                    @foreach(\App\Models\Campeonato::all() as $camp)
                    <option value="{{ $camp->id }}">{{strtoupper( $camp->nombre) }}</option>
                    @endforeach
                </select>

            </form>
        </div>
        {{-- --------------------------------------------------------------------------- --}}


        <div class="mb-4 p-4 bg-gray-300 dark:text-white dark:bg-gray-700 rounded-lg space-y-4">
            <div>
                <label class=" mb-3 text-lg">Título Principal</label>
                <input type="text" class="bg-gray-50 dark:bg-gray-800 shadow-2xs border w-full p-2"
                    wire:model="tituloPrincipal">
            </div>
        </div>
        {{-- ******************************************************* --}}

        <hr class="mb-4 border-gray-400">
        <div class="flex gap-3 items-center">
            <input type="checkbox" wire:model="mostrarTablaPosiciones">
            <label>Mostrar tabla de posiciones</label>
        </div>

        <div class="flex gap-3 items-center">
            <input type="checkbox" wire:model="mostrarProximosEncuentros">
            <label>Mostrar próximos encuentros</label>
        </div>
        <div class="flex gap-3 items-center">
            <input type="checkbox" wire:model="mostrarGoleadores">
            <label>Mostrar goleadores</label>
        </div>
        <div class="flex gap-3 items-center">
            <input type="checkbox" wire:model="mostrarSanciones">
            <label>Mostrar sanciones</label>
        </div>

        <hr class="border border-gray-400">
        {{-- ************CONFIGURACION DE FUENTES Y TAMAÑOS***************** --}}

        <h5>Configuración de tipografias, tamaños y color </h5>
        <div class="space-y-3 bg-gray-100 border-gray-400 border dark:bg-gray-600 p-4 rounded">
            <h5>Titulo</h5>
            <hr class="mb-4 border-gray-400">
            <div class="grid grid-cols-4 gap-4">
                <div><label class="font-semibold block">Fuente:</label>
                    <select wire:model="tituloFuente"
                        class="border border-gray-500  rounded px-3 py-1 w-full bg-gray-50 dark:bg-gray-800">
                        @foreach ($fuentes_disponibles as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Tamaño</label>
                    {{-- <input type="text" wire:model="tituloSize"
                        class="bg-gray-100 dark:bg-gray-800 border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
                        placeholder="" required /> --}}
                    <select wire:model="tituloSize"
                        class="border border-gray-500  rounded px-3 py-1 bg-gray-50 w-full dark:bg-gray-800">
                        <option value="8px">12</option>
                        <option value="10px">12</option>
                        <option value="12px">12</option>
                        <option value="14px">14</option>
                        <option value="18px">18</option>
                        <option value="24px">24</option>
                        <option value="36px">36</option>
                        <option value="40px">40</option>
                    </select>
                </div>

                <div>
                    <label>Grosor</label>
                    <select wire:model="tituloWeight"
                        class="border border-gray-500  rounded px-3 py-1 bg-gray-50 w-full dark:bg-gray-800">
                        <option value="300">Light</option>
                        <option value="400">Normal</option>
                        <option value="500">Medium</option>
                        <option value="600">SemiBold</option>
                        <option value="700">Bold</option>
                        <option value="800">ExtraBold</option>
                    </select>
                </div>
                <div>
                    <label class="font-semibold block">Color:</label>
                    <input type="color" wire:model="tituloColor"
                        value="{{ \App\Models\Configuracion::get('titulo_color', '#ffffff') }}"
                        class="w-16 h-10 cursor-pointer">
                </div>
            </div>
        </div>
        <hr>
        {{-- ************************CONFIG LEYENDA 1*********************** --}}

        <div class="space-y-3  bg-gray-100 border-gray-400 border dark:bg-gray-600 p-4 rounded">
            <H5 class="font-roboto">Leyenda 1</H5>
            <hr class="mb-4 border-gray-400">
            <div class="grid grid-cols-4 gap-4">
                <div><label class="font-semibold block">Tipografia:</label>
                    <select wire:model="leyendaPrincipalFuente1"
                        class="border border-gray-500  rounded px-3 py-1 w-full bg-gray-50 dark:bg-gray-800">
                        @foreach ($fuentes_disponibles as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Tamaño</label>

                    <select wire:model="leyendaSize1"
                        class="border border-gray-500  rounded px-3 py-1 w-full bg-gray-50 dark:bg-gray-800">
                        <option value="12px">12</option>
                        <option value="14px">14</option>
                        <option value="18px">18</option>
                        <option value="24px">24</option>
                        <option value="36px">36</option>
                        <option value="40px">40</option>
                    </select>
                </div>

                <div>
                    <label>Grosor</label>
                    <select wire:model="leyendaWeight1"
                        class="border border-gray-500  rounded px-3 py-1 w-full bg-gray-50 dark:bg-gray-800">
                        <option value="300">Light</option>
                        <option value="400">Normal</option>
                        <option value="500">Medium</option>
                        <option value="600">SemiBold</option>
                        <option value="700">Bold</option>
                        <option value="800">ExtraBold</option>
                    </select>
                </div>
                <div>
                    <label class="font-semibold block">Color:</label>
                    <input type="color" wire:model="leyendaPrincipalColor1"
                        value="{{ \App\Models\Configuracion::get('titulo_color', '#ffffff') }}"
                        class="w-16 h-10 cursor-pointer">
                </div>
            </div>
        </div>

        {{-- ************************CONFIG LEYENDA 2************************************* --}}
        <div class="space-y-3  bg-gray-100 border-gray-400 border dark:bg-gray-600 p-4 rounded">
            <H5>Leyenda 2</H5>
            <hr class="mb-4 border-gray-400">

            <div class="grid grid-cols-4 gap-4">
                <div><label class="font-semibold block">Tipografia:</label>
                    <select wire:model="leyendaPrincipalFuente2"
                        class="border border-gray-500 rounded px-3 py-1 w-full bg-gray-50 dark:bg-gray-800">
                        @foreach ($fuentes_disponibles as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Tamaño</label>

                    <select wire:model="leyendaSize2"
                        class="border border-gray-500  rounded bg-gray-50 px-3 py-1 w-full dark:bg-gray-800">
                        <option value="12px">12</option>
                        <option value="14px">14</option>
                        <option value="18px">18</option>
                        <option value="24px">24</option>
                        <option value="36px">36</option>
                        <option value="40px">40</option>
                    </select>
                </div>

                <div>
                    <label>Grosor</label>
                    <select wire:model="leyendaWeight2"
                        class="border border-gray-500  rounded bg-gray-50 px-3 py-1 w-full dark:bg-gray-800">
                        <option value="300">Light</option>
                        <option value="400">Normal</option>
                        <option value="500">Medium</option>
                        <option value="600">SemiBold</option>
                        <option value="700">Bold</option>
                        <option value="800">ExtraBold</option>
                    </select>
                </div>
                <div>
                    <label class="font-semibold block">Color:</label>
                    <input type="color" wire:model="leyendaPrincipalColor2"
                        value="{{ \App\Models\Configuracion::get('titulo_color', '#ffffff') }}"
                        class="w-16 h-10 cursor-pointer">
                </div>
            </div>
        </div>
        {{-- ********************************CONFIG LEYENDA 3********************** --}}
        <div class="space-y-3  bg-gray-100 border-gray-400 border dark:bg-gray-600 p-4 rounded">
            <H5>Leyenda 3</H5>
            <hr class="mb-4 border-gray-400">
            <div class="grid grid-cols-4 gap-4">
                <div><label class="font-semibold block">Tipografia:</label>
                    <select wire:model="leyendaPrincipalFuente3"
                        class="border border-gray-500  rounded px-3 py-1 w-full bg-gray-50 dark:bg-gray-800">
                        @foreach ($fuentes_disponibles as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Tamaño</label>

                    <select wire:model="leyendaSize3"
                        class="border border-gray-500  rounded px-3 py-1 w-full bg-gray-50 dark:bg-gray-800">
                        <option value="12px">12</option>
                        <option value="14px">14</option>
                        <option value="18px">18</option>
                        <option value="24px">24</option>
                        <option value="36px">36</option>
                        <option value="40px">40</option>
                    </select>
                </div>

                <div>
                    <label>Grosor</label>
                    <select wire:model="leyendaWeight3"
                        class="border border-gray-500  rounded px-3 py-1 w-full bg-gray-50 dark:bg-gray-800">
                        <option value="300">Light</option>
                        <option value="400">Normal</option>
                        <option value="500">Medium</option>
                        <option value="600">SemiBold</option>
                        <option value="700">Bold</option>
                        <option value="800">ExtraBold</option>
                    </select>
                </div>
                <div>
                    <label class="font-semibold block">Color:</label>
                    <input type="color" wire:model="leyendaPrincipalColor3"
                        value="{{ \App\Models\Configuracion::get('titulo_color', '#ffffff') }}"
                        class="w-16 h-10 cursor-pointer">
                </div>
            </div>
        </div>
    </div>

    <button wire:click="guardar"
        class="bg-blue-900 mt-2  text-white px-4 py-2 rounded cursor-pointer hover:bg-blue-700">
        Guardar
    </button>

</div>

</div>