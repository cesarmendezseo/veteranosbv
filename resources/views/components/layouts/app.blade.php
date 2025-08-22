@auth
<x-layouts.app.sidebar :title="$title ?? 'Futbol de Veteranos'">
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>
@else
{{-- Si el usuario no está autenticado, solo renderiza el contenido sin el sidebar. --}}

{{ $slot }}

@endauth