@auth
<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>
@endauth

@guest
<x-layouts.app.frontend :title="$title ?? null">


    {{ $slot }}

</x-layouts.app.frontend>
@endguest