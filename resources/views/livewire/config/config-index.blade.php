<div class="p-6 bg-white shadow rounded">
    <div class="bg-blue-900 text-white p-4 shadow-md rounded flex justify-between items-center relative z-10"">
        <h2 class=" font-semibold text-xl text-gray-100 leading-tight">
        {{ __('Configuración') }}
        </h2>
        <div>
            <a href="#" class="cursor-pointer hover:underline">Volver</a>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('copiar-listado-buena-fe') }}"
            class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100">
            <div class="flex justify-between items-center">
                <div>
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">Clonar Listado de Buena Fé</h5>
                    <p class="font-normal text-gray-700">Clonar listado de buena fe de campeonatos anterioes.</p>
                </div>
                <button
                    class="px-5 py-2.5 gap-4 text-sm font-medium text-white inline-flex items-center bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 cursor-pointer rounded-lg text-center shadow">
                    Clonar
                </button>
            </div>
        </a>
        <a href="#" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100">
            <div class="flex justify-between items-center">
                <div>
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">Copiar Sanciones</h5>
                    <p class="font-normal text-gray-700">Copiar sanciones de campeonatos anteriores.</p>
                </div>
                <button
                    class="px-5 py-2.5 gap-4 text-sm font-medium text-white inline-flex items-center bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 cursor-pointer rounded-lg text-center shadow">
                    Copiar
                </button>
            </div>
        </a>
        <a href="{{ route('config.PanelConfiguracion') }}"
            class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100">
            <div class="flex justify-between items-center">
                <div>
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">Configuración General</h5>
                    <p class="font-normal text-gray-700">Ajustes generales de la aplicación.</p>
                </div>
                <button wire:click="config.PanelConfiguracion"
                    class="px-5 py-2.5 gap-4 text-sm font-medium text-white inline-flex items-center bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 cursor-pointer rounded-lg text-center shadow">
                    cesar
                </button>
            </div>
        </a>
    </div>
</div>