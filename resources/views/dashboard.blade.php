<x-layouts.app :title="__('Dashboard')">

    <div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 bg-slate-200 p-4 rounded-2xl shadow-2xl">
        <!-- Tarjeta Jugadores -->
         <a href="{{ route('jugadores.index') }}" 
            class="bg-white rounded-2xl shadow-md p-6 flex items-center justify-between 
                    hover:shadow-xl hover:scale-105 transition transform duration-300 ease-in-out">
                <div>
                    <h2 class="text-lg font-semibold text-gray-700">Jugadores</h2>
                </div>
                <div>
                    <img src="{{ asset('images/jugador.jpg') }}" alt="Logo" class="w-20 h-20 rounded-xl">
                </div>
        </a>


        <!-- Tarjeta Fixture -->
        <a href="{{ route('fixture.index') }}"  class="bg-white rounded-2xl shadow-md p-6 flex items-center justify-between 
                    hover:shadow-xl hover:scale-105 transition transform duration-300 ease-in-out">
            <div>
                <h2 class="text-lg font-semibold text-gray-700">Fixture</h2>
                
            </div>
            <div ">
     <img src="{{ asset('images/fixture.png') }}" alt="Logo" class="w-20 h-20 ">
            </div>
        </a>

        <!-- Tarjeta Estadísticas -->
        <a href="{{ route('sanciones.index') }}"  class="bg-white rounded-2xl shadow-md p-6 flex items-center justify-between 
                    hover:shadow-xl hover:scale-105 transition transform duration-300 ease-in-out">
            <div>
                <h2 class="text-lg font-semibold text-gray-700">Estadísticas</h2>
            
            </div>
             <div ">
     <img src="{{ asset('images/estadistica.png') }}" alt="Logo" class="w-20 h-20 rounded-2xl">
            </div>
        </a>
        <!-- Listado Buena Fe -->
        <a href="{{ route('listado-buena-fe') }}"  class="bg-white rounded-2xl shadow-md p-6 flex items-center justify-between 
                    hover:shadow-xl hover:scale-105 transition transform duration-300 ease-in-out">
            <div>
                <h2 class="text-lg font-semibold text-gray-700">Listado Buena Fe</h2>
            
            </div>
             <div ">
     <img src="{{ asset('images/listado.png') }}" alt="Logo" class="w-20 h-20 rounded-2xl">
            </div>
        </a>
        <!-- Tabla posicion -->
        <a href="{{ route('tabla-posiciones') }}"  class="bg-white rounded-2xl shadow-md p-6 flex items-center justify-between 
                    hover:shadow-xl hover:scale-105 transition transform duration-300 ease-in-out">
            <div>
                <h2 class="text-lg font-semibold text-gray-700">Tabla de Posición</h2>
            
            </div>
             <div ">
     <img src="{{ asset('images/tabla-posicion.png') }}" alt="Logo" class="w-20 h-20 rounded-2xl">
            </div>
        </a>
        <!-- Altas y Bajas -->
        @adminOrCan('comision')
        <a href="{{ route('altas-bajas.index') }}"  class="bg-white rounded-2xl shadow-md p-6 flex items-center justify-between 
                    hover:shadow-xl hover:scale-105 transition transform duration-300 ease-in-out">
            <div>
                <h2 class="text-lg font-semibold text-gray-700">Altas y Bajas</h2>
            
            </div>
             <div ">
     <img src="{{ asset('images/altas-bajas.png') }}" alt="Logo" class="w-20 h-20 rounded-2xl">
            </div>
        </a>
        @endadminOrCan

        <!-- Puedes seguir agregando más tarjetas según las opciones del menú -->
    </div>
</div>
<footer class="mt-10 bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-300 rounded-2xl">
    <div class="max-w-7xl mx-auto px-6 py-8 flex flex-col md:flex-row items-center justify-between">
        
        <!-- Texto -->
        <p class="text-sm">
            © {{ date('Y') }} <span class="font-semibold text-orange-500">Futbol de Veteranos Bella Vista</span>. Todos los derechos reservados.
        </p>

 <!-- Línea divisoria -->
    <div class="border-t border-gray-300 dark:border-gray-700 mt-6"></div>

    <!-- Créditos del diseñador -->
    <div class="text-center py-4 text-sm text-gray-500 dark:text-gray-400">
        Diseñado y desarrollado por 
        <span class="font-semibold text-orange-500">César Méndez</span>
    </div>
     
    </div>
</footer>

</x-layouts.app>