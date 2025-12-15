<div class="relative w-full min-h-screen flex items-center justify-center 
            bg-gradient-to-br from-gray-900 via-gray-800 to-black overflow-hidden">

    <!-- Imagen de fondo -->
    <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1529257414772-1960c08cb732?auto=format&fit=crop&w=1500&q=80')] 
                bg-cover bg-center opacity-40"></div>

    <!-- Capa borrosa -->
    <div class="absolute inset-0 backdrop-blur-xl bg-white/5"></div>

    <!-- CONTENEDOR VIDRIOSO -->
    <div class="relative z-10 max-w-4xl w-full p-10 rounded-3xl space-y-6
                bg-white/10 backdrop-blur-2xl border border-white/20
                shadow-[0_0_40px_rgba(0,0,0,0.5)]">

        <!-- TITULO -->
        <h1 class="text-4xl md:text-6xl font-extrabold text-center text-white mb-12 tracking-wide
                   drop-shadow-[0_0_15px_rgba(0,200,255,0.8)]" style="
                font-family: var(--font-{{ \App\Models\Configuracion::get('titulo_fuente', 'titulo') }});
                color: {{ \App\Models\Configuracion::get('titulo_color', '#ffffff') }};
                font-size: clamp(
                    {{ \App\Models\Configuracion::get('titulo_size', '8px') }},
                    8vw,
                    calc({{ \App\Models\Configuracion::get('titulo_size', '8px') }} * 3)
                );
            ">
            {{ $campeonato->nombre }}
        </h1>

        <!-- ITEMS DE MENÚ (repetibles) -->
        @if($campeonato->formato === "eliminacion_simple" || $campeonato->formato === "eliminacion_doble")
        <a href="{{ Route('frontend.eliminatoria.ver', $campeonato->id) }}" class="flex items-center justify-between bg-blue-600 text-white rounded-2xl p-5
                  shadow-md hover:shadow-xl transition-all duration-200">

            <div>
                <h2 class="text-xl font-semibold">Ver encuentros y Resultados</h2>
                <p class="text-sm opacity-90">Ver resultados</p>
            </div>

            <div class="w-11 h-11 flex items-center justify-center bg-white rounded-full backdrop-blur">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                    fill="#1f1f1f">
                    <path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 
                             23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 
                             33-23.5 56.5T760-120H200Zm240-240H200v160h240v-160Zm80 
                             0v160h240v-160H520Zm-80-80v-160H200v160h240Zm80 
                             0h240v-160H520v160ZM200-680h560v-80H200v80Z" />
                </svg>
            </div>
        </a>
        @else
        <a href="{{ Route('tabla-posicion-resultados1', $campeonato->id) }}" class="flex items-center justify-between bg-blue-600 text-white rounded-2xl p-5
                  shadow-md hover:shadow-xl transition-all duration-200">

            <div>
                <h2 class="text-xl font-semibold">Tabla de Posiciones</h2>
                <p class="text-sm opacity-90">Ver resultados</p>
            </div>

            <div class="w-11 h-11 flex items-center justify-center bg-white rounded-full backdrop-blur">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                    fill="#1f1f1f">
                    <path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 
                             23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 
                             33-23.5 56.5T760-120H200Zm240-240H200v160h240v-160Zm80 
                             0v160h240v-160H520Zm-80-80v-160H200v160h240Zm80 
                             0h240v-160H520v160ZM200-680h560v-80H200v80Z" />
                </svg>
            </div>
        </a>
        @endif


    </div>

</div>