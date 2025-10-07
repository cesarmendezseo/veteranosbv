<div class="p-4">
    <div class="p-6 space-y-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
            Fase actual: <span class="bg-gray-500 p-2 rounded-2xl">{{ ucfirst($fase_actual) }}</span>
        </h2>

        {{-- ================= CREAR ENCUENTRO ================= --}}
        <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow">


            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                <div>
                    <label class="text-sm font-medium">Equipo Local</label>
                    <select wire:model="nuevo_local"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value=""></option>
                        @foreach($equiposDisponibles as $equipo)
                        <option value="{{ $equipo->id }}">{{ $equipo->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium">Equipo Visitante</label>
                    <select wire:model="nuevo_visitante"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value=""></option>
                        @foreach($equiposDisponibles as $equipo)
                        <option value="{{ $equipo->id }}">{{ $equipo->nombre }}</option>
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
                        <option value="{{ $cancha->id }}">{{ $cancha->nombre }}</option>
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

        {{-- ================= ENCUENTROS ================= --}}
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-2">Encuentros registrados</h3>

            @foreach ($encuentros as $encuentro)
            <div class="flex items-center justify-between border-b py-2 text-gray-900 dark:text-gray-100">
                <span class="flex-1 text-right pr-2">{{ $encuentro->equipoLocal->nombre }} </span>



                <span class="font-bold rounded-4xl bg-white text-gray-900 p-2">vs </span>


                <span class="flex-1 pl-2"> {{ $encuentro->equipoVisitante->nombre }}</span>

                <span class="flex-1  text-sm text-gray-200">
                    {{ \Carbon\Carbon::parse($encuentro->fecha . ' ' . $encuentro->hora)->format('d/m/Y H:i') }}hs
                </span>
                <span class=" flex-1 text-sm text-gray-200">
                    {{ $encuentro->canchas->nombre }}
                </span>

            </div>
            @endforeach
        </div>
    </div>

</div>