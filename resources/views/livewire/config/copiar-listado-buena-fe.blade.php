<div class="p-6 bg-white shadow rounded dark:bg-gray-900">
    <div class="bg-blue-900 text-white p-4 shadow-md rounded flex justify-between items-center relative z-10"">
        <h2 class=" font-semibold text-xl text-gray-100 leading-tight">
        {{ __('Clonar listado de buena fe') }}
        </h2>
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <a href="{{ route('altas-bajas.index') }}" class="cursor-pointer hover:underline">
                <span class="hidden sm:inline">Volver</span>
            </a>
        </div>
    </div>



    {{-- ////////////////////// --}}
    <div class=" bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 border border-gray-200 dark:border-gray-700">

        <div>
            <label class=" dark:tetx-gray-900">Campeonato origen</label>
            <select wire:model.live="campeonato_id"
                class="border bg-gray-50 shadow-lg border-gray-200 rounded p-2 w-full mt-2 dark:bg-gray-900 ">
                <option value="">Elija campeonato</option>
                @foreach (\App\Models\Campeonato::all() as $camp)
                <option value="{{ $camp->id }}">{{ $camp->nombre }}</option>
                @endforeach
            </select>
        </div>

        @if($equiposOrigen)
        <div>
            <label class="dark:text-gray-900">Seleccione equipos a copiar</label>
            <div
                class="grid grid-cols-2 md:grid-cols-3 gap-2 mt-2 border bg-gray-50 shadow-lg border-gray-200 rounded p-2 w-full  dark:bg-gray-900">
                @foreach($equiposOrigen as $equipo)
                <label class="flex items-center space-x-2 text-sm">
                    <input type="checkbox" wire:model="equiposSeleccionados" value="{{ $equipo->id }}">
                    <span>{{ $equipo->nombre }}</span>
                </label>
                @endforeach
            </div>
        </div>
        @endif
        <hr class="m-4">
        <div class="mt-6">
            <label>Campeonato destino</label>
            <select wire:model="campeonato_id2"
                class="border bg-gray-50 shadow-lg border-gray-200 rounded p-2 w-full mt-2 dark:bg-gray-900 ">
                <option value="">Seleccione Campeonato</option>
                @foreach (\App\Models\Campeonato::all() as $camp)
                <option value="{{ $camp->id }}">{{ $camp->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="text-center mt-4">
            <button wire:click="duplicarListado" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Copiar jugadores
            </button>
        </div>
    </div>

</div>