<div class="w-full mt-6 font-titulo">
    <div class="p-4 sm:p-6 rounded-3xl bg-white/10 backdrop-blur-xl border border-white/20 shadow-2xl">

        <div class="text-center mb-6">
            <h1 class="text-2xl md:text-4xl font-extrabold text-black dark:text-white uppercase">
                🏆 RESULTADOS DE LA FECHA
            </h1>
        </div>

        {{-- 1. BOTONES DE FECHAS --}}
        <div class="mb-8">
            <div class="flex flex-wrap justify-center gap-2 max-h-40 overflow-y-auto p-2 sm:max-h-none sm:overflow-visible custom-scrollbar">
                @foreach($jornadasDisponibles as $jornada)
                <button
                    wire:key="btn-{{ $jornada }}"
                    wire:click="setJornada('{{ $jornada }}')"
                    class="cursor-pointer px-4 py-2 rounded-xl font-bold transition-all duration-300 border 
    {{ $jornadaSeleccionada == $jornada 
        ? 'bg-blue-600 border-blue-400 text-white shadow-[0_0_15px_rgba(37,99,235,0.5)] scale-105' 
        : 'bg-gray-500/10 border-gray-500/30 text-gray-500 dark:text-gray-400 hover:bg-blue-600/20 hover:border-blue-500 hover:text-blue-500' 
    }}">
                    {{ $jornada }}
                </button>
                @endforeach
            </div>
        </div>

        {{-- 2. CONTENIDO DE RESULTADOS --}}
        @if($resultados && $resultados->count() > 0)
        {{-- Escritorio --}}
        <div class="hidden sm:block overflow-hidden rounded-2xl border border-white/10">
            <table class="w-full text-center text-black dark:text-white">
                <thead class="bg-emerald-600/80 text-white uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">Información</th>
                        <th class="px-4 py-3 text-right">Local</th>
                        <th class="px-4 py-3">Marcador</th>
                        <th class="px-4 py-3 text-left">Visitante</th>
                        <th class="px-4 py-3">Estado</th>
                    </tr>
                </thead>
                <tbody class="bg-white/5">
                    @foreach($resultados as $partido)
                    <tr wire:key="desktop-{{ $partido->id }}" class="border-b border-white/10 hover:bg-white/10 transition">
                        <td class="px-4 py-4 text-xs">
                            <p class="font-bold opacity-70">{{ $partido->fecha_encuentro }}</p>
                            <p class="text-[10px]">{{ $partido->cancha->nombre ?? 'S/C' }}</p>
                        </td>
                        <td class="px-4 py-4 text-right uppercase font-bold {{ $partido->gol_local > $partido->gol_visitante ? 'text-emerald-400' : '' }}">
                            {{ $partido->equipoLocal->nombre }}
                        </td>
                        <td class="px-4 py-4">
                            <div class="inline-flex items-center justify-center gap-2 bg-black/30 px-4 py-1.5 rounded-lg border border-white/10">
                                <span class="text-xl font-black text-white">{{ $partido->gol_local }}</span>
                                <span class="text-white/40">-</span>
                                <span class="text-xl font-black text-white">{{ $partido->gol_visitante }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-left uppercase font-bold {{ $partido->gol_visitante > $partido->gol_local ? 'text-emerald-400' : '' }}">
                            {{ $partido->equipoVisitante->nombre }}
                        </td>
                        <td class="px-4 py-4 text-[10px] uppercase font-bold opacity-60">Finalizado</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Móvil --}}
        <div class="sm:hidden space-y-4">
            @foreach($resultados as $partido)
            <div wire:key="mobile-{{ $partido->id }}" class="bg-white/10 border border-white/20 rounded-2xl overflow-hidden shadow-lg">
                <div class="bg-emerald-600/40 p-2 text-center border-b border-white/10">
                    <span class="text-[10px] font-bold text-white uppercase tracking-widest">Resultado Final</span>
                </div>
                <div class="p-5 flex items-center justify-between gap-4">
                    <div class="flex-1 flex flex-col items-center text-center">
                        <span class="text-xs font-bold mb-1 {{ $partido->gol_local > $partido->gol_visitante ? 'text-emerald-400' : 'text-white' }}">
                            {{ $partido->equipoLocal->nombre }}
                        </span>
                        <span class="text-2xl font-black text-white leading-none">{{ $partido->gol_local }}</span>
                    </div>
                    <div class="text-white/30 font-bold text-xl italic">vs</div>
                    <div class="flex-1 flex flex-col items-center text-center">
                        <span class="text-xs font-bold mb-1 {{ $partido->gol_visitante > $partido->gol_local ? 'text-emerald-400' : 'text-white' }}">
                            {{ $partido->equipoVisitante->nombre }}
                        </span>
                        <span class="text-2xl font-black text-white leading-none">{{ $partido->gol_visitante }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-10">
            <p class="text-white/50 italic">No hay resultados registrados aún.</p>
        </div>
        @endif
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }
    </style>
</div>