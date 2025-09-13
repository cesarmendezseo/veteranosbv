<header x-data="{ open: false }"
    class="bg-blue-900 text-white p-2 shadow-md rounded flex justify-between items-center relative z-10">
    <h1 class="text-lg font-bold">{!! $titulo ?? '' !!}</h1>

    {{-- Menú escritorio --}}
    <nav class="hidden md:flex space-x-4">
        {{ $slot }}

    </nav>
    <nav class="md:hidden  space-x-4">
        {{ $slot }}
    </nav>

</header>