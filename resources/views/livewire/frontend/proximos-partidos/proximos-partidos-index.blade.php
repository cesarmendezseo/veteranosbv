<div>
   <div class="bg-white rounded-2xl shadow-md p-6 mt-6">
    <p class="text-xl font-semibold text-gray-700 mb-4">PRÓXIMOS ENCUENTROS</p>

    
   <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse ($proximos as $partido)
        <div class="bg-gray-100 dark:bg-gray-900 rounded-xl shadow-lg p-5 flex flex-col gap-4">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-base font-bold text-gray-900 dark:text-white uppercase">
                {{ $partido->equipoLocal->nombre }}
            </p>
            
        </div>
        <div class="text-center text-gray-600 dark:text-gray-300">
            <span class="block text-xs">VS</span>
        </div>
        <div class="text-right">
            <p class="text-base font-bold text-gray-900 dark:text-white uppercase style="font-family: 'Inter', sans-serif;"">
                {{ $partido->equipoVisitante->nombre }}
            </p>
           
        </div>
    </div>

    <div class="flex items-center justify-between text-sm text-gray-700 dark:text-gray-300 border-t border-gray-300 dark:border-gray-700 pt-3">
        <span class="flex items-center gap-1">
            📅 {{ \Carbon\Carbon::parse($partido->fecha)->format('d/m/Y') }}
        </span>
        <span class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full">Próximo partido</span>
    </div>
</div>

    @empty
        <p class="text-gray-500 text-sm">No hay próximos partidos programados.</p>
    @endforelse
</div>

</div>

</div>
