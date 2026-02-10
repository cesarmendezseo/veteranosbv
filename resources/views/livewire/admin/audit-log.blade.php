<div class="p-6 bg-white shadow-sm rounded-lg">
    <h2 class="text-2xl font-bold mb-4">Registro de Auditoría</h2>

    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Usuario</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Evento</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Modelo</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cambios</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($audits as $audit)
            <tr>
                <td class="px-4 py-2 text-sm">{{ $audit->user->name ?? 'Sistema' }}-{{ $audit->user->email ?? 'Sin email'   }}</td>
                
                <td class="px-4 py-2 text-sm">
                    <span class="px-2 py-1 rounded text-white {{ $audit->event == 'created' ? 'bg-green-500' : ($audit->event == 'updated' ? 'bg-blue-500' : 'bg-red-500') }}">
                        {{ strtoupper($audit->event) }}
                    </span>
                </td>
                <td class="px-4 py-2 text-sm">
                    {{ class_basename($audit->auditable_type) }} (ID: {{ $audit->auditable_id }})
                </td>
                <td class="px-4 py-2 text-xs">
                    <strong>Nuevos:</strong> 
                    <pre class="bg-gray-100 p-1 rounded">{{ json_encode($audit->new_values, JSON_PRETTY_PRINT) }}</pre>
                </td>
                <td class="px-4 py-2 text-sm text-gray-500">
                    {{ $audit->created_at->format('d/m/Y H:i') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $audits->links() }}
    </div>
</div>