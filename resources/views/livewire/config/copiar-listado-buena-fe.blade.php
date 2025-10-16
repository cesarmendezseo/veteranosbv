<div class="p-6 bg-white shadow rounded">
    <div class="bg-blue-900 text-white p-4 shadow-md rounded flex justify-between items-center relative z-10"">
        <h2 class=" font-semibold text-xl text-gray-100 leading-tight">
        {{ __('Clonar listado de buena fe') }}
        </h2>
        <div>
            <a href="{{ route('altas-bajas.index') }}" class="cursor-pointer hover:underline">Volver</a>
        </div>
    </div>



    {{-- ////////////////////// --}}
    <div class="space-y-4 p-4 bg-gray-900">

        <div>
            <label class="dark:tetx-gray-900">Campeonato origen</label>
            <select wire:model.live="campeonato_id" class="border rounded p-2 w-full  dark:bg-gray-900 ">
                <option value="">-- Seleccione --</option>
                @foreach (\App\Models\Campeonato::all() as $camp)
                <option value="{{ $camp->id }}">{{ $camp->nombre }}</option>
                @endforeach
            </select>
        </div>

        @if($equiposOrigen)
        <div>
            <label class="dark:text-gray-900">Seleccione equipos a copiar</label>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mt-2">
                @foreach($equiposOrigen as $equipo)
                <label class="flex items-center space-x-2">
                    <input type="checkbox" wire:model="equiposSeleccionados" value="{{ $equipo->id }}">
                    <span>{{ $equipo->nombre }}</span>
                </label>
                @endforeach
            </div>
        </div>
        @endif

        <div class="mt-4 bg-gray-500 p-2 rounded-2xl">
            <label>Campeonato destino</label>
            <select wire:model="campeonato_id2" class="border rounded p-2 w-full  dark:bg-gray-900 ">
                <option value="">-- Seleccione --</option>
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