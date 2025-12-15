<div class="min-h-screen bg-gray-50 dark:bg-zinc-900">
    <!-- Hero Section -->


    <div class="relative min-h-screen overflow-hidden bg-black pb-5">

        <!-- Imagen de fondo -->
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat scale-110 blur-[3px] opacity-60" style="
                background-image: url('{{ asset('storage/' . \App\Models\Configuracion::get('fondo_pagina_principal', 'images/pagina-principal.jpg')) }}');
            ">
            </div>
        </div>

        <!-- Capa de oscurecimiento elegante -->
        <div class="absolute inset-0 bg-gradient-to-b 
                from-black/60 
                via-black/40 
                to-black/65">
        </div>

        <!-- CONTENIDO -->
        <div class="relative z-10 py-20 px-6 flex flex-col items-center">

            <!-- Título -->
            <h1 class="text-4xl md:text-6xl font-extrabold text-center mb-16 text-white tracking-wide
                   drop-shadow-[0_0_15px_rgba(0,200,255,0.8)]" style="
                font-family: var(--font-{{ \App\Models\Configuracion::get('titulo_fuente', 'titulo') }});
                color: {{ \App\Models\Configuracion::get('titulo_color', '#ffffff') }};
                font-size: clamp({{ \App\Models\Configuracion::get('titulo_size', '8px') }}, 8vw, calc({{ \App\Models\Configuracion::get('titulo_size', '8px') }} * 3));
        ">
                ⚡ {{ $tituloPrincipal }}
            </h1>
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
                            {{ \App\Models\Configuracion::get('leyenda_principal1', 'Sistema de Gestión de
                            Torneos
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
                            {{ \App\Models\Configuracion::get('leyenda_principal2', 'Organiza y administra tus
                            torneos
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
                            {{ \App\Models\Configuracion::get('leyenda_principal3', '¡Únete a la comunidad
                            deportiva hoy
                            mismo!') }}
                        </span>
                    </p>
                </div>
            </div>
            @endif
        </div>
        <!-- GRID -->
        <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">



            @foreach ($campeonato as $camp )
            <!-- CARD 1 -->
            <a href="{{ Route('frontend.principal-index', $camp->id) }}">
                <div class="relative group p-[2px] rounded-3xl bg-gradient-to-br 
                    from-cyan-400/60 via-blue-500/40 to-purple-500/60
                    hover:from-cyan-300 hover:to-blue-400
                    transition-all shadow-[0_0_25px_rgba(0,200,255,0.3)]
                    hover:shadow-[0_0_45px_rgba(0,200,255,0.6)] cursor-pointer">

                    <div class="rounded-3xl bg-zinc-900/70 backdrop-blur-xl p-6 h-full
                        flex flex-col justify-between border border-white/10">

                        <!-- Estado -->
                        <div class="absolute top-5 right-5">
                            <span
                                class="px-4 py-1 text-xs rounded-full bg-emerald-600/80 text-white font-bold shadow-lg animate-pulse">
                                EN JUEGO
                            </span>
                        </div>

                        <!-- Banner -->
                        <div class="w-full h-40 rounded-2xl overflow-hidden relative
                            bg-gradient-to-br from-zinc-800 to-zinc-900
                            group-hover:scale-105 transition-transform duration-500">

                            <div
                                class="absolute inset-0 bg-[radial-gradient(circle_at_30%_30%,rgba(0,200,255,0.4),transparent)]">
                            </div>
                            <div
                                class="absolute inset-0 bg-[radial-gradient(circle_at_70%_70%,rgba(255,0,200,0.25),transparent)]">
                            </div>

                            <div class="absolute inset-0 opacity-40 
                                bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]">
                            </div>
                        </div>

                        <!-- Contenido -->
                        <div class="mt-6 text-white space-y-2">
                            <h2 class="text-2xl font-bold tracking-wide group-hover:text-cyan-300 transition">
                                {{ $camp->nombre }}
                            </h2>

                            <p class="text-sm text-gray-300">
                                Temporada: <span class="font-semibold text-cyan-300">{{ date('Y') }}</span>
                            </p>


                            @if($camp->total_equipos === 0)
                            <p class="text-sm text-gray-400">Grupos: {{ $camp->cantidad_grupos }} </p>
                            <p class="text-sm text-gray-400">Equipos: {{ $camp->cantidad_equipos_grupo}}
                            </p>
                            @else
                            <p class="text-sm text-gray-400">Equipos: {{ $camp->total_equipos }} </p>
                            @endif
                            <p class="pt-3 text-cyan-300 font-medium text-sm">
                                Ver detalles →
                            </p>
                        </div>

                    </div>
                </div>
            </a>

            @endforeach
        </div>
    </div>


</div>
</div>
</div>