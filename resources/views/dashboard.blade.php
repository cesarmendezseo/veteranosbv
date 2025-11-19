<x-layouts.app :title="__('Dashboard')">
    <div class="p-4 bg-slate-200 rounded-2xl shadow-2xl dark:bg-gray-900">

        <div class="grid grid-cols-4 gap-3 mb-2">
            <!-- Tarjeta -->
            <a href="{{ route('jugadores.index') }}" class="h-30 sm:h-48 flex flex-col items-center justify-center bg-white rounded-2xl shadow-md p-3 
              hover:shadow-lg hover:scale-105 transition transform duration-300 ease-in-out text-center">
                <img src="{{ asset('images/jugador.jpg') }}" alt="Jugadores"
                    class="w-10 h-10 sm:w-18 sm:h-18 object-cover rounded-full mb-2">
                <span class="text-xs sm:text-base font-medium text-gray-700 leading-tight">Jugadores</span>
            </a>

            <a href="{{ route('fixture.index') }}" class="h-30 sm:h-48 flex flex-col items-center justify-center bg-white rounded-2xl shadow-md p-3 
              hover:shadow-lg hover:scale-105 transition transform duration-300 ease-in-out text-center">
                <img src="{{ asset('images/fixture.png') }}" alt="Fixture"
                    class="w-10 h-10 sm:w-18 sm:h-18 object-cover rounded-full mb-2">
                <span class="text-xs sm:text-base font-medium text-gray-700 leading-tight">Fixture</span>
            </a>

            <a href="{{ route('estadistica.index') }}" class="h-30 sm:h-48 flex flex-col items-center justify-center bg-white rounded-2xl shadow-md p-3 
              hover:shadow-lg hover:scale-105 transition transform duration-300 ease-in-out text-center">
                <img src="{{ asset('images/estadistica.png') }}" alt="Estadísticas"
                    class="w-10 h-10 sm:w-18 sm:h-18 object-cover rounded-full mb-2">
                <span class="text-xs sm:text-base font-medium text-gray-700 leading-tight">Estadísticas</span>
            </a>

            <a href="{{ route('sanciones.index') }}" class="h-30 sm:h-48 flex flex-col items-center justify-center bg-white rounded-2xl shadow-md p-3 
              hover:shadow-lg hover:scale-105 transition transform duration-300 ease-in-out text-center">
                <img src="{{ asset('images/sanciones.jpg') }}" alt="Sanciones"
                    class="w-10 h-10 sm:w-18 sm:h-18 object-cover rounded-full mb-2">
                <span class="text-xs sm:text-base font-medium text-gray-700 leading-tight">Sanciones</span>
            </a>
        </div>
        <div class="grid grid-cols-4 gap-3">
            <a href="{{ route('listado-buena-fe') }}" class="h-30 sm:h-48 flex flex-col items-center bg-white rounded-2xl shadow-md p-3 
              hover:shadow-xl hover:scale-105 transition transform duration-300 ease-in-out text-center">
                <img src="{{ asset('images/listado.png') }}" alt="Listado Buena Fe"
                    class="w-10 h-10 sm:w-18 sm:h-18 object-cover rounded-full mb-2">
                <h2 class="text-xs sm:text-base font-medium text-gray-700 leading-tight">Listado Buena Fe</h2>
            </a>

            <a href="{{ route('tabla-posiciones') }}" class="h-30 sm:h-48 flex flex-col items-center bg-white rounded-2xl shadow-md p-3
              hover:shadow-xl hover:scale-105 transition transform duration-300 ease-in-out text-center">
                <img src="{{ asset('images/tabla-posicion.png') }}" alt="Tabla de Posición"
                    class="w-10 h-10 sm:w-18 sm:h-18 object-cover rounded-full mb-2">
                <h2 class="text-xs sm:text-base font-medium text-gray-700 leading-tight">Tabla de Posición</h2>
            </a>

            @adminOrCan('comision')
            <a href="{{ route('altas-bajas.index') }}" class="h-30 sm:h-48 flex flex-col items-center bg-white rounded-2xl shadow-md p-3 
              hover:shadow-xl hover:scale-105 transition transform duration-300 ease-in-out text-center">
                <img src="{{ asset('images/altas-bajas.png') }}" alt="Altas y Bajas"
                    class="w-10 h-10 sm:w-18 sm:h-18 object-cover rounded-full mb-2">
                <h2 class="text-xs sm:text-base font-medium text-gray-700 leading-tight">Historial</h2>
            </a>
            @endadminOrCan

        </div>
    </div>
    <!-- 📱 Menú inferior fijo -->


</x-layouts.app>