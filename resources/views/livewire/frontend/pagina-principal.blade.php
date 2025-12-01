<div class="min-h-screen bg-gray-50 dark:bg-zinc-900">
    <!-- Hero Section -->
    <div class="relative w-full min-h-screen">
        <div class="absolute inset-0 bg-cover bg-center"
            style="background-image: url('{{ asset('storage/' . \App\Models\Configuracion::get('fondo_pagina_principal', 'images/pagina-principal.jpg')) }}')">
        </div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-gray-50 dark:to-zinc-900"></div>
        <div class="relative z-10 flex flex-col items-center justify-center h-full text-center px-4">

            <h1 class="font-extrabold tracking-tight drop-shadow-lg mb-4" style="
                    font-family: var(--font-{{ \App\Models\Configuracion::get('titulo_fuente', 'titulo') }});
                    color: {{ \App\Models\Configuracion::get('titulo_color', '#ffffff') }};
                    font-size: clamp(
                        {{ \App\Models\Configuracion::get('titulo_size', '8px') }},
                        8vw,
                        calc({{ \App\Models\Configuracion::get('titulo_size', '8px') }} * 3)
                    );
                ">
                {{ $tituloPrincipal }}
            </h1>

            {{-- Leyendas principales --}}
            @php
            $ley1 = \App\Models\Configuracion::get('leyenda_principal1');
            $ley2 = \App\Models\Configuracion::get('leyenda_principal2');
            $ley3 = \App\Models\Configuracion::get('leyenda_principal3');
            @endphp

            @if($ley1 || $ley2 || $ley3)
            <div class="flex items-center justify-center min-h-auto md:min-h-[40vh]">
                <div class="text-center bg-gradient-to-r from-gray-700 via-gray-800 to-gray-700
                                text-white rounded-2xl shadow-2xl p-6 mb-8 max-w-3xl mx-auto
                                border border-gray-600">

                    <p class="font-serif italic text-lg md:text-xl leading-relaxed tracking-wide">
                        {{-- Texto 1 --}}
                        <span style="
                                font-family: var(--font-{{ \App\Models\Configuracion::get('leyenda_principal_fuente_1', 'titulo') }});
                                color: {{ \App\Models\Configuracion::get('leyenda_principal_color_1', '#ffffff') }};
                                font-size: clamp(
                                    {{ \App\Models\Configuracion::get('leyenda_size_1', '12px') }},
                                    4vw,
                                    calc({{ \App\Models\Configuracion::get('leyenda_size_1', '12px') }} * 2)
                                );
                                font-weight: {{ \App\Models\Configuracion::get('leyenda_weight_1', '700') }};
                            ">
                            {{ \App\Models\Configuracion::get('leyenda_principal1', 'Sistema de Gestión de Torneos
                            Deportivos') }}
                        </span>

                        {{-- Texto 2 --}}
                        <span style="
                                font-family: var(--font-{{ \App\Models\Configuracion::get('leyenda_principal_fuente_2', 'titulo') }});
                                color: {{ \App\Models\Configuracion::get('leyenda_principal_color_2', '#ffffff') }};
                                font-size: clamp(
                                    {{ \App\Models\Configuracion::get('leyenda_size_2', '12px') }},
                                    4vw,
                                    calc({{ \App\Models\Configuracion::get('leyenda_size_2', '12px') }} * 2)
                                );
                                font-weight: {{ \App\Models\Configuracion::get('leyenda_weight_2', '700') }};
                            ">
                            {{ \App\Models\Configuracion::get('leyenda_principal2', 'Organiza y administra tus torneos
                            fácilmente') }}
                        </span>

                        {{-- Texto 3 --}}
                        <span style="
                                font-family: var(--font-{{ \App\Models\Configuracion::get('leyenda_principal_fuente_3', 'titulo') }});
                                color: {{ \App\Models\Configuracion::get('leyenda_principal_color_3', '#ffffff') }};
                                font-size: clamp(
                                    {{ \App\Models\Configuracion::get('leyenda_size_3', '12px') }},
                                    4vw,
                                    calc({{ \App\Models\Configuracion::get('leyenda_size_3', '12px') }} * 2)
                                );
                                font-weight: {{ \App\Models\Configuracion::get('leyenda_weight_3', '700') }};
                            ">
                            {{ \App\Models\Configuracion::get('leyenda_principal3', '¡Únete a la comunidad deportiva hoy
                            mismo!') }}
                        </span>
                    </p>
                </div>
            </div>
            @endif
        </div>

        <!-- Main Content -->
        <div class="w-full max-w-4xl mx-auto px-4 py-6 space-y-4 relative z-20">

            {{-- TABLA DE POSICIONES --}}
            @if ($mostrarTabla)
            <a href="{{ route('tabla-posicion-resultados') }}"
                class="flex items-center justify-between bg-blue-600 text-white rounded-2xl p-5 shadow-md hover:shadow-xl transition-all duration-200">

                <div>
                    <h2 class="text-xl font-semibold">Tabla de Posiciones</h2>
                    <p class="text-sm opacity-90">Ver resultados</p>
                </div>

                <div class="w-11 h-11 flex items-center justify-center bg-white rounded-full backdrop-blur">
                    {{-- AÑADIDO: text-blue-600 fill-current --}}
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                        fill="#1f1f1f">
                        <path
                            d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm240-240H200v160h240v-160Zm80 0v160h240v-160H520Zm-80-80v-160H200v160h240Zm80 0h240v-160H520v160ZM200-680h560v-80H200v80Z" />
                    </svg>
                </div>
            </a>
            @endif



            {{-- GOLEADORES --}}
            @if ($mostrarGoleadores)
            <a href="{{ route('frontend.goleadores.index') }}"
                class="flex items-center justify-between bg-blue-600 text-white rounded-2xl p-5 shadow-md hover:shadow-xl transition-all duration-200">

                <div>
                    <h2 class="text-xl font-semibold">Goleadores</h2>
                    <p class="text-sm opacity-90">Ver los goleadores del torneo actual</p>
                </div>

                <div class="w-11 h-11 flex items-center justify-center bg-white rounded-full backdrop-blur">
                    {{-- AÑADIDO: text-blue-600 fill-current --}}
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                        fill="#1f1f1f">
                        <path
                            d="M185-80q-17 0-29.5-12.5T143-122v-105q0-90 56-159t144-88q-40 28-62 70.5T259-312v190q0 11 3 22t10 20h-87Zm147 0q-17 0-29.5-12.5T290-122v-190q0-70 49.5-119T459-480h189q70 0 119 49t49 119v64q0 70-49 119T648-80H332Zm148-484q-66 0-112-46t-46-112q0-66 46-112t112-46q66 0 112 46t46 112q0 66-46 112t-112 46Z" />
                    </svg>
                </div>
            </a>
            @endif



            {{-- SANCIONES --}}
            @if ($mostrarSanciones)
            <a href="{{ route('frontend.sanciones.index') }}"
                class="flex items-center justify-between bg-blue-600 text-white rounded-2xl p-5 shadow-md hover:shadow-xl transition-all duration-200">

                <div>
                    <h2 class="text-xl font-semibold">Sanciones</h2>
                    <p class="text-sm opacity-90">Ver las sanciones de los jugadores</p>
                </div>

                <div class="w-11 h-11 flex items-center justify-center bg-white rounded-full backdrop-blur">
                    {{-- AÑADIDO: text-blue-600 fill-current --}}
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                        fill="#1f1f1f">
                        <path
                            d="m819-28-59-59q-10 3-19.5 5T720-80H240q-50 0-85-35t-35-85v-120h120v-287L27-820l57-57L876-85l-57 57ZM240-160h447l-80-80H200v40q0 17 11.5 28.5T240-160Zm600-73-80-80v-447H313l-73-73v-47l60 60 60-60 60 60 60-60 60 60 60-60 60 60 60-60 60 60 60-60v647Zm-520-87h207L320-527v207Zm155-280-80-80h205v80H475Zm120 120-80-80h85v80h-5Zm85 0q-17 0-28.5-11.5T640-520q0-17 11.5-28.5T680-560q17 0 28.5 11.5T720-520q0 17-11.5 28.5T680-480Zm0-120q-17 0-28.5-11.5T640-640q0-17 11.5-28.5T680-680q17 0 28.5 11.5T720-640q0 17-11.5 28.5T680-600ZM200-160v-80 80Z" />
                    </svg>
                </div>
            </a>
            @endif



            {{-- PRÓXIMOS ENCUENTROS (Este ya estaba correcto) --}}
            @if ($mostrarEncuentros)
            <a href="{{ route('frontend.proximoPartidos') }}"
                class="flex items-center justify-between bg-blue-600 text-white rounded-2xl p-5 shadow-md hover:shadow-xl transition-all duration-200">

                <div>
                    <h2 class="text-xl font-semibold">Próximos Encuentros</h2>
                    <p class="text-sm opacity-90">Fixtures y horarios</p>
                </div>

                <div class="w-11 h-11 flex items-center justify-center bg-white rounded-full backdrop-blur">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                        fill="#1f1f1f">
                        <path
                            d="M600-120v-120H440v-400h-80v120H80v-320h280v120h240v-120h280v320H600v-120h-80v320h80v-120h280v320H600ZM160-760v160-160Zm520 400v160-160Zm0-400v160-160Zm0 160h120v-160H680v160Zm0 400h120v-160H680v160ZM160-600h120v-160H160v160Z" />
                    </svg>
                </div>

            </a>
            @endif

        </div>
    </div>
</div>