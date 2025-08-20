<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Futbol</title>
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        html,
        body {
            overflow-x: hidden;
            /* evita scroll horizontal */
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-[#0a0a0a] text-[#1b1b18] flex flex-col min-h-screen">
    <div class="flex-grow w-full">
        <div class="w-full max-w-screen-xl mx-auto px-4 overflow-x-hidden  id=" navbar-sticky"
            x-cloak
            :class="{ 'hidden': !open, 'flex': open }"">
            @push('styles')
            <style>
                [x-cloak] {
                    display: none !important;
                }
            </style>
            @endpush
            <nav
                x-data=" { open: false }"
            @popstate.window="open = false"
            class="bg-white dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600">
            <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
                <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
                    <img src="https://flowbite.com/docs/images/logo.svg" class="h-8" alt="Logo">
                    <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Futbol</span>
                </a>

                <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                    @if (Route::has('login'))
                    <div class="hidden md:flex items-center justify-end gap-4">
                        @auth
                        <a href="{{ url('/dashboard') }}" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Dashboard</a>
                        @else
                        <a href="{{ route('login') }}" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Log in</a>
                        @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Register</a>
                        @endif
                        @endauth
                    </div>
                    @endif
                    <button
                        @click="open = !open"
                        type="button"
                        class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                        aria-controls="navbar-sticky"
                        :aria-expanded="open.toString()">
                        <span class="sr-only">Open main menu</span>
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
                        </svg>
                    </button>
                </div>

                <div
                    id="navbar-sticky"
                    class="items-center justify-between w-full md:flex md:w-auto md:order-1     "
                    :class="{ 'hidden': !open, 'flex': open }">
                    <ul class="w-full flex flex-col p-4 md:p-0 mt-4 font-medium border  rounded-lg 
            bg-gray-200 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 
            md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                        <li>
                            <a href="{{route('pagina.principal')}}" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Inicio</a>
                        </li>
                        <li>
                            <a href="{{ route('tabla-posicion-index') }}"
                                class="
                            block py-2 px-3 rounded-sm 
                            {{ Route::is('tabla-posicion-index') ? 'text-white bg-blue-700 md:bg-transparent md:text-blue-700 md:p-0 md:dark:hover:text-blue-500' : 'text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent' }}
                        "
                                aria-current="{{ Route::is('tabla-posicion-index') ? 'page' : 'false' }}">
                                Tabla Posición
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('frontend.fixture.index') }}"
                                class="
                            block py-2 px-3 rounded-sm 
                            {{ Route::is('frontend.fixture.index') ? 'text-white bg-blue-700 md:bg-transparent md:text-blue-700 md:p-0 md:dark:hover:text-blue-500' : 'text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent' }}
                        "
                                aria-current="{{ Route::is('frontend.fixture.index') ? 'page' : 'false' }}">
                                Fixture
                            </a>
                        </li>
                        <li>
                            <a href="#" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Contact</a>
                        </li>
                        <flux:separator />
                        @if (Route::has('login'))
                        @auth
                        <li class="md:hidden">
                            <a href="{{ url('/dashboard') }}" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent" x-on:click="open = false">Dashboard</a>
                        </li>
                        @else
                        <li class="md:hidden">
                            <a href="{{ route('login') }}" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent" x-on:click="open = false">Log in</a>
                        </li>
                        @if (Route::has('register'))
                        <li class="md:hidden">
                            <a href="{{ route('register') }}" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent" x-on:click="open = false">Register</a>
                        </li>
                        @endif
                        @endauth
                        @endif
                    </ul>
                </div>
            </div>
            </nav>
        </div>
        <div class="bg-gray-100 dark:bg-[#0a0a0a] pt-18 text-[#1b1b18] flex flex-col min-h-screen w-screen">
            <div class="w-full max-w-screen-xl mx-auto px-4 overflow-x-hidden">
                {{ $slot }}
            </div>
            <footer class="text-center p-4">
                &copy; 2025 Futbol de Veteranos Bella Vista. Síguenos en <a href="#">Redes Sociales</a>
            </footer>

        </div>
    </div>
    @livewireScripts
    @stack('js')
    @fluxScripts

</body>

</html>