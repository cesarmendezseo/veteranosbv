<div class="w-full mt-6 font-titulo">

  <!-- Contenedor vidrioso principal -->
  <div
    class="p-4 sm:p-6 rounded-3xl bg-white/10 backdrop-blur-xl border border-white/20 shadow-[0_0_25px_rgba(0,0,0,0.4)]">

    <!-- Título del campeonato -->
    <div class="text-center mb-8">
      <h1 class="text-4xl md:text-6xl font-extrabold text-black dark:text-white">
        🏆 {{ ucwords($campeonato->nombre) }}
      </h1>
      <p class="text-2xl mt-2 text-black/70 dark:text-white/70"></p>
    </div>

    <!-- Selector de Fase con estilo de pestañas -->
    <div class="mb-8">
      <div class="flex flex-wrap justify-center gap-3">
        <button wire:click="$set('faseElegida', '')"
          class="px-6 py-3 rounded-xl font-bold text-sm transition-all duration-300
                    {{ $faseElegida == '' 
                        ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-[0_0_20px_rgba(0,150,255,0.6)] scale-105' 
                        : 'bg-white/10 backdrop-blur-md text-black dark:text-white border border-white/20 hover:bg-white/20' }}">

        </button>
        @foreach ($fases as $fase)
        <button wire:click="$set('faseElegida', '{{ $fase }}')"
          class="cursor-pointer px-6 py-3 rounded-xl font-bold text-sm transition-all duration-300
                    {{ $faseElegida == $fase 
                        ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-[0_0_20px_rgba(0,150,255,0.6)] scale-105' 
                        : 'bg-white/10 backdrop-blur-md text-black dark:text-white border border-white/20 hover:bg-white/20' }}">
          {{ strtoupper($fase) }}
        </button>
        @endforeach
      </div>
    </div>

    <!-- Grid de encuentros estilo bracket -->
    <div class="space-y-6">
      @php
      $encuentrosPorFase = $encuentros->groupBy('fase');
      @endphp

      @foreach ($encuentrosPorFase as $fase => $encuentrosFase)

      <!-- Separador de fase -->
      <div class="relative my-8">
        <div class="absolute inset-0 flex items-center">
          <div class="w-full border-t-2 border-white/20"></div>
        </div>
        <div class="relative flex justify-center">
          {{-- <span
            class="bg-gradient-to-r from-orange-600 to-orange-500 text-white px-8 py-3 rounded-full font-bold text-xl shadow-[0_0_20px_rgba(255,100,0,0.6)] border-2 border-white/30">
            {{ strtoupper($fase) }}
          </span> --}}
        </div>
      </div>

      <!-- Encuentros de esta fase -->
      <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach ($encuentrosFase as $encuentro)

        <div
          class="relative bg-gradient-to-br from-white/15 to-white/5 backdrop-blur-xl border-2 border-white/30 rounded-2xl overflow-hidden shadow-[0_0_25px_rgba(0,0,0,0.4)] hover:shadow-[0_0_35px_rgba(0,150,255,0.5)] transition-all duration-300 hover:scale-105">

          <!-- Barra superior con fecha -->
          <div
            class="bg-gradient-to-r from-blue-700/80 to-blue-500/60 backdrop-blur-sm px-4 py-2 border-b border-white/20">
            <div class="flex justify-between items-center">
              <span class="text-white font-bold text-sm">
                📅 {{ \Carbon\Carbon::parse($encuentro->fecha)->format('d/m/Y') }}
              </span>
              @if($encuentro->hora)
              <span class="text-white/90 text-xs">
                🕐 {{ $encuentro->hora }}
              </span>
              @endif
            </div>
          </div>

          <!-- Contenido del encuentro -->
          <div class="p-5">

            <!-- Equipo Local -->
            <div class="flex items-center justify-between mb-3 group">
              <div class="flex items-center gap-3 flex-1">
                <div class="relative">
                  <div
                    class="w-14 h-14 rounded-full bg-gradient-to-br from-white/30 to-white/10 backdrop-blur-sm p-2 shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <img src="{{ $encuentro->equipoLocal->logo
                                            ? asset('storage/' . $encuentro->equipoLocal->logo)
                                            : asset('images/default.jpg') }}" alt="Logo Local"
                      class="w-full h-full object-contain rounded-full">
                  </div>
                  <!-- Indicador de ganador -->
                  @if($encuentro->goles_local > $encuentro->goles_visitante)
                  <div
                    class="absolute -top-1 -right-1 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center shadow-lg">
                    <span class="text-white text-xs font-bold">✓</span>
                  </div>
                  @endif
                </div>
                <span class="font-bold text-lg text-black dark:text-white truncate">
                  {{ strtoupper($encuentro->equipoLocal->nombre) }}
                </span>
              </div>
              <div class="ml-3">
                <span class="flex items-center justify-center w-12 h-12 rounded-xl 
                                    {{ $encuentro->goles_local > $encuentro->goles_visitante 
                                        ? 'bg-gradient-to-br from-green-600 to-green-500 shadow-[0_0_15px_rgba(0,255,100,0.6)]' 
                                        : 'bg-gradient-to-br from-gray-600 to-gray-500' }} 
                                    text-white font-extrabold text-2xl">
                  {{ $encuentro->goles_local }}
                </span>
              </div>
            </div>

            <!-- Separador VS -->
            <div class="flex justify-center my-2">
              <span
                class="bg-white/20 backdrop-blur-sm px-4 py-1 rounded-full text-xs font-bold text-black dark:text-white border border-white/30">
                VS
              </span>
            </div>

            <!-- Equipo Visitante -->
            <div class="flex items-center justify-between group">
              <div class="flex items-center gap-3 flex-1">
                <div class="relative">
                  <div
                    class="w-14 h-14 rounded-full bg-gradient-to-br from-white/30 to-white/10 backdrop-blur-sm p-2 shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <img src="{{ $encuentro->equipoVisitante->logo
                                            ? asset('storage/' . $encuentro->equipoVisitante->logo)
                                            : asset('images/default.jpg') }}" alt="Logo Visitante"
                      class="w-full h-full object-contain rounded-full">
                  </div>
                  <!-- Indicador de ganador -->
                  @if($encuentro->goles_visitante > $encuentro->goles_local)
                  <div
                    class="absolute -top-1 -right-1 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center shadow-lg">
                    <span class="text-white text-xs font-bold">✓</span>
                  </div>
                  @endif
                </div>
                <span class="font-bold text-lg text-black dark:text-white truncate">
                  {{ strtoupper($encuentro->equipoVisitante->nombre) }}
                </span>
              </div>
              <div class="ml-3">
                <span class="flex items-center justify-center w-12 h-12 rounded-xl 
                                    {{ $encuentro->goles_visitante > $encuentro->goles_local 
                                        ? 'bg-gradient-to-br from-green-600 to-green-500 shadow-[0_0_15px_rgba(0,255,100,0.6)]' 
                                        : 'bg-gradient-to-br from-gray-600 to-gray-500' }} 
                                    text-white font-extrabold text-2xl">
                  {{ $encuentro->goles_visitante }}
                </span>
              </div>
            </div>

          </div>

          <!-- Borde inferior decorativo -->
          <div class="h-1 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500"></div>

        </div>

        @endforeach
      </div>

      @endforeach
    </div>

  </div>

</div>