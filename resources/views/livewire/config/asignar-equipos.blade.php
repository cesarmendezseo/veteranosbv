<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <x-navbar titulo="Asignar Equipos">
        <a href="{{ route('campeonato.index') }}"
            class=" text-white px-4 py-2 rounded flex items-center gap-2 hover:underline"> <svg
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            Volver</a>

    </x-navbar>

    <flux:separator />

    {{-- Definimos los formatos sin grupos para usarlos en las comprobaciones --}}
    @php
    $formatosSinGrupos = ['todos_contra_todos', 'eliminacion_simple', 'eliminacion_doble'];
    $formatoEliminacion = ['eliminacion_simple', 'eliminacion_doble'];
    @endphp

    {{-- Selector de grupo SOLO si el formato es por grupos --}}
    @if ($campeonato->formato === 'grupos' )
    <div class="mb-4 bg-gray-900 p-4 rounded-lg dark:bg-gray-500">
        <label class="block mb-2 text-xl font-medium text-gray-100 dark:text-white">Elegir Grupo</label>
        <select wire:model.live="grupoSeleccionado"
            class="p-4 block w-full border-gray-100 bg-gray-100 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800">
            <option value="">-- Selecciona un grupo --</option>
            @foreach ($campeonato->grupos as $grupo)
            <option value="{{ $grupo->id }}"> {{ ucwords($grupo->nombre) }}</option>
            @endforeach
        </select>
    </div>
    @endif

    {{-- Selección de equipos: Muestra el selector si NO es por grupos O si SÍ es por grupos y ya seleccionaste uno --}}
    @if (in_array($campeonato->formato, $formatosSinGrupos) || $grupoSeleccionado)
    <div class="mb-4  bg-gray-200  p-4 rounded-lg dark:bg-gray-600">
        <label class="block mb-2 text-basic font-medium bg-gray-500 text-gray-100 dark:text-white dark:bg-gray-900 p-4">
            Seleccionar equipos
            {{ $campeonato->formato === 'grupos' ? 'para el grupo' : 'para el campeonato' }}
            <span> (Puedes seleccionar múltiples equipos)</span>
        </label>
        <select wire:model="equiposSeleccionados" multiple
            class="block cursor-pointer w-full border border-gray-400  bg-gray-100  dark:text-gray-900 p-2 dark:border-gray-100 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 "
            size="8">
            @foreach ($equiposDisponibles as $equipo)
            <option value="{{ $equipo->id }}">{{ ucwords($equipo->nombre) }}</option>
            @endforeach
        </select>
    </div>

    {{-- Botón de Asignar --}}
    @adminOrCan('comision')
    <button wire:click="asignarEquiposAGrupo"
        class=" cursor-pointer mb-4 px-5 py-2.5 gap-4 text-sm font-medium text-white inline-flex items-center bg-blue-950 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 shadow">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-badge-plus-icon lucide-badge-plus">
            <path
                d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z" />
            <line x1="12" x2="12" y1="8" y2="16" />
            <line x1="8" x2="16" y1="12" y2="12" />
        </svg>
        Asignar equipos
    </button>
    @endadminOrCan
    @endif

    <flux:separator />

    {{-- Mostrar lista de grupos con sus equipos --}}
    @if ($campeonato->formato === 'grupos')
    <div class="mt-6">
        <h2 class="block mb-2 text-lg font-medium text-gray-100 dark:text-white">Grupos y Equipos</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 ">
            @foreach ($campeonato->grupos as $grupo)
            <div class="p-4 border rounded-lg dark:bg-gray-600 shadow-yellow-100 ">
                <h3 class="font-bold mb-2 "> {{ ucwords($grupo->nombre) }}</h3>
                <flux:separator />
                <ul class="list-none space-y-1">
                    @forelse ($grupo->equipos as $equipo)
                    <li>
                        {{ $loop->iteration }}. {{ ucwords($equipo->nombre) }}
                        <button type="button" wire:click="removerEquipoDeGrupo({{ $equipo->id }}, {{ $grupo->id }})"
                            class="text-red-600 hover:underline text-sm ml-2 cursor-pointer dark:bg-white rounded-4xl dark:p-1 dark:shadow-2xl bg-gray-200  p-1 shadow-lg">
                            {{-- Icono de eliminar --}}
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-5 w-5 shasow-lg">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </button>
                    </li>
                    @empty
                    <li>No hay equipos en este grupo</li>
                    @endforelse
                </ul>


            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Mostrar equipos asignados directamente al campeonato (Todos contra todos Y Eliminación) --}}
    @if (in_array($campeonato->formato, $formatosSinGrupos))
    <div class="mt-2">
        <h2 class="block mb-2 text-lg bg-gray-500 p-4 text-gray-100 font-medium  dark:text-white">
            Equipos del Campeonato ({{ $campeonato->formato === 'todos_contra_todos' ? 'Todos contra todos' :
            'Eliminación' }})
        </h2>
        <div class="p-4 border rounded-lg bg-gray-300 dark:bg-gray-600  ">
            <ul class="list-none space-y-1 text-gray-100 dark:text-gray-100">
                {{-- Filtramos los equipos que no tienen grupo asignado (grupo_id es null) --}}
                @forelse ($campeonato->equipos->where('pivot.grupo_id', null) as $equipo)
                <li class="bg-gray-900 p-2 rounded flex justify-between items-center dark:bg-gray-800">
                    {{ $loop->iteration }}. - {{ ucwords($equipo->nombre) }}

                    @adminOrCan('comision')
                    {{-- Pasamos 'null' como segundo argumento para indicar que no tiene grupo --}}
                    <button type=" button" wire:click="removerEquipoDeGrupo({{ $equipo->id }}, null)"
                        class="text-red-600 hover:underline text-sm ml-2 cursor-pointer dark:bg-white rounded-4xl dark:p-1 dark:shadow-2xl bg-gray-200  p-1 shadow-lg">
                        {{-- Icono de eliminar --}}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-5 w-5 shasow-lg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                    </button>
                    @endadminOrCan
                </li>
                @empty
                <li class="text-sm text-gray-500">Sin equipos aún</li>
                @endforelse
            </ul>
        </div>


    </div>
    @endif
    <a href="{{route('campeonato.index')}}"
        class="cursor-pointer inline-flex items-center gap-2 mt-4  bg-blue-950 hover:bg-blue-800 text-white px-4 py-2 rounded dark:bg-blue-900">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <span>Volver</span>
    </a>

    @push('js')
    <script>
        document.addEventListener('livewire:initialized', () => {

            Livewire.on('selecionarEquipos', () => {
                Swal.fire(
                    '¡Atención!',
                    'Debe seleccionar al menos un equipo.',
                    'info'
                );
            });


            Livewire.on('equiposAsignados', () => {
                Swal.fire(
                    '¡Éxito!',
                    'El equipo ha sido asignado correctamente.',
                    'success'
                );
            });

            Livewire.on('errorEquipoAsignado', (grupoNombre) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error al asignar equipo',
                    // Corregida la interpolación de la variable para el mensaje de SweetAlert
                    text: `El ${grupoNombre} ya tiene el máximo de equipos permitidos.`,
                });
            });
        });
    </script>
    @endpush
</div>