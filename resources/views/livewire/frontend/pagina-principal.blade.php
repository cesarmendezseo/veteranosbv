<div>

    <!-- Hero -->
    <section class="w-full relative bg-cover bg-center h-[100vh] flex items-center"
        style="background-image: url('{{ asset('images/pagina-principal.jpg') }}')">

        <div class="bg-black bg-opacity-60 w-full h-full flex items-center">
            <div class="max-w-3xl mx-auto px-6 text-center text-white">
                <h2 class="text-6xl mb-4 text-[#FFD700] font-bold">Fútbol de Veteranos</h2>
                <p class="text-xl mb-6 text-slate-100 font-bold">Pasión, amistad y competencia para siempre.</p>
                <a href="#partidos"
                    class="px-6 py-3 bg-yellow-400 text-green-900 font-bold rounded-lg shadow hover:bg-yellow-500">
                    Próximos partidos
                </a>
            </div>
        </div>
    </section>


    <!-- Partidos -->
    <section id="partidos" class="py-16 max-w-6xl mx-auto px-6">
        <livewire:frontend.proximos-partidos.proximos-partidos-index />
    </section>



    <!-- Contacto -->
    <section id="contacto" class="py-16 w-full mb-2 mx-auto px-6 bg-[#3561a7]">

    </section>

</div>