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
                        {{ \App\Models\Configuracion::get('titulo_size', '12px') }},
                        8vw,
                        calc({{ \App\Models\Configuracion::get('titulo_size', '12px') }} * 3)
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
        <div class="w-full max-w-4xl mx-auto px-4 py-6 space-y-4">

            {{-- TABLA DE POSICIONES --}}
            @if ($mostrarTabla)
            <a href="{{ route('tabla-posicion-resultados') }}"
                class="flex items-center justify-between bg-blue-600 text-white rounded-2xl p-5 shadow-md hover:shadow-xl transition-all duration-200">

                <div>
                    <h2 class="text-xl font-semibold">Tabla de Posiciones</h2>
                    <p class="text-sm opacity-90">Ver resultados</p>
                </div>

                <div class="w-11 h-11 flex items-center justify-center bg-white/20 rounded-full backdrop-blur">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="#fff">
                        <path d="M200-120q-33 0-56.5-23.5T120-200v-560..." />
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

                <div class="w-11 h-11 flex items-center justify-center bg-white/20 rounded-full backdrop-blur">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="#fff">
                        <path d="M480-80q-83 0-156-31.5T197-197..." />
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

                <div class="w-11 h-11 flex items-center justify-center bg-white/20 rounded-full backdrop-blur">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="#fff">
                        <path d="M640-400q-50 0-85-35t-35-85q0-50..." />
                    </svg>
                </div>
            </a>
            @endif


            {{-- PRÓXIMOS ENCUENTROS --}}
            @if ($mostrarEncuentros)
            <a href="{{ route('frontend.proximoPartidos') }}"
                class="flex items-center justify-between bg-blue-600 text-white rounded-2xl p-5 shadow-md hover:shadow-xl transition-all duration-200">

                <div>
                    <h2 class="text-xl font-semibold">Próximos Encuentros</h2>
                    <p class="text-sm opacity-90">Fixtures y horarios</p>
                </div>

                <div class="w-11 h-11 flex items-center justify-center bg-white/20 rounded-full backdrop-blur">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="#fff">
                        <path d="M300-120 140-280l56-56..." />
                    </svg>
                </div>
            </a>
            @endif

        </div>
    </div>
</div>