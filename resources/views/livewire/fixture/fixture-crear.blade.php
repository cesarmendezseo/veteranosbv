<div>
    @error('general')
    <div class="bg-red-100 text-red-800 p-2 rounded mb-2">
        {{ $message }}
    </div>
    @enderror

    <form wire:submit.prevent="guardarEncuentro" class="space-y-4 shadow-md sm:rounded-lg bg-gray-100 p-4">

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

        <!-- Mostrar equipos cargados -->

        <hr class="my-4">
        {{-- Botón --}}

        @if ($equipos && count($equipos))
        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            {{-- Equipo Local --}}
            <div>
                <label for="equipo_local_id"
                    class="block text-sm font-medium text-gray-700 dark:text-[#ebee2d]">Equipo
                    Local</label>
                <select id="equipo_local_id" wire:model.live="equipo_local_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="">-- Selecciona un equipo --</option>
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
                    class="block text-sm font-medium text-gray-700 dark:text-[#ebee2d]">Equipo
                    Visitante</label>
                <select id="equipo_visitante_id" wire:model="equipo_visitante_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="">-- Selecciona un equipo --</option>
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
                <label class="block dark:text-[#ebee2d]">Hora</label>
                <input type="time" wire:model="hora" class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 sm:text-sm" />
                @error('hora')
                <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- jornada --}}
            <div class="flex flex-col items-start">
                <label class="block dark:text-[#ebee2d]">Jornada</label>
                <input type="number" wire:model="jornada" class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 sm:text-sm" />
                @error('jornada')
                <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>
            {{-- Fecha --}}
            <div class="flex flex-col">
                <label class="block dark:text-[#ebee2d]">Fecha</label>
                <input type="date" wire:model="fecha" class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 sm:text-sm" placeholder="dd/mm/aaaa" />
                @error('fecha')
                <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            {{-- Cancha --}}
            <div class="flex flex-col">
                <label class="block dark:text-[#ebee2d]">Cancha</label>
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
                <label class="block dark:text-[#ebee2d]">Estado</label>
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

        <div class="block md:hidden space-y-4">
            <!-- Primera fila: Hora y Jornada en 2 columnas (siempre) -->
            <div class="grid grid-cols-2 gap-4">
                {{-- Hora --}}
                <div class="flex flex-col">
                    <label class="block dark:text-[#ebee2d] ">Hora</label>
                    <input type="time" wire:model="hora" class="w-full input-chico" />
                    @error('hora')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Jornada --}}
                <div class="flex flex-col">
                    <label class="block dark:text-[#ebee2d]">Jornada</label>
                    <input type="number" wire:model="jornada" class="w-full input-chico" />
                    @error('jornada')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Fecha (ocupa todo el ancho en móvil) -->
            <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col">
                    <label class="block dark:text-[#ebee2d]">Fecha</label>
                    <input type="date" wire:model="fecha" class="w-full fecha" placeholder="dd/mm/aaaa" />
                    @error('fecha')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Cancha y Estado: 1 columna en móvil, 2 en desktop -->
                <div class="flex flex-col">
                    {{-- Cancha --}}
                    <div class="flex flex-col">
                        <label class="block dark:text-[#ebee2d]">Cancha</label>
                        <select wire:model="cancha_id" class="w-full select">
                            <option value="">-- Selecciona una cancha --</option>
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
                    <label class="block dark:text-[#ebee2d]">Estado</label>
                    <select wire:model="estado" class="w-full select">
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
        <button type="submit"
            class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">Guardar
            Encuentro</button>
    </form>

</div>