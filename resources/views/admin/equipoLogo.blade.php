<x-layouts.app :title="__('upload logo')">
    <div class="max-w-md mx-auto mt-10">
        <!-- <h1 class="text-2xl font-bold mb-4"> <span class="underline decoration-blue-500">Subir logo para: </span> {{ strtoupper($equipo->nombre) }}</h1> -->
        <div class="flex items-center p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
            <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
            <div>
                <h1> <span class="font-medium underline decoration-blue-500">Cargar logo para</span>: {{ strtoupper($equipo->nombre) }}</h1>
            </div>
        </div>
        @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        @if($equipo->logo)
        <div class="mb-4 flex justify-center">
            <div class="w-32 h-32 rounded-full border-4 border-gray-300 shadow-lg overflow-hidden">
                <img src="{{ asset('storage/' . $equipo->logo) }}"
                    alt="Logo {{ $equipo->nombre }}"
                    class="w-full h-full object-cover" />
            </div>
        </div>
        @endif

        <form action="{{ route('equipo.logo.guardar', $equipo->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="flex flex-col items-center justify-center w-full">
                <label for="file-input" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">

                    <!-- Input único de archivo -->
                    <input type="file" id="file-input" name="logo" onchange="previewImage(event)" class="hidden" />

                    <!-- Imagen de vista previa -->
                    <img id="preview-image" src="#" alt="Vista previa"
                        class="w-32 h-32 object-cover rounded-full mb-4 hidden" />

                    <!-- Placeholder -->
                    <div id="upload-placeholder" class="animate-pulse flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                        </svg>
                        <p class="mb-2 text-sm text-gray-500">
                            <span class="font-semibold">Click para subir</span> o arrastrar archivo
                        </p>
                        <p class="text-xs text-gray-500">SVG, PNG, JPG o GIF (máx. 800x400px)</p>
                    </div>

                </label>
            </div>

            <div class="flex justify-between mt-4">
                <button type="submit" class="bg-blue-950 text-white px-4 py-2 rounded flex items-center gap-2 cursor-pointer hover:bg-blue-800">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hard-drive-upload">
                        <path d="m16 6-4-4-4 4" />
                        <path d="M12 2v8" />
                        <rect width="20" height="8" x="2" y="14" rx="2" />
                        <path d="M6 18h.01" />
                        <path d="M10 18h.01" />
                    </svg>
                    Guardar
                </button>

                <a href="{{ route('equipo.index') }}" class="bg-blue-950 text-white px-4 py-2 rounded flex items-center gap-2 hover:bg-blue-800"> <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Volver</a>
            </div>
        </form>

        <script>
            function previewImage(event) {
                const file = event.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-image').src = e.target.result;
                    document.getElementById('preview-image').classList.remove('hidden');
                    document.getElementById('upload-placeholder').classList.add('hidden');
                };

                reader.readAsDataURL(file);
            }
        </script>

    </div>
</x-layouts.app>