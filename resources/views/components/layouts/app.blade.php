@auth
    <x-layouts.app.sidebar :title="$title ?? 'Futbol de Veteranos'">
        <flux:main>
            <div x-data="{ show: false }" x-init="window.addEventListener('scroll', () => { show = window.scrollY > 200 })" class="fixed bottom-3 right-3 z-50">
                <button x-show="show" @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
                    class="p-3 rounded-full bg-blue-600 text-white shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400 "
                    x-transition>
                    <!-- Ícono de flecha hacia arriba -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                    </svg>
                </button>
            </div>
            @PwaHead
            {{ $slot }}
            @RegisterServiceWorkerScript
        </flux:main>
    </x-layouts.app.sidebar>
@else
    {{-- Si el usuario no está autenticado, solo renderiza el contenido sin el sidebar. --}}
    @PwaHead

    {{ $slot }}
    @RegisterServiceWorkerScript
@endauth
