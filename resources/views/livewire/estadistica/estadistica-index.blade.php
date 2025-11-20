<div>
    <div class="bg-blue-900 text-white p-2 shadow-md rounded flex justify-between items-center relative z-10"">
        <h2 class=" font-semibold text-xl text-gray-100 leading-tight">
        {{ __('Estadistica de Jugadores') }}
        </h2>

        <div class="relative z-0 w-55  group">

            <select wire:model.live="campeonatoId" id="countries"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">Seleccione Campeonato</option>
                @foreach ($campeonatos as $campeonato)
                <option value="{{ $campeonato->id }}">{{strtoupper( $campeonato->nombre) }}</option>
                @endforeach
            </select>
        </div>

    </div>

    <div class="hidden grid grid-cols-3 sm:flex sm:flex-wrap gap-2 mt-2">
        {{-- Goles --}}
        <button type="button" wire:click="$set('vistaActual', 'goleadores')"
            class="inline-flex flex-col items-center justify-center w-16 sm:w-auto px-2 py-2 text-xs sm:text-sm font-medium bg-white border border-gray-200 rounded-md shadow-sm hover:bg-gray-200 hover:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 sm:mr-2" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path d="M11.1 7.1a16.55 16.55 0 0 1 10.9 4" />
                <path d="M12 12a12.6 12.6 0 0 1-8.7 5" />
                <path d="M16.8 13.6a16.55 16.55 0 0 1-9 7.5" />
                <path d="M20.7 17a12.8 12.8 0 0 0-8.7-5 13.3 13.3 0 0 1 0-10" />
                <path d="M6.3 3.8a16.55 16.55 0 0 0 1.9 11.5" />
                <circle cx="12" cy="12" r="10" />
            </svg>
            <span class="mt-1 sm:mt-0">Goles</span>
        </button>

        {{-- Amarilla --}}
        <button type="button" wire:click="$set('vistaActual', 'amarillas')"
            class="inline-flex flex-col items-center justify-center w-16 sm:w-auto px-2 py-2 text-xs sm:text-sm font-medium bg-white border border-gray-200 rounded-md shadow-sm hover:bg-gray-200 hover:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
            <svg class="w-5 h-5" fill="yellow" viewBox="0 0 24 24">
                <path fill-rule="evenodd"
                    d="M4 4c0-.975.718-2 1.875-2h12.25C19.282 2 20 3.025 20 4v16c0 .975-.718 2-1.875 2H5.875C4.718 22 4 20.975 4 20V4Zm7 13a1 1 0 1 0 0 2h2a1 1 0 1 0 0-2h-2Z"
                    clip-rule="evenodd" />
            </svg>
            <span class="mt-1 sm:mt-0">Amarilla</span>
        </button>

        {{-- Doble Amarilla --}}
        <button type="button" wire:click="$set('vistaActual', 'dobleAmarillas')"
            class="inline-flex flex-col items-center justify-center w-16 sm:w-auto px-2 py-2 text-xs sm:text-sm font-medium bg-white border border-gray-200 rounded-md shadow-sm hover:bg-gray-200 hover:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
            <div class="flex">
                <svg class="w-5 h-5" fill="yellow" viewBox="0 0 24 24">
                    <path fill-rule="evenodd"
                        d="M4 4c0-.975.718-2 1.875-2h12.25C19.282 2 20 3.025 20 4v16c0 .975-.718 2-1.875 2H5.875C4.718 22 4 20.975 4 20V4Zm7 13a1 1 0 1 0 0 2h2a1 1 0 1 0 0-2h-2Z"
                        clip-rule="evenodd" />
                </svg>
                <svg class="w-5 h-5" fill="yellow" viewBox="0 0 24 24">
                    <path fill-rule="evenodd"
                        d="M4 4c0-.975.718-2 1.875-2h12.25C19.282 2 20 3.025 20 4v16c0 .975-.718 2-1.875 2H5.875C4.718 22 4 20.975 4 20V4Zm7 13a1 1 0 1 0 0 2h2a1 1 0 1 0 0-2h-2Z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <span class="mt-1 sm:mt-0 text-center">Doble Amarilla</span>
        </button>

        {{-- Roja --}}
        <button type="button" wire:click="$set('vistaActual', 'rojas')"
            class="inline-flex flex-col items-center justify-center w-16 sm:w-auto px-2 py-2 text-xs sm:text-sm font-medium bg-white border border-gray-200 rounded-md shadow-sm hover:bg-gray-200 hover:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
            <svg class="w-5 h-5" fill="red" viewBox="0 0 24 24">
                <path fill-rule="evenodd"
                    d="M4 4c0-.975.718-2 1.875-2h12.25C19.282 2 20 3.025 20 4v16c0 .975-.718 2-1.875 2H5.875C4.718 22 4 20.975 4 20V4Zm7 13a1 1 0 1 0 0 2h2a1 1 0 1 0 0-2h-2Z"
                    clip-rule="evenodd" />
            </svg>
            <span class="mt-1 sm:mt-0">Roja</span>
        </button>

        {{-- 5 Amarillas --}}
        <button type="button" wire:click="$set('vistaActual', '5amarillas')"
            class="inline-flex flex-col items-center justify-center w-16 sm:w-auto px-2 py-2 text-xs sm:text-sm font-medium bg-white border border-gray-200 rounded-md shadow-sm hover:bg-gray-200 hover:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
            <svg class="w-5 h-5" fill="yellow" viewBox="0 0 24 24">
                <path fill-rule="evenodd"
                    d="M4 4c0-.975.718-2 1.875-2h12.25C19.282 2 20 3.025 20 4v16c0 .975-.718 2-1.875 2H5.875C4.718 22 4 20.975 4 20V4Zm7 13a1 1 0 1 0 0 2h2a1 1 0 1 0 0-2h-2Z"
                    clip-rule="evenodd" />
            </svg>
            <span class="mt-1 sm:mt-0 text-center">5 Amarillas</span>
        </button>
        {{-- Sanciones --}}
        <button type="button" wire:click="$set('vistaActual', 'sanciones')"
            class="inline-flex flex-col items-center justify-center w-16 sm:w-auto px-2 py-2 text-xs sm:text-sm font-medium bg-white border border-gray-200 rounded-md shadow-sm hover:bg-gray-200 hover:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
            <div class="flex flex-col items-center space-y-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-scale-icon lucide-scale">
                    <path d="m16 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z" />
                    <path d="m2 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z" />
                    <path d="M7 21h10" />
                    <path d="M12 3v18" />
                    <path d="M3 7h2c2 0 5-1 7-2 2 1 5 2 7 2h2" />
                </svg>
                <span class="text-xs sm:text-base">Sanciones</span>
            </div>
        </button>
    </div>
    {{-- Select en móvil --}}
    <div class="block sm:hidden mt-2">
        <select wire:model.live="vistaActual"
            class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-400 dark:bg-gray-800 dark:text-white dark:border-gray-600">
            <option value="goleadores">Goles</option>
            <option value="amarillas">Amarilla</option>
            <option value="dobleAmarillas">Doble Amarilla</option>
            <option value="rojas">Roja</option>
            <option value="5amarillas">5 Amarillas</option>
            <option value="sanciones">Sanciones</option>
        </select>
    </div>





    <flux:separator />
    @if ($campeonatoId)
    <div class="mt-4">
        @if ($vistaActual === 'goleadores')
        @livewire('estadistica.goleador',['campeonatoId' => $campeonatoId], key('goleadores-' . $campeonatoId))
        @elseif ($vistaActual === 'amarillas')
        @livewire('estadistica.tarjetas-amarilla', ['campeonatoId' => $campeonatoId], key('amarillas-' . $campeonatoId))
        @elseif ($vistaActual === 'dobleAmarillas')
        @livewire('estadistica.tarjetas-doble-amarilla', ['campeonatoId' => $campeonatoId], key('doble-amarillas' .
        $campeonatoId))
        @elseif ($vistaActual === 'rojas')
        @livewire('estadistica.tarjetas-roja', ['campeonatoId' => $campeonatoId], key('rojas-' . $campeonatoId))
        @elseif ($vistaActual === '5amarillas')
        @livewire('estadistica.cinco-amarillas', ['campeonatoId' => $campeonatoId], key('5amarillas-' .
        $campeonatoId))
        @elseif ($vistaActual === 'sanciones')
        @livewire('sanciones.sanciones-ver', ['campeonatoId' => $campeonatoId])
        @endif
    </div>
    @else
    <div class="mt-6 flex items-center justify-center text-center">
        <div
            class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded-lg shadow-md animate-pulse flex items-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v2m0 4h.01M12 5a7 7 0 100 14 7 7 0 000-14z" />
            </svg>
            <span class="font-semibold">Debe seleccionar un campeonato</span>
        </div>
    </div>
    @endif

</div>