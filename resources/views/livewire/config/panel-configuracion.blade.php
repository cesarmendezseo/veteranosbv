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


        <form class="">
            <label for="countries" class="block mb-2.5 text-sm font-medium text-heading"><span
                    class=" font-titulo text-lg">CAMPEONATO PRINCIPAL</span>
                <p class="text-xs">(Seleccione el campeonato que quiere que
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
        <div class="mb-4">

            <div>
                <label for="first_name" class="block mb-2.5 text-sm font-titulo text-heading">TÍTULO DE LA
                    PÁGINA</label>
                <input type="text" wire:model="titulo" id="first_name"
                    class="bg-gray-100 dark:bg-gray-800 border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
                    placeholder="John" required />
            </div>

        </div>
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



        <button wire:click="guardar" class="bg-blue-900  text-white px-4 py-2 rounded cursor-pointer hover:bg-blue-700">
            Guardar
        </button>

    </div>

</div>