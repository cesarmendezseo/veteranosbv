<div>
    <div class="space-y-4">

        @if (session('ok'))
        <div class="p-3 bg-green-100 text-green-700 rounded">
            {{ session('ok') }}
        </div>
        @endif

        <h2 class="text-xl font-bold">Configuración de Inicio</h2>

        <div>
            <label class="font-semibold">Título principal</label>
            <input type="text" class="border w-full p-2" wire:model="tituloPrincipal">
        </div>

        <div>
            <label class="font-semibold">Campeonato principal</label>
            <select wire:model="campeonatoPrincipal" class="border p-2 w-full">
                <option value="">-- Seleccionar --</option>

                @foreach(\App\Models\Campeonato::all() as $camp)
                <option value="{{ $camp->id }}">{{ $camp->nombre }}</option>
                @endforeach
            </select>
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
        <div class="mb-4">
            <label for="titulo" class="block text-sm font-medium text-gray-700">Título de la página</label>
            <input type="text" id="titulo" wire:model="titulo"
                class="mt-1 p-4 bg-gray-200 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        </div>

        <button wire:click="guardar" class="bg-blue-900  text-white px-4 py-2 rounded cursor-pointer hover:bg-blue-700">
            Guardar
        </button>
        <a href="{{ route('cargar.logo') }}"
            class="bg-blue-900  text-white px-4 py-2 rounded cursor-pointer hover:bg-blue-700">
            Cargar Logo
        </a>
    </div>

</div>