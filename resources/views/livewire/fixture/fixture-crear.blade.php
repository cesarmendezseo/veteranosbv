<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    @error('general')
    <div class=" bg-red-100 text-red-800 p-2 rounded mb-2">
        {{ $message }}
    </div>
    @enderror

    <div class="bg-blue-900 text-white p-4 shadow-md rounded flex justify-between items-center relative z-10">
        <h2 class=" font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Crear Fixture') }}
        </h2>
        <a href="{{ route('fixture.index') }}"
            class="flex items-center gap-1 hover:underline text-gray-100 dark:text-gray-100">

            {{-- Icono de volver --}}
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>

            {{-- Texto visible solo en pantallas medianas o más grandes --}}
            <span class="hidden sm:inline">Volver</span>
        </a>
    </div>

    <form wire:submit.prevent="guardarEncuentro"
        class="space-y-4 shadow-md sm:rounded-lg bg-gray-100 p-4 dark:bg-gray-800">


        <!-- Si el campeonato tiene grupos, mostrar select de grupos -->
        @if ($grupos)
        <div class="mb-4">
            <h3 class="text-base text-blue-900 font-semibold ">Selecione un Grupo</h3>
            <select id="grupo_id" wire:model.live="grupo_id"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value=""></option>
                @foreach ($grupos as $grupo)
                <option value="{{ $grupo->id }}">{{ strtoupper($grupo->nombre) }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <!-- Mostrar equipos cargados -->

        <hr class="my-4">
        {{-- Botón --}}

        @if ($equipos && count($equipos))
        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 dark:bg-gray-800">
            {{-- Equipo Local --}}
            <div>
                <label for="equipo_local_id" class=" text-base text-blue-900 font-semibold dark:text-gray-100 ]">Equipo
                    Local</label>
                <select id="equipo_local_id" wire:model.live="equipo_local_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
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
                <label for="equipo_visitante_id" class="text-base text-blue-900 font-semibold dark:text-gray-100">Equipo
                    Visitante</label>
                <select id="equipo_visitante_id" wire:model="equipo_visitante_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
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
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            {{-- Hora --}}
            <div class="flex flex-col items-start">
                <!-- Alineación a la izquierda -->
                <label class="block dark:text-gray-100">Hora</label>
                <input type="time" wire:model="hora"
                    class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" />
                @error('hora')
                <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- jornada --}}
            <div class="flex flex-col items-start">
                <label class="block dark:text-gray-100">Jornada</label>
                <input type="number" wire:model="jornada"
                    class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" />
                @error('jornada')
                <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>
            {{-- Fecha --}}
            <div class="flex flex-col">
                <label class="block dark:text-gray-100">Fecha</label>
                <input type="date" wire:model="fecha"
                    class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 sm:text-sm  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                    placeholder="dd/mm/aaaa" />
                @error('fecha')
                <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Cancha --}}
            <div class="flex flex-col">
                <label class="block dark:text-gray-100">Cancha</label>
                <select wire:model="cancha_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
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
                <label class="block dark:text-gray-100">Estado</label>
                <select wire:model="estado"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value=" programado">Programado</option>
                    <option value="jugado">Jugado</option>
                    <option value="pendiente">Pendiente</option>
                </select>
                @error('estado')
                <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <hr>
        {{-- Botón --}}
        <button type="submit"
            class="cursor-pointer inline-flex items-center gap-2 mt-4  bg-blue-950 hover:bg-blue-800 text-white px-4 py-2 rounded ">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-save-icon lucide-save">
                <path
                    d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
                <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7" />
                <path d="M7 3v4a1 1 0 0 0 1 1h7" />
            </svg> <span>Guardar</span></button>

    </form>
    {{-- ================= ENCUENTROS ================= --}}
    <div class="mt-6">

        <div class="bg-blue-900 text-white p-2 shadow-md rounded flex justify-between items-center relative z-10">
            <h3 class="text-lg font-semibold mb-2">Encuentros registrados</h3>
        </div>

        @foreach ($this->encuentros as $encuentro)
        <div class="hidden lg:flex items-center justify-between border-b py-2 text-gray-900 dark:text-gray-100">
            <span class="flex-1 pl-2">{{ ucwords($encuentro->fase) }}</span>

            <span class="flex-1 text-right pr-2">{{ $encuentro->equipoLocal->nombre }}</span>

            <span class="font-bold rounded-4xl bg-white text-gray-900 p-2">vs</span>

            <span class="flex-1 pl-2">{{ $encuentro->equipoVisitante->nombre }}</span>

            <span class="flex-1 text-sm">
                {{ \Carbon\Carbon::parse($encuentro->fecha . ' ' . $encuentro->hora)->format('d/m/Y H:i') }}hs
            </span>

            <span class="flex-1 text-sm">
                {{ $encuentro->cancha->nombre ?? "Sin cancha asignada" }}
            </span>
        </div>
        @endforeach
    </div>
</div>