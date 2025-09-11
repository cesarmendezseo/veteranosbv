<div>
    @error('general')
    <div class="bg-red-100 text-red-800 p-2 rounded mb-2">
        {{ $message }}
    </div>
    @enderror

     <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Crear Fixture') }}
        </h2>
        <div class="flex items-center space-x-4">
        <a href="{{route('fixture.index')}}" class="flex items-center gap-1 hover:underline text-gray-100 dark:text-gray-100">
            {{-- Icono de volver --}}
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            Volver
        </a>
        </div>
     </x-slot>
    <form wire:submit.prevent="guardarEncuentro" class="space-y-4 shadow-md sm:rounded-lg bg-gray-100 p-4">

        {{-- Título --}}
        <div class="mb-4 flex items-center justify-center">

        </div>
        <flux:separator class="mb-2" />
        <!-- Si el campeonato tiene grupos, mostrar select de grupos -->
        @if ($grupos)
        <div class="mb-4">
            <h3 class="text-base text-blue-900 font-semibold ">Selecione un Grupo</h3>
            <select id="grupo_id" wire:model.live="grupo_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value=""></option>
                @foreach ($grupos as $grupo)
                <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <!-- Mostrar equipos cargados -->

        <hr class="my-4">
        {{-- Botón --}}

        @if ($equipos && count($equipos))
        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            {{-- Equipo Local --}}
            <div>
                <label for="equipo_local_id"
                    class=" text-base text-blue-900 font-semibold dark:text-gray-800]">Equipo
                    Local</label>
                <select id="equipo_local_id" wire:model.live="equipo_local_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value=""></option>
                    @foreach ($equipos as $equipo)
                    <option value="{{ $equipo->id }}">{{ strtoupper($equipo->nombre)}}</option>
                    @endforeach
                </select>
                @error('equipo_local_id')
                <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Equipo Visitante (filtrado) --}}
            <div>
                <label for="equipo_visitante_id"
                    class="text-base text-blue-900 font-semibold dark:text-gray-800">Equipo
                    Visitante</label>
                <select id="equipo_visitante_id" wire:model="equipo_visitante_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value=""> </option>
                    @foreach ($this->equiposVisitantes as $equipo)
                    <option value="{{ $equipo->id }}">{{ strtoupper($equipo->nombre) }}</option>
                    @endforeach
                </select>
                @error('equipo_visitante_id')
                <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>
        {{-- --}}
        @endif
        <div class="hidden md:grid grid-cols-5 gap-4">
            {{-- Hora --}}
            <div class="flex flex-col items-start"> <!-- Alineación a la izquierda -->
                <label class="block dark:text-gray-800">Hora</label>
                <input type="time" wire:model="hora" class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 sm:text-sm" />
                @error('hora')
                <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- jornada --}}
            <div class="flex flex-col items-start">
                <label class="block dark:text-gray-800">Jornada</label>
                <input type="number" wire:model="jornada" class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 sm:text-sm" />
                @error('jornada')
                <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>
            {{-- Fecha --}}
            <div class="flex flex-col">
                <label class="block dark:text-gray-800">Fecha</label>
                <input type="date" wire:model="fecha" class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 sm:text-sm" placeholder="dd/mm/aaaa" />
                @error('fecha')
                <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Cancha --}}
            <div class="flex flex-col">
                <label class="block dark:text-gray-800">Cancha</label>
                <select wire:model="cancha_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="">-- Selecciona una cancha --</option>
                    @foreach ($canchas as $cancha)
                    <option value=" {{ $cancha->id }}">{{ strtoupper($cancha->nombre) }}</option>
                    @endforeach
                </select>
                @error('cancha_id')
                <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Estado --}}
            <div class="flex flex-col">
                <label class="block dark:text-gray-800">Estado</label>
                <select wire:model="estado" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value=" programado">Programado</option>
                    <option value="jugado">Jugado</option>
                    <option value="pendiente">Pendiente</option>
                </select>
                @error('estado')
                <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- ============MOVIL=========================================== --}}

        <div class="block md:hidden space-y-4 dark:bg-gray-600 rounded shadow p-4">
            <!-- Primera fila: Hora y Jornada en 2 columnas (siempre) -->
            <div class="grid grid-cols-2 gap-4">
                {{-- Hora --}}
                <div class="flex flex-col">
                    <label class="block dark:text-gray-100 ">Hora</label>
                    <input type="time" wire:model="hora" class="bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-500 dark:border-gray-600 dark:placeholder-gray-800 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    @error('hora')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Jornada --}}
                <div class="flex flex-col">
                    <label class="block dark:text-gray-100">Jornada</label>
                    <input type="number" wire:model="jornada" class="bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-500 dark:border-gray-600 dark:placeholder-gray-800 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    @error('jornada')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Fecha (ocupa todo el ancho en móvil) -->
            <div class="grid grid-cols-2 gap-4 ">
                <div class="flex flex-col">
                    <label class="block dark:text-gray-100">Fecha</label>
                    <input type="date" wire:model="fecha" class="bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-500 dark:border-gray-600 dark:placeholder-gray-800 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="dd/mm/aaaa" />
                    @error('fecha')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Cancha y Estado: 1 columna en móvil, 2 en desktop -->
                <div class="flex flex-col">
                    {{-- Cancha --}}
                    <div class="flex flex-col">
                        <label class="block dark:text-gray-100">Cancha</label>
                        <select wire:model="cancha_id" class="bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-40 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value=""> </option>
                            @foreach ($canchas as $cancha)
                            <option value="{{ $cancha->id }}">{{ strtoupper($cancha->nombre) }}</option>
                            @endforeach
                        </select>
                        @error('cancha_id')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                {{-- Estado --}}
                <div class="flex flex-col">
                    <label class="block dark:text-gray-100">Estado</label>
                    <select wire:model="estado" class="bg-gray-50 border border-gray-500 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-40 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="programado">Programado</option>
                        <option value="jugado">Jugado</option>
                        <option value="pendiente">Pendiente</option>
                    </select>
                    @error('estado')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <hr>
        {{-- Botón --}}
        <button type="submit" class="inline-flex items-center gap-2 mt-4  bg-blue-950 hover:bg-blue-800 text-white px-4 py-2 rounded ">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-save-icon lucide-save">
                <path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
                <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7" />
                <path d="M7 3v4a1 1 0 0 0 1 1h7" />
            </svg> <span>Guardar</span></button>

    </form>
    @if($campeonatos)

    @endif
</div>