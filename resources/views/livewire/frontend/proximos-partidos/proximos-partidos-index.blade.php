<div class="w-full overflow-x-hidden">
    <div class="bg-opacity-0 rounded-2xl shadow-md p-4 sm:p-6 mt-2 w-full max-w-7xl mx-auto">
        <div class="bg-gray-500 rounded-2xl shadow-2xl p-2 mb-6">
            <span class="text-xl font-semibold text-gray-100">PRÓXIMOS ENCUENTROS</span>
        </div>

        <div class="space-y-10">
            {{-- Próximos partidos --}}
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
                <p class="text-gray-500 text-sm col-span-full text-center">
                    No hay próximos partidos programados.
                </p>
                @endforelse
            </div>

            {{-- Próximos partidos eliminatorias --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
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
                <p class="text-gray-500 text-sm col-span-full text-center">
                    No hay próximos partidos programados.
                </p>
                @endforelse
            </div>
        </div>
    </div>
</div>