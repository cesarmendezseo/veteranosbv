<div class="space-y-6 mt-20 font-titulo">
  <h2 class="text-2xl font-bold text-center mb-6">
    Eliminatoria - {{ucwords( $campeonato->nombre) }}
  </h2>

  <hr class="border-t border-gray-300 dark:border-gray-400 my-4">
  <div class="mt-2">
    <label for="equipo_visitante_id" class="text-base text-blue-900 font-semibold dark:text-gray-100">Selecione una Fase
    </label>
    <select id="equipo_visitante_id" wire:model.live="faseElegida"
      class="bg-gray-50 border font-select text-lg border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-40 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
      <option value=""> </option>
      @foreach ($fases as $fase)
      <option value="{{ $fase }}">{{ strtoupper($fase) }}</option>
      @endforeach
    </select>

  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @foreach ($encuentros as $fencuentro)

    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-4 flex flex-col gap-2">
      <div class="text-sm text-gray-500 dark:text-gray-300">{{ \Carbon\Carbon::parse($fencuentro->fecha)->format('d M')
        }}</div>
      <div>
        <span class="font-semibold font-titulo text-xl text-[#bb4108] dark:text-white">{{ strtoupper($fencuentro->fase
          )}}</span>
      </div>

      <div class="flex items-center justify-between">
        {{-- Equipo Local --}}
        <div class="flex items-center gap-2">
          <img src="{{ $fencuentro->equipoLocal->logo
    ? asset('storage/' . $fencuentro->equipoLocal->logo)
    : asset('images/default.jpg') }}" alt="Logo Local"
            class="w-8 h-8 object-contain rounded-full bg-gray-300 shadow-2md">
          <span class="font-semibold font-front text-xl text-blue-900 dark:text-white">{{
            strtoupper($fencuentro->equipoLocal->nombre)
            }}</span>
        </div>
        <span class=" font-bold font-front text-xl text-gray-800 dark:text-white">{{ $fencuentro->goles_local
          }}</span>
      </div>

      <div class="flex items-center justify-between">
        {{-- Equipo Visitante --}}
        <div class="flex items-center gap-2">
          <img src="{{ $fencuentro->equipoVisitante->logo
                ? asset('storage/' . $fencuentro->equipoVisitante->logo)
                 : asset('images/default.jpg') }}" alt="Logo Visitante"
            class="w-8 h-8 object-contain rounded-full bg-gray-300 shadow-2md">
          <span class="font-semibold font-front text-xl text-blue-900 dark:text-white">{{
            strtoupper($fencuentro->equipoVisitante->nombre)
            }}</span>
        </div>
        <span class=" font-bold font-front text-xl text-gray-800 dark:text-white">{{ $fencuentro->goles_visitante
          }}</span>
      </div>
    </div>
    @endforeach
  </div>


</div>