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



        <div class="P-4">
            @if ($mostrarTabla && $campeonatoSeleccionado)
            <a href="{{ route('frontend.fixture.index') }}" class="block bg-blue-600 shadow-md rounded-2xl p-4 mb-2
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
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                            fill="#2563eb">
                            <path
                                d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-507h560v-133H200v133Zm0 214h560v-134H200v134Zm0 213h560v-133H200v133Zm40-454v-80h80v80h-80Zm0 214v-80h80v80h-80Zm0 214v-80h80v80h-80Z" />
                        </svg>

                    </div>
                </div>

            </a>
            @endif
        </div>


        <div class="P-4">
            {{-- MOSTRAR GOLEADOR --}}
            @if ($mostrarGoleadores && $campeonatoSeleccionado)
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
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                            fill="#2563eb">
                            <path
                                d="M480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm200-500 54-18 16-54q-32-48-77-82.5T574-786l-54 38v56l160 112Zm-400 0 160-112v-56l-54-38q-54 17-99 51.5T210-652l16 54 54 18Zm-42 308 46-4 30-54-58-174-56-20-40 30q0 65 18 118.5T238-272Zm242 112q26 0 51-4t49-12l28-60-26-44H378l-26 44 28 60q24 8 49 12t51 4Zm-90-200h180l56-160-146-102-144 102 54 160Zm332 88q42-50 60-103.5T800-494l-40-28-56 18-58 174 30 54 46 4Z" />
                        </svg>
                    </div>
                </div>

            </a>
            @endif
        </div>
        {{-- ////////////////////////////////// --}}
        <div class="P-4">
            {{-- MOSTRAR SANCIONES --}}
            @if ($mostrarSanciones && $campeonatoSeleccionado)
            <a href="{{ route('frontend.sanciones.index') }}" class="block bg-blue-600 shadow-md rounded-2xl p-4 mb-2 
                  border border-gray-200 
                  active:scale-[0.98] transition duration-150
                  hover:shadow-lg">

                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-white">
                            Sanciones
                        </h2>
                        <p class="text-sm text-gray-200">
                            Ver las sancioes de los jugadores
                        </p>
                    </div>

                    <div class="w-10 h-10 flex items-center justify-center bg-blue-100 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                            fill="#2563eb">
                            <path
                                d="M640-400q-50 0-85-35t-35-85q0-50 35-85t85-35q50 0 85 35t35 85q0 50-35 85t-85 35ZM400-160v-76q0-21 10-40t28-30q45-27 95.5-40.5T640-360q56 0 106.5 13.5T842-306q18 11 28 30t10 40v76H400Zm86-80h308q-35-20-74-30t-80-10q-41 0-80 10t-74 30Zm154-240q17 0 28.5-11.5T680-520q0-17-11.5-28.5T640-560q-17 0-28.5 11.5T600-520q0 17 11.5 28.5T640-480Zm0-40Zm0 280ZM120-400v-80h320v80H120Zm0-320v-80h480v80H120Zm324 160H120v-80h360q-14 17-22.5 37T444-560Z" />
                        </svg>
                    </div>
                </div>

            </a>
            @endif
        </div>

        <div class="P-4">
            {{-- MOSTRAR Tabla --}}
            @if ($mostrarEncuentros && $campeonatoSeleccionado)
            <a href="{{ route('frontend.fixture.index') }}" class="block bg-blue-600 shadow-md rounded-2xl p-4 mb-2
                  border border-gray-200 
                  active:scale-[0.98] transition duration-150
                  hover:shadow-lg">

                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-white">
                            Proximos Encuentros
                        </h2>
                        <p class="text-sm text-gray-200">
                            Fixtures y horarios
                        </p>
                    </div>

                    <div class="w-10 h-10 flex items-center justify-center bg-blue-100 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                            fill="#2563eb">
                            <path
                                d="M300-120 140-280l56-56 64 63v-247H120q-33 0-56.5-23.5T40-600v-240h80v240h140q33 0 56.5 23.5T340-520v247l63-63 57 56-160 160Zm360 0L500-280l56-56 64 63v-247q0-33 23.5-56.5T700-600h140v-240h80v240q0 33-23.5 56.5T840-520H700v248l63-64 57 56-160 160Z" />
                        </svg>

                    </div>
                </div>

            </a>
            @endif
        </div>

    </div>
</div>




</div>