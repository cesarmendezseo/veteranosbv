<div class="overflow-x-auto">
    <div class="mb-4">
        <input 
            wire:model.live="search" 
            type="text" 
            placeholder="Buscar por ID, evento o usuario..." 
            class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500"
        />
    </div>
    <table class="min-w-full table-auto border-collapse">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-left">Evento</th>
                <th class="px-4 py-2 text-left">Modelo / Afectado</th>
                <th class="px-4 py-2 text-left">Cambios (Valores Nuevos)</th>
                <th class="px-4 py-2 text-left">Usuario</th>
            </tr>
        </thead>
        <tbody>
            @foreach($audits as $audit)
            <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-2">
                    <span class="px-2 py-1 rounded text-xs font-bold 
                        {{ $audit->event == 'created' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ strtoupper($audit->event) }}
                    </span>
                </td>
                
                <td class="px-4 py-2">
                    <div class="font-bold text-sm">
                        {{ class_basename($audit->auditable_type) }}
                    </div>
                    <div class="text-xs text-gray-500">
                        {{-- Intentamos mostrar el nombre si el modelo lo tiene, sino el ID --}}
                        ID: {{ $audit->auditable_id }} 
                        @if($audit->auditable && isset($audit->auditable->nombre))
                            - <strong>{{ $audit->auditable->nombre }}</strong>
                        @elseif($audit->auditable && isset($audit->auditable->name))
                            - <strong>{{ $audit->auditable->name }}</strong>
                        @endif
                    </div>
                </td>

                <td class="px-4 py-2">
                    <div class="grid grid-cols-1 gap-1">
                        @foreach($audit->new_values as $key => $value)
                            @if(!is_null($value) && $key !== 'id')
                                <div class="text-xs">
                                    <span class="text-gray-400 font-semibold">{{ strtoupper(str_replace('_', ' ', $key)) }}:</span>
                                    <span class="text-gray-800">
                                        {{-- Manejo de booleanos o fechas --}}
                                        @if(is_bool($value))
                                            {{ $value ? 'SÍ' : 'NO' }}
                                        @else
                                            {{ $value }}
                                        @endif
                                    </span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </td>

                <td class="px-4 py-2 text-sm text-gray-600">
                    {{ $audit->user->name ?? 'Sistema' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $audits->links() }}
    </div>
</div>