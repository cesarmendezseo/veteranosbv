<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
    <style>
        /* Agrega algunos estilos personalizados para los efectos */
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        /* Estilos para la imagen de fondo */
        .hero-background {
            background-image: url('{{ asset(' images/pagina-principal.webp') }}');
            /* Reemplaza con la URL de tu imagen */
            background-size: cover;
            /* Ajusta la imagen para cubrir todo el contenedor */
            background-position: center;
            /* Centra la imagen */
            background-repeat: no-repeat;
            /* Evita que la imagen se repita */
        }

        /* Estilos para los efectos de las tarjetas */
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        /* Estilos personalizados para el efecto de subrayado y el sticky */
        .nav-link::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -5px;
            width: 100%;
            height: 2px;
            background-color: #3b82f6;
            /* Color azul de Tailwind */
            transform: scaleX(0);
            transition: transform 0.3s ease-in-out;
            transform-origin: bottom right;
        }

        .nav-link:hover::after {
            transform: scaleX(1);
            transform-origin: bottom left;
        }

        .sticky-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col bg-white dark:bg-zinc-800">
    <!-- Navbar principal -->
    <nav
        class="fixed top-0 left-0 w-full z-50  hidden md:flex items-center justify-between px-6 py-4 bg-white dark:bg-zinc-900 shadow">
        <!-- Logo -->
        <a href="#" class="flex items-center  space-x-2 font-titulo text-lg">
            <img src="{{ asset('images/logo.jpeg') }}" alt="Logo del Club" class="h-10 w-10 rounded-full">
            <span class="text-xl font-bold text-gray-800 dark:text-white">A.C.F.V.B.V</span>
        </a>

        <!-- Enlaces de navegación -->
        <div class="space-x-8 hidden md:flex items-center font-titulo text-lg">
            <a href="{{ route('pagina-principal-index') }}"
                class="text-gray-600 dark:text-gray-300 hover:text-blue-600 transition duration-300 nav-link">Inicio</a>
            <a href="{{ route('tabla-posicion-resultados') }}"
                class="text-gray-600 dark:text-gray-300 hover:text-blue-600 transition duration-300 nav-link">Tabla de
                Posición</a>
            <a href="{{ route('frontend.goleadores.index') }}"
                class="text-gray-600 dark:text-gray-300 hover:text-blue-600 transition duration-300 nav-link">Goleadores</a>
            <a href="#"
                class="text-gray-600 dark:text-gray-300 hover:text-blue-600 transition duration-300 nav-link">Contacto</a>

            <!-- Autenticación -->
            @if (Route::has('login'))
            @auth
            <a href="{{ url('/dashboard') }}"
                class="text-gray-600 dark:text-gray-300 hover:text-blue-600 transition duration-300 flex items-center hover:underline">
                Dashboard
            </a>
            @else
            <a href="{{ route('login') }}"
                class="text-gray-600 dark:text-gray-300 hover:text-blue-600 transition duration-300 flex items-center space-x-1 hover:underline">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <span>Ingresar</span>
            </a>
            @endauth
            @endif
        </div>

    </nav>
    <!-- Navbar móvil -->
    <nav
        class="fixed top-0 left-0 w-full z-50 bg-white dark:bg-zinc-900 shadow px-4 py-3 flex items-center justify-between md:hidden">
        <!-- Logo -->
        <a href="#" class="flex items-center space-x-2">
            <img src="{{ asset('images/logo.jpeg') }}" alt="Logo del Club" class="h-10 w-10 rounded-full">
            <span class="text-xl font-bold text-gray-800 dark:text-white">A.C.F.V.B.V</span>
        </a>

        <!-- Botón de menú -->
        <button id="mobile-menu-toggle" class="text-gray-600 dark:text-gray-300 focus:outline-none">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
            </svg>
        </button>
    </nav>

    <!-- Menú desplegable -->
    <div id="mobile-menu"
        class="fixed top-16 left-0 w-full bg-white dark:bg-zinc-900 shadow-md px-4 py-4 space-y-4 hidden md:hidden z-40">
        <a href="{{ route('pagina-principal-index') }}"
            class="block text-gray-600 dark:text-gray-300 hover:text-blue-600 transition">Inicio</a>
        <a href="{{ route('tabla-posicion-resultados') }}"
            class="block text-gray-600 dark:text-gray-300 hover:text-blue-600 transition">Tabla de Posición</a>
        <a href="{{ route('frontend.goleadores.index') }}"
            class="block text-gray-600 dark:text-gray-300 hover:text-blue-600 transition">Goleador</a>
        <a href="#" class="block text-gray-600 dark:text-gray-300 hover:text-blue-600 transition">Contacto</a>

        @if (Route::has('login'))
        @auth
        <a href="{{ url('/dashboard') }}"
            class="block text-gray-600 dark:text-gray-300 hover:text-blue-600 transition">Dashboard</a>
        @else
        <a href="{{ route('login') }}"
            class="flex items-center space-x-2 text-gray-600 dark:text-gray-300 hover:text-blue-600 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
            <span>Ingresar</span>
        </a>
        @endauth
        @endif
    </div>
    </nav>

    <!-- Contenido dinámico -->
    <main class="flex-grow pt-[80px] px-6 w-full mx-auto">
        {{ $slot }}
    </main>
    <footer class="bg-[#042240] text-[#3c95B3] py-4 mb-8">
        <div class="container mx-auto text-center">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p>&copy; 2025 ACFVBV. Todos los derechos reservados.</p>
                {{-- <p class="mt-2"> Tel: +54 3777 - 111111</p> --}}
            </div>
            <!-- Agregado el "Diseñado por" -->
            <div class="hidden md:block">
                <p class="mt-2 text-sm text-[#3c95B3]">Diseñado por César Méndez</p>
            </div>
        </div>
    </footer>
    <div class="block sm:hidden fixed bottom-3 left-0 w-full  shadow-lg  z-50">
        <div class="flex justify-around items-center py-3 relative">

            <!-- Botón Home centrado y sobresalido -->
            <a href="{{ route('pagina-principal-index') }}"
                class="absolute -top-5 left-1/2 transform -translate-x-1/2
                  bg-blue-600 text-white w-14 h-14 rounded-full flex items-center justify-center shadow-xl border-4 border-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>

            </a>

            <!-- Otros ítems del menú -->
            {{-- <a href="/tabla" class="text-gray-600 text-sm">Tabla</a>
            <a href="/fixture" class="text-gray-600 text-sm">Fixture</a> --}}
            <span class="w-25"></span> <!-- espacio para el botón -->
            <a href="/goleadores" class="text-gray-600 text-sm"></a>
            <a href="#" class="text-gray-300 text-sm">Desing by CMendez</a>
        </div>
    </div>
    @livewireScripts
    @fluxScripts
    @RegisterServiceWorkerScript
    <!-- Script para toggle -->
    <script>
        document.getElementById('mobile-menu-toggle').addEventListener('click', function () {
    const menu = document.getElementById('mobile-menu');
    menu.classList.toggle('hidden');
  });
    </script>
</body>


</html>