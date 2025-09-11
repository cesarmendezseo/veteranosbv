<div>

     <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Fixture') }}
        </h2>
        <a href="{{ route('fixture.index') }}"
            class="px-3 py-2  text-white rounded flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg> Volver
        </a>


     </x-slot>
    <select wire:model.live="anioSeleccionado" id="countries"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        <option value="">-- Selecciona un año --</option>
        @foreach ($aniosDisponibles as $anio)
        <option value="{{ $anio }}">{{ $anio }}</option>
        @endforeach
    </select>


    @if ($campeonatos)
    <div>
        <select wire:model.live="campeonatoSeleccionado" id="campeonatoSeleccionado"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option value="">-- Selecciona un campeonato --</option>
            @foreach ($campeonatos as $campeonato)
            <option value="{{ $campeonato->id }}">
                {{ $campeonato->nombre }} ({{ $campeonato->created_at->format('d/m/Y') }})
            </option>
            @endforeach
        </select>
    </div>
    @endif

    <!-- Si el campeonato tiene grupos, mostrar select de grupos -->
    @if ($grupos)
    <div class="mb-4">
        <select id="grupo_id" wire:model.live="grupo_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option value="">-- Selecciona un grupo --</option>
            @foreach ($grupos as $grupo)
            <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option>
            @endforeach
        </select>
    </div>
    @endif
    <div>
        @if($botonFixture)
        @if(!$porGrupos)
        <button class="inline-flex items-center gap-2 mt-4  bg-blue-950 hover:bg-blue-800 text-white px-4 py-2 rounded " wire:click="fixtureNormal({{ $campeonato->id }}, false)">General - Normal</button>
        <button class="inline-flex items-center gap-2 mt-4  bg-blue-950 hover:bg-blue-800 text-white px-4 py-2 rounded " wire:click="fixtureAlternancia({{ $campeonato->id }}, false)">General - Alternancia</button>
        <button class="inline-flex items-center gap-2 mt-4  bg-blue-950 hover:bg-blue-800 text-white px-4 py-2 rounded " wire:click="fixtureIdaVuelta({{ $campeonato->id }}, false)">General - Ida/Vuelta</button>
        @else
        <button class="inline-flex items-center gap-2 mt-4  bg-blue-950 hover:bg-blue-800 text-white px-4 py-2 rounded " wire:click="fixtureNormal({{ $campeonato->id }}, true)">Por Grupos - Normal</button>
        <button class="inline-flex items-center gap-2 mt-4  bg-blue-950 hover:bg-blue-800 text-white px-4 py-2 rounded " wire:click="fixtureAlternancia({{ $campeonato->id }}, true)">Por Grupos - Alternancia</button>
        <button class="inline-flex items-center gap-2 mt-4  bg-blue-950 hover:bg-blue-800 text-white px-4 py-2 rounded " wire:click="fixtureIdaVuelta({{ $campeonato->id }}, true)">Por Grupos - Ida/Vuelta</button>
        @endif
        @endif

    </div>

    <button wire:click="borrarTodo" class="inline-flex items-center gap-2 mt-4  bg-blue-950 hover:bg-blue-800 text-white px-4 py-2 rounded ">BORRAR</button>

</div>