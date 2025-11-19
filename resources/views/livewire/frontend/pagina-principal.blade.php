<div>

    <!-- Hero -->
    <section
        class="lex flex-col bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6 mt-2 w-full max-w-7xl mx-auto min-h-screen overflow-visible">
        <!-- Partidos -->
        <section id="partidos" class="py-16 w-full mx-auto px-6 bg-cover bg-center bg-no-repeat"
            style="background-image: url('{{ asset('images/pagina-principal.jpg') }}')">
            {{--
            <livewire:frontend.proximos-partidos.proximos-partidos-index /> --}}
            <h1 class="font-semibold text-xl text-gray-100 leading-tight">{{ $tituloPrincipal }}</h1>


            @if ($mostrarTabla && $campeonatoSeleccionado)
            @livewire('frontend.tabla-posicion.tabla-posicion-resultados', ['campeonatoId' => $campeonatoSeleccionado])
            @endif
            {{-- @if ($mostrarTabla)
            @livewire('frontend.tabla-posicion.tabla-posicion-index')
            @endif --}}

            @if ($mostrarEncuentros)
            @livewire('frontend.proximos-partidos.proximos-partidos-index')
            @endif
        </section>
    </section>



</div>