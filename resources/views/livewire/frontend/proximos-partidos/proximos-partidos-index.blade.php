<div class="w-full overflow-x-hidden">
    <div class="bg-opacity-0 rounded-2xl shadow-md p-4 sm:p-6 mt-2 w-full max-w-7xl mx-auto">

        {{-- Leyenda general cuando NO hay ningún encuentro --}}
        @if($proximos->count() == 0 && $proximosEliminatorias->count() == 0)
        <div
            class="flex flex-col items-center justify-center min-h-[35vh] text-center text-gray-600 dark:text-gray-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-3 opacity-70" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <p class="text-lg font-semibold">No hay encuentros programados</p>
            <span class="text-sm opacity-70">Vuelve más tarde para ver las próximas fechas.</span>
        </div>
        @endif


        {{-- Título de próximos encuentros (solo si hay partidos) --}}
        @if($proximos->count() > 0)
        <div class="bg-gray-500 rounded-2xl shadow-2xl p-2 mb-6 text-center">
            <span class="text-xl font-semibold text-gray-100">PRÓXIMOS ENCUENTROS</span>
        </div>
        @endif


        {{-- Próximos partidos de fase de grupos --}}
        <div class="space-y-10">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @forelse ($proximos as $partido)
                <div class="bg-gray-100 dark:bg-gray-900 rounded-xl shadow-lg p-5 flex flex-col gap-4 w-full">

                    <div>
                        <h1 class="mb-2 font-titulo">{{ strtoupper($partido->campeonato->nombre) }}</h1>
                        <hr class="my-2">
                        <span class="mt-4 block">Fecha: {{ strtoupper($partido->fecha_encuentro) }}</span>
                    </div>

                    <div class="flex items-center justify-between flex-wrap">
                        <p class="text-base font-bold text-gray-900 dark:text-white uppercase">
                            {{ $partido->equipoLocal->nombre }}
                        </p>
                        <span class="text-xs text-gray-600 dark:text-gray-300 mx-2">VS</span>
                        <p class="text-base font-bold text-gray-900 dark:text-white uppercase">
                            {{ $partido->equipoVisitante->nombre }}
                        </p>
                    </div>

                    <div
                        class="flex items-center justify-between text-sm text-gray-700 dark:text-gray-300 border-t border-gray-300 dark:border-gray-700 pt-3 flex-wrap">
                        <span class="flex items-center gap-1">
                            📅 {{ \Carbon\Carbon::parse($partido->fecha)->format('d/m/Y') }}
                        </span>
                        <span
                            class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full whitespace-nowrap mt-2 sm:mt-0">
                            Próximo partido
                        </span>
                    </div>

                </div>
                @empty
                @endforelse
            </div>
        </div>


        {{-- Próximos partidos eliminatorias --}}
        <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @forelse ($proximosEliminatorias as $partido)
            <div class="bg-gray-100 dark:bg-gray-900 rounded-xl shadow-lg p-5 flex flex-col gap-4 w-full">

                <div>
                    <h1 class="mb-2 font-titulo">{{ strtoupper($partido->campeonato->nombre) }}</h1>
                    <hr class="my-2">
                    <span class="mt-4 block">{{ strtoupper($partido->fase) }}</span>
                </div>

                <div class="flex items-center justify-between flex-wrap">
                    <p class="text-base font-bold text-gray-900 dark:text-white uppercase">
                        {{ $partido->equipoLocal->nombre }}
                    </p>
                    <span class="text-xs text-gray-600 dark:text-gray-300 mx-2">VS</span>
                    <p class="text-base font-bold text-gray-900 dark:text-white uppercase">
                        {{ $partido->equipoVisitante->nombre }}
                    </p>
                </div>

                <div
                    class="flex items-center justify-between text-sm text-gray-700 dark:text-gray-300 border-t border-gray-300 dark:border-gray-700 pt-3 flex-wrap">
                    <span class="flex items-center gap-1">
                        📅 {{ \Carbon\Carbon::parse($partido->fecha)->format('d/m/Y') }}
                    </span>
                    <span
                        class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full whitespace-nowrap mt-2 sm:mt-0">
                        Próximo partido
                    </span>
                </div>

            </div>
            @empty
            @endforelse
        </div>

    </div>
</div>