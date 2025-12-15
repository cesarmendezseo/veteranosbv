<div class="p-4">
    <div class="bg-blue-900 text-white p-2 shadow-md rounded flex justify-between items-center relative z-10">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Fixture Eliminatoria CREAR') }}
        </h2>

        <div class="flex gap-2">
            <a href="{{ route('fixture.index') }}"
                class="md:hidden px-3 py-2 text-white rounded flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </a>

            <div class="hidden md:flex gap-4">
                <a href="{{ route('fixture.index') }}" class="px-3 py-2 text-white rounded flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Volver
                </a>

            </div>
        </div>
    </div>
    <div class="mt-2 ">
        <div class="bg-blue-900 text-white p-4 shadow-md  flex justify-between items-center relative z-10">
            <h2 class="text-2xl font-bold text-gray-100 dark:text-gray-100">
                Fase actual: <span class="bg-gray-500 dark:text-white text-gray-100 p-2 rounded-2xl">{{
                    ucfirst($fase_actual) }}</span>
            </h2>
        </div>

        {{-- ================= EQUIPO LIBRE ================= --}}
        @if ($esCantidadImpar)
        <div class="mb-2 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow">
            <div class="mt-4">
                <label for="equipoLibre">Seleccionar equipo libre:</label>
                <select wire:model.live="equipoLibreId" id="equipoLibre"
                    class="bg-gray-50 border mt-2 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="">Selec Equipo</option>
                    @foreach ($equiposDisponibles as $equipo)
                    <option value="{{ $equipo->id }}">{{strtoupper( $equipo->nombre) }}</option>
                    @endforeach
                </select>
            </div>

            @endif
            @if ($esCantidadImpar && $equipoLibreId)
            <button wire:click="registrarEquipoLibre"
                class="cursor-pointer mt-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Guardar equipo libre
            </button>
            @endif
        </div>
        {{-- ================= CREAR ENCUENTRO ================= --}}
        @if($fase_actual !== 'final')
        <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                <div>
                    <label class="text-sm font-medium">Equipo Local</label>
                    <select wire:model="nuevo_local"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value=""></option>
                        @foreach($equiposDisponibles as $equipo)
                        <option value="{{ $equipo->id }}">{{ strtoupper($equipo->nombre) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium">Equipo Visitante</label>
                    <select wire:model="nuevo_visitante"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value=""></option>
                        @foreach($equiposDisponibles as $equipo)
                        <option value="{{ $equipo->id }}">{{ strtoupper($equipo->nombre) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium">Fecha</label>
                    <input type="date" wire:model="nueva_fecha"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>

                <div>
                    <label class="text-sm font-medium">Hora</label>
                    <input type="time" wire:model="nueva_hora"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>

                <div>
                    <label class="text-sm font-medium">Cancha</label>
                    <select wire:model="nueva_cancha"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value=""></option>
                        @foreach($canchas as $cancha)
                        <option value="{{ $cancha->id }}">{{ strtoupper($cancha->nombre) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <button wire:click="crearEncuentro"
                    class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Guardar encuentro
                </button>
            </div>
        </div>
        @else
        <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow">
            <p class="text-gray-800 dark:text-gray-100">La fase actual es Final, los encuentros se crean
                automaticamente, si quiere editar horario y canchas, los puede realizar en Fixture/ver
            </p>
        </div>
        @endif
        {{-- ================= ENCUENTROS ================= --}}
        <div class="mt-6">

            <div class="bg-blue-900 text-white p-2 shadow-md rounded flex justify-between items-center relative z-10">
                <h3 class="text-lg font-semibold mb-2">Encuentros registrados</h3>
            </div>
            @foreach ($encuentros as $encuentro)

            <div class=" hidden lg:flex items-center justify-between border-b py-2 text-gray-900 dark:text-gray-100">
                <span class="flex-1 pl-2"> {{ ucwords($encuentro->fase) }}</span>

                <span class="flex-1 text-right pr-2">{{ strtoupper($encuentro->equipoLocal->nombre) }} </span>

                <span class="font-bold rounded-4xl bg-white text-gray-900 p-2 dark:text-gray-900">vs </span>


                <span class="flex-1 pl-2"> {{ strtoupper($encuentro->equipoVisitante->nombre) }}</span>

                <span class="flex-1  text-sm text-gray-900 dark:text-gray-100">
                    {{ \Carbon\Carbon::parse($encuentro->fecha . ' ' . $encuentro->hora)->format('d/m/Y H:i') }}hs
                </span>
                <span class=" flex-1 text-sm text-gray-900 dark:text-gray-100">
                    {{ strtoupper($encuentro->canchas->nombre) ?? "Sin cancha asignada" }}
                </span>

            </div>

            {{-- ================MOVIL================ --}}
            <div
                class="lg:hidden bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-4 text-gray-900 dark:text-gray-100">
                <div class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-2">
                    {{ ucwords($encuentro->fase) }}
                </div>
                <hr class="border-gray-300 dark:border-gray-600 mb-2">
                <div class="flex flex-col space-y-1">
                    <div class="flex justify-between items-center">
                        <span class="font-medium">{{ strtoupper($encuentro->equipoLocal->nombre) }}</span>
                        <span
                            class="text-xs font-bold bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 px-2 py-1 rounded-full">vs</span>
                        <span class="font-medium">{{ strtoupper($encuentro->equipoVisitante->nombre) }}</span>
                    </div>
                    <hr class="border-gray-300 dark:border-gray-600 mb-2">
                    <div class="text-sm text-gray-600 dark:text-gray-300">
                        {{ \Carbon\Carbon::parse($encuentro->fecha . ' ' . $encuentro->hora)->format('d/m/Y H:i') }} hs
                    </div>

                    <div class="text-sm text-gray-600 dark:text-gray-300">
                        {{ strtoupper($encuentro->canchas->nombre) ?? "Sin cancha asignada" }}
                    </div>
                </div>
            </div>
            @endforeach



        </div>

    </div>

</div>