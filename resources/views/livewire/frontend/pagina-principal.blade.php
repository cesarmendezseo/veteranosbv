<div>
    @push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />

    <style>
        .font-oswald {
            font-family: 'Oswald', sans-serif;
        }
    </style>
    @endpush

    <x-layouts.app.frontend>
        <section class="bg-gray-100 dark:bg-gray-800 py-16">
            <div class="max-w-5xl mx-auto text-center px-4">
                <h1 class="text-4xl sm:text-5xl font-extrabold text-blue-700 dark:text-blue-500 mb-4 tracking-tight font-montserrat">
                    Fútbol de Veteranos
                </h1>
                <p class="text-lg text-gray-700 dark:text-gray-300 mb-8">
                    Organización, fixture y resultados en un solo lugar.
                </p>
                <a href="{{route('tabla-posicion-index')}}" class="inline-block bg-blue-700 text-white px-8 py-3 rounded-full hover:bg-blue-800 transition-all duration-300 transform hover:scale-105">
                    Ver Tabla de Posiciones
                </a>
            </div>
        </section>

        <section class="py-12 bg-white dark:bg-gray-900">
            <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 text-center px-4">
                <div class="p-8 bg-gray-50 dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="text-4xl mb-3">🏆</div>
                    <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-2 font-montserrat">
                        Campeonatos
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Explora torneos activos y pasados con todos los detalles.
                    </p>
                    <a href="#" class="text-blue-700 dark:text-blue-500 font-semibold hover:underline">
                        Ver más →
                    </a>
                </div>
                <div class="p-8 bg-gray-50 dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="text-4xl mb-3">📅</div>
                    <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-2 font-montserrat">
                        Fixture
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Encuentra las fechas, horarios y sedes de los próximos partidos.
                    </p>
                    <a href="#" class="text-blue-700 dark:text-blue-500 font-semibold hover:underline">
                        Ver más →
                    </a>
                </div>
                <div class="p-8 bg-gray-50 dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="text-4xl mb-3">⚽</div>
                    <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-2 font-montserrat">
                        Resultados
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Mantente al día con los últimos marcadores y sanciones.
                    </p>
                    <a href="#" class="text-blue-700 dark:text-blue-500 font-semibold hover:underline">
                        Ver más →
                    </a>
                </div>
            </div>
        </section>

        <section class="py-12 bg-gray-100 dark:bg-gray-800">
            <div class="max-w-4xl mx-auto text-center px-4">
                <h2 class="text-3xl font-bold text-blue-700 dark:text-blue-500 mb-4 font-montserrat">
                    Contacto
                </h2>
                <p class="text-gray-700 dark:text-gray-300 mb-2">
                    ¿Eres delegado o jugador? Escríbenos para registrar tu equipo.
                </p>
                <p class="text-gray-600 dark:text-gray-400 text-lg">
                    Email: <a href="mailto:contacto@ligaveteranos.com.ar" class="text-blue-700 dark:text-blue-500 hover:underline">contacto@ligaveteranos.com.ar</a>
                </p>
            </div>
        </section>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    </x-layouts.app.frontend>

</div>