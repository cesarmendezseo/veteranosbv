<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    @error('general')
    <div class="bg-red-100 text-red-800 p-2 rounded mb-2">
        {{ $message }}
    </div>
    @enderror

    {{-- CABECERA PRINCIPAL --}}
    <div class="bg-blue-900 text-white p-4 shadow-md rounded flex justify-between items-center relative z-10">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Crear Fixture') }}
        </h2>

        <div class="flex items-center gap-4">
            {{-- BOTÓN DE DESIGNACIÓN (Solo aparece en fase regular si está terminada) --}}
            @php
            $faseRegularFinalizada = $campeonato->fases()->where('orden', 1)->where('estado', 'finalizada')->exists();
            $partidosLiguillaGenerados = \App\Models\Encuentro::where('campeonato_id', $campeonato_id)
            ->whereIn('fase', ['superior', 'inferior'])->exists();
            @endphp

            @if($campeonato && $faseRegularFinalizada && !$partidosLiguillaGenerados)
            <button wire:click="confirmarCierreFase"
                class="cursor-pointer bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded shadow-lg flex items-center gap-2 text-sm transition-all">
                <i class="fas fa-magic"></i>
                Generar Cruces Oro/Plata
            </button>
            @endif

            <a href="{{ route('fixture.index') }}" class="flex items-center gap-1 hover:underline text-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span class="hidden sm:inline">Volver</span>
            </a>
        </div>
    </div>

    {{-- FORMULARIO DE CARGA --}}
    <form wire:submit.prevent="guardarEncuentro"
        class="space-y-4 shadow-md sm:rounded-lg bg-gray-100 p-6 dark:bg-gray-800 border-t-4 border-blue-900">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- SELECTOR DE FASE (CRUCIAL PARA EL FILTRADO MANUAL) --}}
            <div class="p-4 bg-white dark:bg-gray-700 rounded-xl border border-blue-100 shadow-sm">
                <label class="block text-sm font-bold text-blue-900 dark:text-blue-300 uppercase mb-2">
                    <i class="fas fa-layer-group mr-1"></i> Fase del Encuentro
                </label>
                <select wire:model.live="fase_seleccionada"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                    @foreach($fases_campeonato as $f)
                    <option value="{{ $f->id }}">{{ strtoupper($f->nombre) }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-2 italic">Selecciona la fase para filtrar los equipos disponibles.
                </p>
            </div>

            {{-- SELECTOR DE GRUPO (Solo si la fase es regular y hay grupos) --}}
            @if ($grupos && count($grupos) > 0)
            <div class="p-4 bg-white dark:bg-gray-700 rounded-xl border border-gray-100 shadow-sm">
                <label class="block text-sm font-bold text-blue-900 dark:text-blue-300 uppercase mb-2">
                    <i class="fas fa-users mr-1"></i> Seleccione un Grupo
                </label>
                <select id="grupo_id" wire:model.live="grupo_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:text-white">
                    <option value="">Todos los grupos</option>
                    @foreach ($grupos as $grupo)
                    <option value="{{ $grupo->id }}">{{ strtoupper($grupo->nombre) }}</option>
                    @endforeach
                </select>
            </div>
            @endif
        </div>

        <hr class="my-4 border-gray-200 dark:border-gray-600">

        {{-- SELECTORES DE EQUIPOS --}}
        @if ($equipos && count($equipos))
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
            <div>
                <label for="equipo_local_id" class="text-base text-blue-900 font-semibold dark:text-gray-100">Equipo
                    Local</label>
                <select id="equipo_local_id" wire:model.live="equipo_local_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:text-white">
                    <option value="">-- Seleccionar Equipo --</option>
                    @foreach ($equipos as $equipo)
                    <option value="{{ $equipo->id }}">{{ strtoupper($equipo->nombre)}}</option>
                    @endforeach
                </select>
                @error('equipo_local_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="equipo_visitante_id" class="text-base text-blue-900 font-semibold dark:text-gray-100">Equipo
                    Visitante</label>
                <select id="equipo_visitante_id" wire:model="equipo_visitante_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:text-white">
                    <option value="">-- Seleccionar Equipo --</option>
                    @foreach ($this->equiposVisitantes as $equipo)
                    <option value="{{ $equipo->id }}">{{ strtoupper($equipo->nombre) }}</option>
                    @endforeach
                </select>
                @error('equipo_visitante_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>
        @else
        <div class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300"
            role="alert">
            <span class="font-medium">Atención:</span> No se encontraron equipos para esta fase/grupo. Si es una
            Liguilla, asegúrate de haber designado los clasificados.
        </div>
        @endif

        {{-- DATOS DEL ENCUENTRO --}}
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block dark:text-gray-100 text-sm font-medium">Hora</label>
                <input type="time" wire:model="hora"
                    class="block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white" />
                @error('hora') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block dark:text-gray-100 text-sm font-medium">Jornada / Fecha Nº</label>
                <input type="number" wire:model="jornada"
                    class="block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white" />
                @error('jornada') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block dark:text-gray-100 text-sm font-medium">Fecha</label>
                <input type="date" wire:model="fecha"
                    class="block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white" />
                @error('fecha') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block dark:text-gray-100 text-sm font-medium">Cancha</label>
                <select wire:model="cancha_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:text-white">
                    <option value="">-- Seleccionar --</option>
                    @foreach ($canchas as $cancha)
                    <option value="{{ $cancha->id }}">{{ strtoupper($cancha->nombre) }}</option>
                    @endforeach
                </select>
                @error('cancha_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block dark:text-gray-100 text-sm font-medium">Estado</label>
                <select wire:model="estado"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:text-white">
                    <option value="programado">Programado</option>
                    <option value="jugado">Jugado</option>
                    <option value="pendiente">Pendiente</option>
                </select>
            </div>
        </div>

        <div class="flex justify-start pt-4">
            <button type="submit"
                class="inline-flex items-center gap-2 bg-blue-950 hover:bg-blue-800 text-white font-bold px-6 py-3 rounded-lg shadow transition-colors">
                <i class="fas fa-save"></i>
                GUARDAR ENCUENTRO
            </button>
        </div>
    </form>

    {{-- LISTADO DE ENCUENTROS REGISTRADOS --}}
    <div class="mt-8 mb-10">
        <div class="bg-blue-900 text-white p-3 shadow-md rounded-t flex justify-between items-center">
            <h3 class="text-lg font-semibold uppercase tracking-wider italic">Encuentros registrados en este campeonato
            </h3>
        </div>

        <div class="bg-white dark:bg-gray-800 border shadow-sm">
            @forelse ($encuentros as $encuentro)
            <div
                class="hidden lg:flex items-center justify-between border-b last:border-0 py-4 px-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <div class="w-40">
                    <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-full uppercase">
                        {{ $encuentro->fase }}
                    </span>
                </div>

                <div class="flex-1 flex items-center justify-center gap-4">
                    <span class="text-right font-bold text-gray-800 dark:text-white flex-1">{{
                        $encuentro->equipoLocal->nombre }}</span>
                    <span
                        class="bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-200 px-3 py-1 rounded text-xs font-black italic">VS</span>
                    <span class="text-left font-bold text-gray-800 dark:text-white flex-1">{{
                        $encuentro->equipoVisitante->nombre }}</span>
                </div>

                <div class="w-64 text-right flex flex-col items-end">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">
                        <i class="far fa-calendar-alt mr-1"></i> {{
                        \Carbon\Carbon::parse($encuentro->fecha)->format('d/m/Y') }}
                        <i class="far fa-clock ml-2 mr-1"></i> {{ \Carbon\Carbon::parse($encuentro->hora)->format('H:i')
                        }}hs
                    </span>
                    <span class="text-xs text-gray-400 mt-1">
                        <i class="fas fa-map-marker-alt text-red-400"></i> {{ $encuentro->cancha->nombre ?? "Sin cancha"
                        }} | Jornada {{ $encuentro->fecha_encuentro }}
                    </span>
                </div>
            </div>
            @empty
            <div class="p-10 text-center text-gray-400 italic">
                <i class="fas fa-search mb-2 text-2xl block"></i>
                No hay encuentros registrados para la fase seleccionada.
            </div>
            @endforelse
        </div>
    </div>
</div>