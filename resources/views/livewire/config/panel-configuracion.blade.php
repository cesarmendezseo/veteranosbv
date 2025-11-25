<div>
    <div class="space-y-4 bg-gray-50 dark:bg-gray-900 p-6 rounded shadow-md">

        @if (session('ok'))
        <div class="p-3 bg-green-100 text-green-700 rounded">
            {{ session('ok') }}
        </div>
        @endif

        <h2 class="text-xl font-bold">Configuración de Inicio</h2>

        <div>
            <label class="font-semibold font-titulo">TÍTULO PRINCIPAL</label>
            <input type="text" class="bg-gray-100 dark:bg-gray-800 border w-full p-2" wire:model="tituloPrincipal">
        </div>

        {{-- *********************campeoanto principal********************* --}}
        <form class="">
            <label for="countries" class="block mb-2.5 text-sm font-medium text-heading"><span
                    class=" font-titulo text-lg">CAMPEONATO PRINCIPAL</span>
                <p class="text-xs text-gray-400">(Seleccione el campeonato que quiere que
                    aparezca
                    en la pagina principal)</p>
            </label>
            <select id="countries" wire:model="campeonatoPrincipal"
                class="bg-gray-100 block w-full px-3 py-2.5 bg-neutral-secondary-medium border dark:bg-gray-800 border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body">
                <option value="">-- Seleccionar --</option>
                @foreach(\App\Models\Campeonato::all() as $camp)
                <option value="{{ $camp->id }}">{{strtoupper( $camp->nombre) }}</option>
                @endforeach
            </select>

        </form>
        {{-- ******************************************************* --}}
        {{-- *********************TITULO DE LA PAGINA****************** --}}
        <div class="mb-4">

            <div>
                <label for="first_name" class="block mb-2.5 text-sm font-titulo text-heading">TÍTULO DE LA
                    PÁGINA</label>
                <input type="text" wire:model="titulo" id="first_name"
                    class="bg-gray-100 dark:bg-gray-800 border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
                    placeholder="John" required />
            </div>

        </div>
        {{-- ******************************************************* --}}
        {{-- -------------------LEYENDAS--------------------------- --}}
        <div class="mb-4 ">
            <label for="message" class="block mb-2.5 text-sm font-titulo text-heading">Leyenda de la página
                principal 1</label>
            <textarea id="message" rows="4" wire:model="leyendaPrincipal1"
                class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-3.5 shadow-xs placeholder:text-body"
                placeholder="Escribe aqui lo que quieras que aparezca en la pagina princial..."></textarea>
        </div>
        <div class="mb-4">
            <label for="message" class="block mb-2.5 text-sm font-titulo text-heading">Leyenda de la página
                principal 2</label>
            <textarea id="message" rows="4" wire:model="leyendaPrincipal2"
                class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-3.5 shadow-xs placeholder:text-body"
                placeholder="Escribe aqui lo que quieras que aparezca en la pagina princial..."></textarea>
        </div>
        <div class="mb-4">
            <label for="message" class="block mb-2.5 text-sm font-titulo text-heading">Leyenda de la página
                principal 3</label>
            <textarea id="message" rows="4" wire:model="leyendaPrincipal3"
                class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full p-3.5 shadow-xs placeholder:text-body"
                placeholder="Escribe aqui lo que quieras que aparezca en la pagina princial..."></textarea>
        </div>
        {{-- --------------------------------------------------------------------------- --}}
        <div class="mb-4">
            <div>
                <label for="first_name" class="block mb-2.5 text-sm font-titulo text-heading">FOOTER DE LA
                    PÁGINA</label>
                <input type="text" wire:model="textoFooter" id="first_name"
                    class="bg-gray-100 dark:bg-gray-800 border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
                    placeholder="" required />
            </div>

        </div>
        <div class="mb-4">
            <a href="{{ route('cargar.logo') }}"
                class="bg-blue-500  text-white px-4 py-3 rounded cursor-pointer hover:bg-blue-700">
                Cargar Logo
            </a>
        </div>
        <div class="mb-4">
            <a href="{{ route('cargar.imagenPrincipal') }}"
                class="bg-blue-500  text-white px-4 py-3 rounded cursor-pointer hover:bg-blue-700">
                Cargar Imagen Principal
            </a>
        </div>

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

        <hr>
        {{-- ************CONFIGURACION DE FUENTES Y TAMAÑOS***************** --}}
        <div class="space-y-3 bg-gray-600 dark:bg-gray-600 p-4 rounded">
            <h5>Titulo</h5>
            <div class="grid grid-cols-4 gap-4">
                <div><label class="font-semibold block">Fuente:</label>
                    <select wire:model="tituloFuente" class="border rounded px-3 py-1 w-full dark:bg-gray-800">
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
                    <select wire:model="tituloSize" class="border rounded px-3 py-1 w-full dark:bg-gray-800">
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
                    <select wire:model="tituloWeight" class="border rounded px-3 py-1 w-full dark:bg-gray-800">
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

        <div class="space-y-3 bg-gray-600 dark:bg-gray-600 p-4 rounded">
            <H5>LEYENDA 1</H5>
            <div class="grid grid-cols-4 gap-4">
                <div><label class="font-semibold block">Tipografia:</label>
                    <select wire:model="leyendaPrincipalFuente1"
                        class="border rounded px-3 py-1 w-full dark:bg-gray-800">
                        @foreach ($fuentes_disponibles as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Tamaño</label>

                    <select wire:model="leyendaSize1" class="border rounded px-3 py-1 w-full dark:bg-gray-800">
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
                    <select wire:model="leyendaWeight1" class="border rounded px-3 py-1 w-full dark:bg-gray-800">
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
        <div class="space-y-3 bg-gray-600 dark:bg-gray-600 p-4 rounded">
            <H5>LEYENDA 2</H5>
            <div class="grid grid-cols-4 gap-4">
                <div><label class="font-semibold block">Tipografia:</label>
                    <select wire:model="leyendaPrincipalFuente2"
                        class="border rounded px-3 py-1 w-full dark:bg-gray-800">
                        @foreach ($fuentes_disponibles as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Tamaño</label>

                    <select wire:model="leyendaSize2" class="border rounded px-3 py-1 w-full dark:bg-gray-800">
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
                    <select wire:model="leyendaWeight2" class="border rounded px-3 py-1 w-full dark:bg-gray-800">
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
        <div class="space-y-3 bg-gray-600 dark:bg-gray-600 p-4 rounded">
            <H5>LEYENDA 3</H5>
            <div class="grid grid-cols-4 gap-4">
                <div><label class="font-semibold block">Tipografia:</label>
                    <select wire:model="leyendaPrincipalFuente3"
                        class="border rounded px-3 py-1 w-full dark:bg-gray-800">
                        @foreach ($fuentes_disponibles as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Tamaño</label>

                    <select wire:model="leyendaSize3" class="border rounded px-3 py-1 w-full dark:bg-gray-800">
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
                    <select wire:model="leyendaWeight3" class="border rounded px-3 py-1 w-full dark:bg-gray-800">
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

    <button wire:click="guardar" class="bg-blue-900  text-white px-4 py-2 rounded cursor-pointer hover:bg-blue-700">
        Guardar
    </button>

</div>

</div>