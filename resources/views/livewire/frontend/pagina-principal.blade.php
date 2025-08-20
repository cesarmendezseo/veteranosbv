<div>

    <x-layouts.app.frontend>
        <!DOCTYPE html>
        <html lang="es">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Fútbol de Veteranos</title>
            <script src="https://cdn.tailwindcss.com"></script>
            <!-- Tipografías -->
            <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;600&display=swap" rel="stylesheet">
            <style>
                body {
                    font-family: 'Inter', sans-serif;
                }

                h1,
                h2,
                h3,
                h4,
                h5,
                h6 {
                    font-family: 'Bebas Neue', sans-serif;
                    letter-spacing: 1px;
                }
            </style>
        </head>

        <body class="bg-gray-50 text-gray-800">


            <!-- Hero -->
            <section class="w-full relative bg-[url('{{ asset('images/pagina-principal.jpg') }}')] bg-cover bg-center h-[100vh] flex items-center">
                <div class="bg-black bg-opacity-60 w-full h-full flex items-center">
                    <div class="max-w-3xl mx-auto px-6 text-center text-white">
                        <h2 class="text-6xl mb-4 text-[#FFD700] font-bold">Fútbol de Veteranos</h2>
                        <p class="text-xl mb-6 text-slate-100 font-bold">Pasión, amistad y competencia para siempre.</p>
                        <a href="#inscripcion"
                            class="px-6 py-3 bg-yellow-400 text-green-900 font-bold rounded-lg shadow hover:bg-yellow-500">
                            Próximos partidos
                        </a>
                    </div>
                </div>
            </section>


            <!-- Partidos -->
            <section id="partidos" class="py-16 max-w-6xl mx-auto px-6">
                <h2 class="text-4xl text-center mb-12">Próximos Partidos</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="bg-white shadow-lg rounded-xl p-6 text-center">
                        <h3 class="text-2xl mb-2">Veteranos Norte</h3>
                        <p class="text-gray-500 mb-4">vs</p>
                        <h3 class="text-2xl mb-4">Amigos del Sur</h3>
                        <p class="text-gray-700">Sábado 20 Ago - 18:00 hs</p>
                    </div>
                    <div class="bg-white shadow-lg rounded-xl p-6 text-center">
                        <h3 class="text-2xl mb-2">Unión 79</h3>
                        <p class="text-gray-500 mb-4">vs</p>
                        <h3 class="text-2xl mb-4">Veteranos Oeste</h3>
                        <p class="text-gray-700">Domingo 21 Ago - 17:00 hs</p>
                    </div>
                    <div class="bg-white shadow-lg rounded-xl p-6 text-center">
                        <h3 class="text-2xl mb-2">Barrio Centro</h3>
                        <p class="text-gray-500 mb-4">vs</p>
                        <h3 class="text-2xl mb-4">Deportivo Viejos</h3>
                        <p class="text-gray-700">Domingo 21 Ago - 19:00 hs</p>
                    </div>
                </div>
            </section>



            <!-- Contacto -->
            <section id="contacto" class="py-16 w-full  mx-auto px-6 bg-[#3561a7]">
                <div class="w-full max-w-[980px] mx-auto">
                    <h2 class="text-4xl text-center mb-12 text-slate-100">Contacto</h2>
                    <form class="grid gap-6">
                        <input type="text" placeholder="Nombre" class="p-4 rounded-lg border">
                        <input type="email" placeholder="Email" class="p-4 rounded-lg border">
                        <textarea placeholder="Mensaje" class="p-4 rounded-lg border"></textarea>
                        <button class="bg-[#0A2A5E] text-white px-6 py-3 rounded-lg hover:bg-[#3561a7]">Enviar</button>
                    </form>
                </div>
            </section>



        </body>

        </html>


    </x-layouts.app.frontend>
</div>