<div class="w-full mt-6 font-titulo">

  <!-- Contenedor vidrioso principal -->
  <div
    class="p-4 sm:p-6 rounded-3xl bg-white/10 backdrop-blur-xl border border-white/20 shadow-[0_0_25px_rgba(0,0,0,0.4)]">

    <!-- Título del campeonato -->
    <div class="p-4 sm:p-6 rounded-3xl text-center">
      <h1
        class="text-4xl md:text-6xl font-extrabold text-center text-black dark:text-white px-6 py-3 rounded-2xl inline-block">
        Eliminatoria - {{ ucwords($campeonato->nombre) }}
      </h1>
    </div>

    <hr class="border-t border-white/20 my-6">

    <!-- Selector de Fase -->
    <div class="mb-8 flex justify-center">
      <div
        class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/20 shadow-[0_0_15px_rgba(0,0,0,0.3)]">
        <label for="fase_select" class="block text-base font-semibold mb-2 text-black dark:text-white">
          Seleccione una Fase
        </label>
        <select id="fase_select" wire:model.live="faseElegida"
          class="bg-white/20 backdrop-blur-md border border-white/30 text-black dark:text-white font-select text-lg rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-52 p-3 shadow-[0_0_10px_rgba(0,0,0,0.2)]">
          <option value="">-- Todas las fases --</option>
          @foreach ($fases as $fase)
          <option value="{{ $fase }}">{{ strtoupper($fase) }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <!-- Grid de encuentros -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      @foreach ($encuentros as $encuentro)

      <div
        class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl p-6 shadow-[0_0_20px_rgba(0,0,0,0.3)] hover:bg-white/15 hover:shadow-[0_0_30px_rgba(0,150,255,0.4)] transition-all duration-300">

        <!-- Fecha del encuentro -->
        <div class="text-sm font-semibold text-black/70 dark:text-white/70 mb-2">
          {{ \Carbon\Carbon::parse($encuentro->fecha)->format('d M Y') }}
        </div>

        <!-- Fase del encuentro -->
        <div class="mb-4">
          <span
            class="inline-block bg-gradient-to-r from-blue-700/60 to-blue-500/50 text-black dark:text-white font-bold text-lg px-4 py-2 rounded-xl shadow-[0_0_10px_rgba(0,150,255,0.5)] border border-white/20">
            {{ strtoupper($encuentro->fase) }}
          </span>
        </div>

        <!-- Equipo Local -->
        <div
          class="flex items-center justify-between mb-4 bg-white/5 backdrop-blur-md rounded-xl p-3 border border-white/10">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-sm p-1 shadow-[0_0_10px_rgba(0,0,0,0.2)]">
              <img src="{{ $encuentro->equipoLocal->logo
                                ? asset('storage/' . $encuentro->equipoLocal->logo)
                                : asset('images/default.jpg') }}" alt="Logo Local"
                class="w-full h-full object-contain rounded-full">
            </div>
            <span class="font-semibold text-xl text-black dark:text-white">
              {{ strtoupper($encuentro->equipoLocal->nombre) }}
            </span>
          </div>
          <span
            class="w-10 h-10 flex items-center justify-center rounded-full bg-emerald-600 text-white font-bold text-xl shadow-[0_0_10px_rgba(0,255,180,0.6)]">
            {{ $encuentro->goles_local }}
          </span>
        </div>

        <!-- Equipo Visitante -->
        <div
          class="flex items-center justify-between bg-white/5 backdrop-blur-md rounded-xl p-3 border border-white/10">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-sm p-1 shadow-[0_0_10px_rgba(0,0,0,0.2)]">
              <img src="{{ $encuentro->equipoVisitante->logo
                                ? asset('storage/' . $encuentro->equipoVisitante->logo)
                                : asset('images/default.jpg') }}" alt="Logo Visitante"
                class="w-full h-full object-contain rounded-full">
            </div>
            <span class="font-semibold text-xl text-black dark:text-white">
              {{ strtoupper($encuentro->equipoVisitante->nombre) }}
            </span>
          </div>
          <span
            class="w-10 h-10 flex items-center justify-center rounded-full bg-emerald-600 text-white font-bold text-xl shadow-[0_0_10px_rgba(0,255,180,0.6)]">
            {{ $encuentro->goles_visitante }}
          </span>
        </div>

      </div>

      @endforeach
    </div>

  </div>

</div>