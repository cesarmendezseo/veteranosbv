<div>
    <div
        class="relative w-full min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900">

        <!-- Patrón de fondo animado -->
        <div class="absolute inset-0 opacity-20">
            <div class="absolute inset-0"
                style="background-image: radial-gradient(circle at 2px 2px, rgba(59, 130, 246, 0.3) 1px, transparent 0); background-size: 40px 40px;">
            </div>
        </div>

        <!-- Efecto de luz animado -->
        <div
            class="absolute top-0 -left-40 w-80 h-80 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob">
        </div>
        <div
            class="absolute top-0 -right-40 w-80 h-80 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000">
        </div>
        <div
            class="absolute -bottom-40 left-20 w-80 h-80 bg-cyan-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000">
        </div>

        <!-- CONTENEDOR PRINCIPAL -->
        <div class="relative z-10 max-w-6xl w-full mx-4 p-8 md:p-12">

            <!-- TÍTULO CON EFECTO NEÓN -->
            <div class="text-center mb-16 space-y-4">
                <h1 class="text-5xl md:text-7xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-cyan-300 to-blue-500 animate-gradient drop-shadow-2xl tracking-tight"
                    style="font-family: var(--font-{{ \App\Models\Configuracion::get('titulo_fuente', 'titulo') }});
                       font-size: clamp({{ \App\Models\Configuracion::get('titulo_size', '8px') }}, 8vw, calc({{ \App\Models\Configuracion::get('titulo_size', '8px') }} * 3));">
                    {{ ucwords($campeonato->nombre) }}
                </h1>
                <div
                    class="h-1 w-32 mx-auto bg-gradient-to-r from-transparent via-blue-500 to-transparent rounded-full">
                </div>
            </div>

            <!-- GRID DE CARDS MODERNO -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Card: Tabla de Posiciones / Eliminatorias -->
                @if($campeonato->formato === "eliminacion_simple" || $campeonato->formato === "eliminacion_doble")
                <a href="{{ Route('frontend.eliminatoria.ver', $campeonato->id) }}"
                    class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600/90 to-blue-800/90 backdrop-blur-xl border border-blue-400/20 p-6 transition-all duration-500 hover:scale-105 hover:shadow-2xl hover:shadow-blue-500/50">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-blue-400/0 to-blue-600/0 group-hover:from-blue-400/20 group-hover:to-blue-600/20 transition-all duration-500">
                    </div>
                    <div class="relative flex items-center justify-between">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 bg-cyan-400 rounded-full animate-pulse"></div>
                                <span
                                    class="text-xs font-semibold text-cyan-300 uppercase tracking-wider">Resultados</span>
                            </div>
                            <h2 class="text-2xl font-bold text-white">Encuentros y Resultados</h2>
                            <p class="text-sm text-blue-200/80">Consulta los partidos y marcadores</p>
                        </div>
                        <div
                            class="w-14 h-14 flex items-center justify-center bg-white/10 rounded-2xl backdrop-blur-sm group-hover:bg-white/20 transition-all duration-300 group-hover:rotate-12">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path d="M3 3h8v8H3V3zm10 0h8v8h-8V3zM3 13h8v8H3v-8zm15 0h3v3h-3v-3zm0 5h3v3h-3v-3z" />
                            </svg>
                        </div>
                    </div>
                </a>
                @else
                <a href="{{ Route('tabla-posicion-resultados1', $campeonato->id) }}"
                    class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600/90 to-blue-800/90 backdrop-blur-xl border border-blue-400/20 p-6 transition-all duration-500 hover:scale-105 hover:shadow-2xl hover:shadow-blue-500/50">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-blue-400/0 to-blue-600/0 group-hover:from-blue-400/20 group-hover:to-blue-600/20 transition-all duration-500">
                    </div>
                    <div class="relative flex items-center justify-between">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 bg-cyan-400 rounded-full animate-pulse"></div>
                                <span
                                    class="text-xs font-semibold text-cyan-300 uppercase tracking-wider">Clasificación</span>
                            </div>
                            <h2 class="text-2xl font-bold text-white">Tabla de Posiciones</h2>
                            <p class="text-sm text-blue-200/80">Consulta los resultados y clasificaciones</p>
                        </div>
                        <div
                            class="w-14 h-14 flex items-center justify-center bg-white/10 rounded-2xl backdrop-blur-sm group-hover:bg-white/20 transition-all duration-300 group-hover:rotate-12">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path d="M3 3h8v8H3V3zm10 0h8v8h-8V3zM3 13h8v8H3v-8zm15 0h3v3h-3v-3zm0 5h3v3h-3v-3z" />
                            </svg>
                        </div>
                    </div>
                </a>
                @endif

                <!-- Card: Goleadores -->
                <a href="{{ Route('frontend.goleadores.index', $campeonato->id) }}"
                    class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-600/90 to-purple-800/90 backdrop-blur-xl border border-purple-400/20 p-6 transition-all duration-500 hover:scale-105 hover:shadow-2xl hover:shadow-purple-500/50">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-purple-400/0 to-purple-600/0 group-hover:from-purple-400/20 group-hover:to-purple-600/20 transition-all duration-500">
                    </div>
                    <div class="relative flex items-center justify-between">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 bg-pink-400 rounded-full animate-pulse"></div>
                                <span
                                    class="text-xs font-semibold text-pink-300 uppercase tracking-wider">Estadísticas</span>
                            </div>
                            <h2 class="text-2xl font-bold text-white">Goleadores</h2>
                            <p class="text-sm text-purple-200/80">Máximos anotadores del torneo</p>
                        </div>
                        <div
                            class="w-14 h-14 flex items-center justify-center bg-white/10 rounded-2xl backdrop-blur-sm group-hover:bg-white/20 transition-all duration-300 group-hover:rotate-12">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Card: Sanciones -->
                <a href="{{ Route('frontend.sanciones.index', $campeonato->id) }}"
                    class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-red-600/90 to-red-800/90 backdrop-blur-xl border border-red-400/20 p-6 transition-all duration-500 hover:scale-105 hover:shadow-2xl hover:shadow-red-500/50">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-red-400/0 to-red-600/0 group-hover:from-red-400/20 group-hover:to-red-600/20 transition-all duration-500">
                    </div>
                    <div class="relative flex items-center justify-between">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 bg-orange-400 rounded-full animate-pulse"></div>
                                <span
                                    class="text-xs font-semibold text-orange-300 uppercase tracking-wider">Disciplina</span>
                            </div>
                            <h2 class="text-2xl font-bold text-white">Sanciones</h2>
                            <p class="text-sm text-red-200/80">Consulta los estados disciplinarios</p>
                        </div>
                        <div
                            class="w-14 h-14 flex items-center justify-center bg-white/10 rounded-2xl backdrop-blur-sm group-hover:bg-white/20 transition-all duration-300 group-hover:rotate-12">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z" />
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Card: Tarjetas -->
                <a href="{{ Route('frontend.estadistica.tarjetas', $campeonato->id) }}"
                    class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-yellow-600/90 to-amber-800/90 backdrop-blur-xl border border-yellow-400/20 p-6 transition-all duration-500 hover:scale-105 hover:shadow-2xl hover:shadow-yellow-500/50">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-yellow-400/0 to-amber-600/0 group-hover:from-yellow-400/20 group-hover:to-amber-600/20 transition-all duration-500">
                    </div>
                    <div class="relative flex items-center justify-between">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 bg-yellow-300 rounded-full animate-pulse"></div>
                                <span
                                    class="text-xs font-semibold text-yellow-200 uppercase tracking-wider">Estadísticas</span>
                            </div>
                            <h2 class="text-2xl font-bold text-white">Tarjetas</h2>
                            <p class="text-sm text-yellow-200/80">Amarillas y rojas del campeonato</p>
                        </div>
                        <div
                            class="w-14 h-14 flex items-center justify-center bg-white/10 rounded-2xl backdrop-blur-sm group-hover:bg-white/20 transition-all duration-300 group-hover:rotate-12">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14z" />
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Card: Listado Buena Fe -->
                <a href="{{ Route('frontend.listado-buena-fe.ver', $campeonato->id) }}"
                    class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-600/90 to-green-800/90 backdrop-blur-xl border border-emerald-400/20 p-6 transition-all duration-500 hover:scale-105 hover:shadow-2xl hover:shadow-emerald-500/50">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-emerald-400/0 to-green-600/0 group-hover:from-emerald-400/20 group-hover:to-green-600/20 transition-all duration-500">
                    </div>
                    <div class="relative flex items-center justify-between">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 bg-green-300 rounded-full animate-pulse"></div>
                                <span
                                    class="text-xs font-semibold text-green-200 uppercase tracking-wider">Jugadores</span>
                            </div>
                            <h2 class="text-2xl font-bold text-white">Listado Buena Fe</h2>
                            <p class="text-sm text-emerald-200/80">Jugadores habilitados por equipo</p>
                        </div>
                        <div
                            class="w-14 h-14 flex items-center justify-center bg-white/10 rounded-2xl backdrop-blur-sm group-hover:bg-white/20 transition-all duration-300 group-hover:rotate-12">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                            </svg>
                        </div>
                    </div>
                </a>
                <!-- Card: Proximos Partidos -->
                <a href="{{ Route('frontend.proximoPartidos', $campeonato->id) }}"
                    class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-600/90 to-purple-800/90 backdrop-blur-xl border border-purple-400/20 p-6 transition-all duration-500 hover:scale-105 hover:shadow-2xl hover:shadow-purple-500/50">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-purple-400/0 to-purple-600/0 group-hover:from-purple-400/20 group-hover:to-purple-600/20 transition-all duration-500">
                    </div>
                    <div class="relative flex items-center justify-between">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 bg-pink-400 rounded-full animate-pulse"></div>
                                <span
                                    class="text-xs font-semibold text-pink-300 uppercase tracking-wider">Encuentros</span>
                            </div>
                            <h2 class="text-2xl font-bold text-white">Próximos Partidos</h2>
                            <p class="text-sm text-purple-200/80">Todas las fechas con sus encuentros.</p>
                        </div>
                        <div
                            class="w-14 h-14 flex items-center justify-center bg-white/10 rounded-2xl backdrop-blur-sm group-hover:bg-white/20 transition-all duration-300 group-hover:rotate-12">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </div>
                    </div>
                </a>

            </div>
        </div>
    </div>

    <style>
        @keyframes gradient {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        .animate-gradient {
            background-size: 200% 200%;
            animation: gradient 3s ease infinite;
        }

        @keyframes blob {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
</div>