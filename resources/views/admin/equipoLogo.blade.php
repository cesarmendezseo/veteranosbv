<x-layouts.app :title="__('upload logo')">
    <div class="max-w-md mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-4">Subir logo para: {{ $equipo->nombre }}</h1>

        @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        @if($equipo->logo)
        <div class="mb-4">
            <img src="{{ asset('storage/' . $equipo->logo) }}" alt="Logo {{ $equipo->nombre }}" class="max-w-xs">
        </div>
        @endif

        <form action="{{ route('equipo.logo.guardar', $equipo->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="logo" class="block mb-2">Seleccione logo (imagen):</label>
                <input type="file" name="logo" id="logo" accept="image/*" class="border rounded p-2 w-full">
                @error('logo')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Subir logo</button>
            <a href="{{route('equipo.index')}}" class="bg-blue-600 text-white px-4 py-2 rounded">Volver</a>
        </form>

    </div>
</x-layouts.app>