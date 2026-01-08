<div class="space-y-6">

    @foreach ($campeonato->fases as $fase)
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">

        {{-- CABECERA DE FASE --}}
        <div class="px-4 py-2 bg-blue-900 text-white rounded-t-lg flex justify-between">
            <h3 class="font-semibold text-lg">
                {{ strtoupper($fase->nombre) }}
            </h3>

            <span class="text-sm italic">
                Estado: {{ ucfirst($fase->estado) }}
            </span>
        </div>

        {{-- CONTENIDO --}}
        <div class="p-4">
            @if ($fase->equipos->count())

            <table class="w-full text-sm text-left text-gray-700 dark:text-gray-200">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                    <tr>
                        <th class="px-2 py-1">#</th>
                        <th class="px-2 py-1">Equipo</th>
                        <th class="px-2 py-1">Fase origen</th>
                        <th class="px-2 py-1">Posición origen</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($fase->equipos->sortBy('pivot.posicion_origen') as $index => $equipo)
                    <tr class="border-b dark:border-gray-700">
                        <td class="px-2 py-1 font-semibold">
                            {{ $index + 1 }}
                        </td>

                        <td class="px-2 py-1">
                            {{ strtoupper($equipo->nombre) }}
                        </td>

                        <td class="px-2 py-1 text-sm italic">
                            {{ optional($equipo->pivot->faseOrigen)->nombre ?? '—' }}
                        </td>

                        <td class="px-2 py-1 text-center font-bold">
                            {{ $equipo->pivot->posicion_origen }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @else
            <div class="text-gray-500 italic">
                No hay equipos clasificados en esta fase.
            </div>
            @endif
        </div>

    </div>
    @endforeach

</div>