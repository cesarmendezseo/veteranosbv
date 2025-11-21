<div class="min-h-screen bg-gray-50 dark:bg-zinc-900">
    <!-- Hero Section -->
    <div class="relative w-full h-[500px] overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center"
            style="background-image: url('{{ asset('images/pagina-principal.jpg') }}')">
        </div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-gray-50 dark:to-zinc-900"></div>
        <div class="relative z-10 flex flex-col items-center justify-center h-full text-center px-4">
            <h1 class="text-4xl md:text-6xl font-extrabold text-white tracking-tight drop-shadow-lg mb-4">
                {{ $tituloPrincipal }}
            </h1>
            {{-- <p class="text-lg md:text-xl text-gray-200 max-w-2xl mx-auto font-light">
                La pasión del fútbol en cada jugada.
            </p> --}}
            <div class="flex items-center justify-center min-h-[40vh]">
                <div
                    class="text-center bg-gradient-to-r from-gray-700 via-gray-800 to-gray-700 text-white rounded-2xl shadow-2xl p-6 mb-8 max-w-3xl mx-auto border border-gray-600">
                    <p class="font-serif italic text-lg md:text-xl leading-relaxed tracking-wide">
                        “Donde las viejas glorias siguen escribiendo nuevas historias.”
                        <br>
                        <span class="font-bold text-amber-400">Fútbol de Veteranos</span> — pasión sin fecha de
                        vencimiento.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 relative z-20 pb-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Left Column: Matches (Takes up 2 columns on large screens) -->
            <div class="lg:col-span-2 space-y-8">
                @if ($mostrarEncuentros)
                <div
                    class="bg-white dark:bg-zinc-800 rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-zinc-700">
                    <div class="p-6 border-b border-gray-100 dark:border-zinc-700 flex items-center justify-between">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Próximos Encuentros
                        </h2>
                    </div>
                    <div class="p-6">
                        @livewire('frontend.proximos-partidos.proximos-partidos-index')
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column: Standings (Takes up 1 column on large screens) -->
            <div class="lg:col-span-1 space-y-8">
                @if ($mostrarTabla && $campeonatoSeleccionado)
                <div
                    class="bg-white dark:bg-zinc-800 rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-zinc-700 sticky top-24">
                    <div class="p-6 border-b border-gray-100 dark:border-zinc-700 bg-blue-600">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Tabla de Posiciones
                        </h2>
                    </div>
                    <div class="p-4">
                        @livewire('frontend.tabla-posicion.tabla-posicion-resultados', ['campeonatoId' =>
                        $campeonatoSeleccionado])
                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>