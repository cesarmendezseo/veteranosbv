{{-- <div
    class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
    <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
</div>
<div class="ms-1 grid flex-1 text-start text-sm">
    <span class="mb-0.5 truncate leading-tight font-semibold">ACFVBV</span>
</div> --}}
<div class="flex items-center space-x-2 rtl:space-x-reverse">
    <img src="{{ asset('storage/' . \App\Models\Configuracion::get('logo')) }}" alt="Logo"
        class="h-10 w-10 rounded-full">
    <span class="text-xl font-bold text-gray-800 dark:text-white">
        {{ \App\Models\Configuracion::get('titulo', 'Sistema') }}
    </span>
</div>