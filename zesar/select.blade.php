<select id="formato" wire:model.live="formato"
    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
    <option value="">-- Selecciona una opción --</option>
    <option value="todos_contra_todos">Todos contra todos</option>
    <option value="grupos">Por grupos</option>
    {{-- AÑADIDO: Opciones para eliminación directa --}}
    <option value="eliminacion_simple">Eliminación Simple</option>
    <option value="eliminacion_doble">Doble Eliminación</option>
</select>