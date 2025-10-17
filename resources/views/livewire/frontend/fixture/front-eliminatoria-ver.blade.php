<div class="space-y-6">
  <h2 class="text-2xl font-bold text-center mb-6">
    Eliminatoria - {{ $campeonato->nombre }}
  </h2>

  <div>
    <label for="equipo_visitante_id" class="text-base text-blue-900 font-semibold dark:text-gray-100">Equipo
      Visitante</label>
    <select id="equipo_visitante_id" wire:model.live="faseElegida"
      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
      <option value=""> </option>
      @foreach ($fases as $fase)
      <option value="{{ $fase }}">{{ ucfirst($fase) }}</option>
      @endforeach
    </select>

  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @foreach ($encuentros as $fencuentro)
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-4 flex flex-col gap-2">
      <div class="text-sm text-gray-500 dark:text-gray-300">{{ \Carbon\Carbon::parse($fencuentro->fecha)->format('d M')
        }}</div>

      <div class="flex items-center justify-between">
        {{-- Equipo Local --}}
        <div class="flex items-center gap-2">
          <img src="{{ $fencuentro->equipoLocal->logo }}" alt="Logo Local" class="w-8 h-8 object-contain">
          <span class="font-semibold text-blue-900 dark:text-white">{{ $fencuentro->equipoLocal->nombre }}</span>
        </div>
        <span class="text-lg font-bold text-gray-800 dark:text-white">{{ $fencuentro->goles_local }}</span>
      </div>

      <div class="flex items-center justify-between">
        {{-- Equipo Visitante --}}
        <div class="flex items-center gap-2">
          <img src="{{ $fencuentro->equipoVisitante->logo }}" alt="Logo Visitante" class="w-8 h-8 object-contain">
          <span class="font-semibold text-blue-900 dark:text-white">{{ $fencuentro->equipoVisitante->nombre }}</span>
        </div>
        <span class="text-lg font-bold text-gray-800 dark:text-white">{{ $fencuentro->goles_visitante }}</span>
      </div>
    </div>
    @endforeach
  </div>

</div>