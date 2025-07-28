<div>
    <div class="max-w-xl mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-4">Editar Equipo</h1>

        <form wire:submit.prevent="actualizarEquipo" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" wire:model="nombre" class="w-full border rounded px-3 py-2">
                @error('nombre') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Repetir campos -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Ciudad</label>
                <input type="text" wire:model="ciudad" class="w-full border rounded px-3 py-2">
            </div>

            <!-- Agregá los demás campos igual -->

            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Guardar</button>
            <button type="button" wire:click="volver" class="px-4 py-2 bg-blue-500 text-white rounded">Volver</button>

        </form>
    </div>
</div>