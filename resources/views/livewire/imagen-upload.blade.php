<div>
    @if (session()->has('message'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('message') }}</span>
    </div>
    @endif

    <form wire:submit.prevent="save">
        <div class="mb-4">
            <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Seleccionar Imagen:</label>
            <input type="file" id="image" wire:model="image" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @error('image') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
        </div>

        @if ($image)
        <div class="mb-4">
            <p class="text-gray-700 text-sm font-bold mb-2">Previsualización:</p>
            <img src="{{ $image->temporaryUrl() }}" class="max-w-xs h-auto border rounded shadow">
        </div>
        @endif

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Subir Imagen
        </button>
    </form>
</div>