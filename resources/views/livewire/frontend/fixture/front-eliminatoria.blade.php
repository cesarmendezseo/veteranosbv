<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach ($campeonatos as $campeonato)
    <a href="{{ route('frontend.eliminatoria.ver', $campeonato->id) }}"
        class="block bg-white dark:bg-gray-800 border hover:bg-gray-500 border-gray-200 dark:border-gray-700 rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow duration-300 cursor-pointer">

        {{-- Nombre del campeonato --}}
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
            {{ ucwords($campeonato->nombre) }}
        </h2>

        {{-- Detalles del campeonato --}}
        <div class="space-y-1 text-gray-700 dark:text-gray-300 text-sm">
            <div><span class="font-semibold">Formato:</span>
                @if ($campeonato->formato === 'todos_contra_todos')
                Todos contra Todos
                @else
                {{ ucfirst($campeonato->formato) }}
                @endif
            </div>

            <div><span class="font-semibold">Cant. Equipos:</span>
                @if ($campeonato->formato === 'todos_contra_todos')
                @foreach ($campeonato->grupos as $grupo)
                <div>{{ $grupo->cantidad_equipos }}</div>
                @endforeach
                @else
                {{ $campeonato->cantidad_equipos_grupo }} x Grupo
                @endif
            </div>

            <div><span class="font-semibold">Categoría:</span>
                {{ ucfirst($campeonato->categoria->nombre) }}
            </div>
        </div>
    </a>
    @endforeach
</div>