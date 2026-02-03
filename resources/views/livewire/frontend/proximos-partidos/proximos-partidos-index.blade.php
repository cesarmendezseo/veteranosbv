<div class="w-full mt-6 font-titulo">
    <div class="p-4 sm:p-6 rounded-3xl bg-white/10 backdrop-blur-xl border border-white/20 shadow-2xl">

        <div class="text-center mb-6">
            <h1 class="text-2xl md:text-4xl font-extrabold text-black dark:text-white uppercase">
                📅 PRÓXIMOS ENCUENTROS
            </h1>
        </div>

        <div class="mb-8 flex justify-center">
            <div class="w-72">
                <select wire:model.live="jornadaSeleccionada"
                    class="w-full bg-white/20 text-black dark:text-white rounded-xl p-2.5 border border-gray-400 backdrop-blur-lg">
                    @foreach($jornadasDisponibles as $jornada)
                    <option value="{{ $jornada }}" class="text-black">{{ $jornada }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if($proximos->count() > 0)
        <div class="hidden sm:block overflow-hidden rounded-2xl border border-white/10">
            <table class="w-full text-center text-black dark:text-white">
                <thead class="bg-blue-600/80 text-white uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">Jornada</th>
                        <th class="px-4 py-3">Información / Cancha</th>
                        <th class="px-4 py-3">Local</th>
                        <th class="px-4 py-3">VS</th>
                        <th class="px-4 py-3">Visitante</th>
                    </tr>
                </thead>
                <tbody class="bg-white/5">
                    @foreach($proximos as $partido)
                    @php $estadoLocal = strtolower($partido->estado); @endphp
                    <tr class="border-b border-white/10 hover:bg-white/10 transition">
                        <td class="px-4 py-4 font-bold">{{ $partido->fecha_encuentro }}</td>

                        <td class="px-4 py-4">
                            @if($estadoLocal == 'por_programar')
                            <span class="text-yellow-500 bg-fuchsia-900 p-3 font-black animate-pulse text-xs">
                                <i class="fas fa-clock mr-1"></i> POR JUGARSE
                            </span>
                            @else
                            <div class="text-xs">
                                <p class="font-bold text-blue-400 uppercase">{{ $partido->cancha->nombre ?? 'Sin cancha'
                                    }}</p>
                                <p class="opacity-80">{{ \Carbon\Carbon::parse($partido->fecha)->format('d/m/Y') }} - {{
                                    \Carbon\Carbon::parse($partido->hora)->format('H:i') }} hs</p>
                            </div>
                            @endif
                        </td>

                        <td class="px-4 py-4 font-bold uppercase">{{ $partido->equipoLocal->nombre }}</td>
                        <td class="px-4 py-4 text-center">
                            <span
                                class="bg-blue-600 text-white w-8 h-8 flex items-center justify-center rounded-full text-[10px] font-black mx-auto">VS</span>
                        </td>
                        <td class="px-4 py-4 font-bold uppercase">{{ $partido->equipoVisitante->nombre }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Móvil --}}
        <div class="sm:hidden space-y-4">
            @foreach($proximos as $partido)
            @php $estadoLocal = strtolower($partido->estado); @endphp
            <div class="bg-white/10 border border-white/20 rounded-2xl overflow-hidden shadow-lg">
                <div
                    class="{{ $estadoLocal == 'por_programar' ? 'bg-gray-700/50' : 'bg-blue-600/40' }} p-3 flex justify-between items-center border-b border-white/10">
                    <span class="text-xs font-bold text-black dark:text-white">Fecha:{{ $partido->fecha_encuentro
                        }}</span>
                    @if($estadoLocal == 'por_programar')
                    <span class="text-[10px] bg-yellow-500 text-black px-2 py-0.5 rounded font-black uppercase">Por
                        Jugarse</span>
                    @else
                    <span class="text-[10px] text-black dark:text-white font-bold">{{
                        \Carbon\Carbon::parse($partido->fecha)->format('d/m') }} - {{
                        \Carbon\Carbon::parse($partido->hora)->format('H:i') }} hs | {{ $partido->cancha->nombre ??
                        'S/C'
                        }}</span>
                    @endif
                </div>
                <div class="p-5 flex items-center justify-between gap-2 text-center">
                    <div class="flex-1 font-bold text-sm text-black dark:text-white">{{
                        strtoupper($partido->equipoLocal->nombre) }}
                    </div>
                    <div class="font-black text-blue-500 text-xs italic italic">VS</div>
                    <div class="flex-1 font-bold text-sm text-black dark:text-white">{{
                        strtoupper($partido->equipoVisitante->nombre) }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-10">
            <p class="text-white/50 italic">No hay encuentros disponibles para mostrar.</p>
        </div>
        @endif
    </div>
</div>