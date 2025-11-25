<x-layouts.app :title="__('upload logo')">
    <div class="max-w-md mx-auto mt-10">

        <!-- Mensaje informativo -->
        <div class="flex items-center p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400"
            role="alert">
            <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <h1 class="font-medium underline decoration-blue-500">Cargar Imagen Principal</h1>
        </div>

        <form action="{{ route('configuracion.subir-imagen-principal') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Zona de carga -->
            <div
                class="flex items-center justify-center w-full border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-neutral-secondary-light">

                <label for="dropzone-file"
                    class="flex flex-col items-center justify-center w-full h-64 bg-neutral-secondary-medium 
                       border border-dashed border-default-strong rounded-base cursor-pointer hover:bg-neutral-tertiary-medium">

                    <!-- PREVIEW -->
                    <img id="preview-image" class="hidden h-full w-full object-cover rounded-lg" />

                    <!-- Icono + texto (solo visible si NO hay preview) -->
                    <div id="upload-info" class="flex flex-col items-center justify-center text-body pt-5 pb-6">
                        <svg class="w-8 h-8 mb-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h3a3 3 0 0 0 0-6h-.025a5.56 5.56 0 0 0 .025-.5A5.5 5.5 0 0 0 7.207 9.021C7.137 9.017 7.071 9 7 9a4 4 0 1 0 0 8h2.167M12 19v-9m0 0-2 2m2-2 2 2" />
                        </svg>
                        <p class="mb-2 text-sm"><span class="font-semibold">Click para subir</span> o arrastre aquí</p>
                        <p class="text-xs">PNG, JPG, JPEG — Máx. 2MB</p>
                    </div>

                    <input id="dropzone-file" type="file" class="hidden" name="fondo_pagina_principal"
                        accept="image/*" />
                </label>
            </div>

            <button class="cursor-pointer mt-2 px-4 py-2 bg-blue-600 text-white rounded w-full">
                Subir Imagen
            </button>

            @if(session('success'))
            <p class="text-green-600 mt-2 text-center">{{ session('success') }}</p>
            @endif
        </form>
    </div>

    <!-- SCRIPT DE PREVIEW -->
    <script>
        const fileInput = document.getElementById('dropzone-file');
        const preview = document.getElementById('preview-image');
        const uploadInfo = document.getElementById('upload-info');

        fileInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                uploadInfo.classList.add('hidden');
            };

            reader.readAsDataURL(file);
        });
    </script>
</x-layouts.app>