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
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 -mt-20 relative z-20 pb-12">
        <div class="">

            <!-- Left Column: Matches (Takes up 2 columns on large screens) -->
            <div class="">

                @if ($mostrarEncuentros)
                <a href="{{ route('frontend.fixture.index') }}" class="block bg-white shadow-md rounded-2xl p-4 
                  border border-gray-200 
                  active:scale-[0.98] transition duration-150
                  hover:shadow-lg">

                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">
                                Próximos Encuentros
                            </h2>
                            <p class="text-sm text-gray-500">
                                Ver el fixture completo
                            </p>
                        </div>

                        <div class="w-10 h-10 flex items-center justify-center bg-green-100 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-6 h-6 text-green-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </div>
                        @endif
                    </div>

                    <!-- Right Column: Standings (Takes up 1 column on large screens) -->

                    <div class="p-4">
                        @if ($mostrarTabla && $campeonatoSeleccionado)
                        <a href="{{ route('tabla-posicion-resultados') }}" class="block bg-blue-600 shadow-md rounded-2xl p-4 mb-2
                  border border-gray-200 
                  active:scale-[0.98] transition duration-150
                  hover:shadow-lg">

                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-lg font-semibold text-white">
                                        Tabla de Posiciones
                                    </h2>
                                    <p class="text-sm text-gray-200">
                                        Ver resultados
                                    </p>
                                </div>

                                <div class="w-10 h-10 flex items-center justify-center bg-blue-100 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor" class="w-6 h-6 text-blue-600">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </div>
                            </div>

                        </a>
                        @endif
                        {{-- @if ($mostrarTabla && $campeonatoSeleccionado) --}}
                        <a href="{{ route('frontend.goleadores.index') }}" class="block bg-blue-600 shadow-md rounded-2xl p-4 mb-2 
                  border border-gray-200 
                  active:scale-[0.98] transition duration-150
                  hover:shadow-lg">

                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-lg font-semibold text-white">
                                        Goleadores
                                    </h2>
                                    <p class="text-sm text-gray-200">
                                        Ver los goleadores del torneo actual
                                    </p>
                                </div>

                                <div class="w-10 h-10 flex items-center justify-center bg-blue-100 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor" class="w-6 h-6 text-blue-600">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </div>
                            </div>

                        </a>
                        {{-- @endif --}}
                    </div>

            </div>
        </div>
    </div>