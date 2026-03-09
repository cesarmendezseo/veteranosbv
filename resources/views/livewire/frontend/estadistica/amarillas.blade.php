<div class="w-full mt-6 font-titulo">

    <!-- Contenedor vidrioso principal -->
    <div
        class="p-4 sm:p-6 rounded-3xl bg-white/10 backdrop-blur-xl border border-white/20 shadow-[0_0_25px_rgba(0,0,0,0.4)]">

        <!-- Título -->
        <div class="p-4 sm:p-6 rounded-3xl text-center mb-6">
            <h1
                class="text-2xl md:text-4xl font-extrabold text-black dark:text-white px-6 py-3 rounded-2xl inline-block">
                🟨🟥 TARJETAS
            </h1>
        </div>

        @php
        $tiposTarjetas = [
        'amarilla' => 'AMARILLA',
        'doble_amarilla' => 'DOBLE AMARILLA',
        'roja' => 'ROJA',
        'todas' => 'TODAS',
        ];
        @endphp

        <!-- Filtros de tarjetas -->
        <div class="mb-8">
            <div class="flex flex-wrap justify-center gap-3">
                @foreach ($tiposTarjetas as $key => $label)
                <button wire:click="$set('tarjetaElegida', '{{ $key }}')"
                    class="cursor-pointer px-6 py-3 rounded-xl font-bold text-sm transition-all duration-300 whitespace-nowrap
                    {{ $tarjetaElegida == $key 
                        ? 'bg-gradient-to-r from-yellow-500 to-red-600 text-white shadow-[0_0_20px_rgba(255,165,0,0.6)] scale-105' 
                        : 'bg-white/10 backdrop-blur-md text-black dark:text-white border border-gray-400 hover:bg-gray-200' }}">
                    {{ $label }}
                </button>
                @endforeach
            </div>
        </div>
        <div class="mb-8 space-y-6">

            <div class="max-w-md mx-auto">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Buscar por nombre o apellido..."
                        class="w-full bg-white/5 backdrop-blur-md border border-white/20 text-black dark:text-white rounded-2xl py-3 pl-10 pr-4 focus:ring-2 focus:ring-yellow-500 focus:border-transparent outline-none transition-all placeholder-gray-500">
                </div>
            </div>


        </div>
        {{-- 📌 Versión Escritorio --}}
        <div class="hidden sm:block overflow-x-auto rounded-2xl shadow-[0_0_20px_rgba(0,0,0,0.3)]">
            <table class="w-full text-sm text-center text-white/90 bg-white/5 backdrop-blur-xl">

                <!-- CABECERA -->
                <thead
                    class="bg-gradient-to-r from-blue-600/60 to-gray-600/50 text-black dark:text-white uppercase text-xs border-b border-white/20">
                    <tr>
                        <th class="px-4 py-3 text-left">Jugador</th>
                        <th class="px-4 py-3 text-left">Equipo</th>
                        <th class="px-4 py-3 text-center">🟨 Amarilla</th>
                        <th class="px-4 py-3 text-center">🟨🟨 Doble</th>
                        <th class="px-4 py-3 text-center">🟥 Roja</th>
                    </tr>
                </thead>

                <!-- BODY -->
                <tbody class="backdrop-blur-xl">
                    @forelse($estadisticas as $stat)
                    <tr class="text-black dark:text-black border-b border-white/10 hover:bg-gray-300 transition
        odd:bg-gray-50 even:bg-gray-100">

                        <!-- JUGADOR -->
                        <td class=" px-4 py-3 text-left font-semibold tracking-wide">
                            {{ strtoupper($stat->apellido_jugador) }}, {{ strtoupper($stat->nombre_jugador) }}
                        </td>

                        <!-- EQUIPO -->
                        <td class="px-4 py-3 text-left text-black/80 dark:text-black">
                            {{ strtoupper($stat->nombre_equipo) }}
                        </td>

                        <!-- AMARILLA -->
                        <td class="py-3">
                            @if($stat->total_amarilla > 0)
                            <span
                                class="w-10 h-10 flex items-center justify-center mx-auto rounded-full bg-yellow-500 text-black font-bold shadow-[0_0_10px_rgba(255,200,0,0.6)]">
                                {{ $stat->total_amarilla }}
                            </span>
                            @else
                            <span class="text-black/50 dark:text-black">-</span>
                            @endif
                        </td>

                        <!-- DOBLE AMARILLA -->
                        <td class="py-3">
                            @if($stat->total_doble > 0)
                            <span
                                class="w-10 h-10 flex items-center justify-center mx-auto rounded-full bg-orange-500 text-white font-bold shadow-[0_0_10px_rgba(255,150,0,0.6)]">
                                {{ $stat->total_doble }}
                            </span>
                            @else
                            <span class="text-black/50 dark:text-black">-</span>
                            @endif
                        </td>

                        <!-- ROJA -->
                        <td class="py-3">
                            @if($stat->total_roja > 0)
                            <span
                                class="w-10 h-10 flex items-center justify-center mx-auto rounded-full bg-red-600 text-white font-bold shadow-[0_0_10px_rgba(255,0,0,0.6)]">
                                {{ $stat->total_roja }}
                            </span>
                            @else
                            <span class="text-black/50 dark:text-black">-</span>
                            @endif
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-black/70 dark:text-white/70 font-semibold">
                            No hay tarjetas registradas con el filtro seleccionado.
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- 📱 Versión Móvil --}}
        <div class="sm:hidden space-y-4">
            @forelse($estadisticas as $stat)
            <div
                class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl overflow-hidden shadow-[0_0_20px_rgba(0,0,0,0.3)] hover:bg-white/15 hover:shadow-[0_0_30px_rgba(255,200,0,0.4)] transition-all duration-300">

                <!-- Encabezado con nombre del jugador -->
                <div class="bg-gradient-to-r from-blue-600/60 to-gray-600/50 p-4 border-b border-white/20">
                    <h3 class="font-bold text-lg text-black dark:text-white">
                        {{ strtoupper($stat->apellido_jugador) }}, {{ strtoupper($stat->nombre_jugador) }}
                    </h3>
                    <p class="text-sm text-black/70 dark:text-white/70 mt-1">
                        {{ strtoupper($stat->nombre_equipo) }}
                    </p>
                </div>

                <!-- Contenido con tarjetas -->
                <div class="p-4">

                    <!-- Grid de tarjetas -->
                    <div class="grid grid-cols-3 gap-3">

                        <!-- Amarilla -->
                        <div class="bg-white/5 backdrop-blur-md rounded-xl p-3 border border-white/10 text-center">
                            <div class="text-3xl mb-2">🟨</div>
                            <span
                                class="block text-xs font-semibold text-black/70 dark:text-white/70 mb-2">Amarilla</span>
                            @if($stat->total_amarilla > 0)
                            <span
                                class="w-12 h-12 flex items-center justify-center mx-auto rounded-full bg-yellow-500 text-black font-bold text-lg shadow-[0_0_10px_rgba(255,200,0,0.6)]">
                                {{ $stat->total_amarilla }}
                            </span>
                            @else
                            <span class="text-2xl text-black/30 dark:text-white/30">-</span>
                            @endif
                        </div>

                        <!-- Doble Amarilla -->
                        <div class="bg-white/5 backdrop-blur-md rounded-xl p-3 border border-white/10 text-center">
                            <div class="text-3xl mb-2">🟨🟨</div>
                            <span class="block text-xs font-semibold text-black/70 dark:text-white/70 mb-2">Doble</span>
                            @if($stat->total_doble > 0)
                            <span
                                class="w-12 h-12 flex items-center justify-center mx-auto rounded-full bg-orange-500 text-white font-bold text-lg shadow-[0_0_10px_rgba(255,150,0,0.6)]">
                                {{ $stat->total_doble }}
                            </span>
                            @else
                            <span class="text-2xl text-black/30 dark:text-white/30">-</span>
                            @endif
                        </div>

                        <!-- Roja -->
                        <div class="bg-white/5 backdrop-blur-md rounded-xl p-3 border border-white/10 text-center">
                            <div class="text-3xl mb-2">🟥</div>
                            <span class="block text-xs font-semibold text-black/70 dark:text-white/70 mb-2">Roja</span>
                            @if($stat->total_roja > 0)
                            <span
                                class="w-12 h-12 flex items-center justify-center mx-auto rounded-full bg-red-600 text-white font-bold text-lg shadow-[0_0_10px_rgba(255,0,0,0.6)]">
                                {{ $stat->total_roja }}
                            </span>
                            @else
                            <span class="text-2xl text-black/30 dark:text-white/30">-</span>
                            @endif
                        </div>

                    </div>

                </div>

            </div>
            @empty
            <div
                class="text-center py-8 text-black/70 dark:text-white/70 font-semibold bg-white/5 backdrop-blur-md rounded-2xl border border-white/20 p-6">
                No hay tarjetas registradas con el filtro seleccionado.
            </div>
            @endforelse
        </div>

        {{-- 📄 Paginación (si la tienes) --}}
        @if(method_exists($estadisticas, 'hasPages') && $estadisticas->hasPages())
        <div class="mt-8">
            {{ $estadisticas->links() }}
        </div>
        @endif

    </div>

</div>