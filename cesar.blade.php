<!-- BOTON GUARDAR Y VOLVER -->
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
        Guardar Imagen
    </button>
    <button type="submit" class="inline-flex items-center gap-2 mt-4  bg-blue-950 hover:bg-blue-800 text-white px-4 py-2 rounded ">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-save-icon lucide-save">
            <path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
            <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7" />
            <path d="M7 3v4a1 1 0 0 0 1 1h7" />
        </svg> <span>Guardar</span></button>
    <a href="{{route('campeonato.index')}}" class="inline-flex items-center gap-2 mt-4  bg-blue-950 hover:bg-blue-800 text-white px-4 py-2 rounded ">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <span>Volver</span>
    </a>
    <!-- //////////////////////////////////////// -->

    #[On('eliminar-campeonato')]
    public function eliminarCampeonato($id)
    {

    Campeonato::findOrFail($id)->delete();

    $this->dispatch('baja');
    $this->dispatch('refresh');
    }

    #[On('refresh')]
    public function refresh()
    {
    // No hace falta que pongas nada acá, con solo tener este método, Livewire vuelve a renderizar
    }

    wire:click="$dispatch('confirmar-baja', { id: '{{ $campeonato->id }}' })"

    @push('js')
    <script>
        document.addEventListener('livewire:initialized', () => {



            Livewire.on('confirmar-baja', ({
                id
            }) => {


                Swal.fire({
                    title: 'CUIDADO...',
                    text: "¿Estás seguro de borrar al el Campeonato?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, Borrar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('eliminar-campeonato', {
                            id: id
                        });
                    }
                });
            });

            Livewire.on('Baja', () => {
                Swal.fire(
                    '¡Baja exitosa!',
                    'El campeonato se ha borrado correctamente.',
                    'success'
                );
            });
            //para errroes de validación
            Livewire.on('alertaError', (event) => {
                const message = event.message; // Accede a los datos del evento
                Swal.fire({
                    icon: 'error',
                    title: 'Errores de validación',
                    text: message.replace(/\n/g, '\n'),
                    customClass: {
                        popup: 'text-sm'
                    }
                });
            });
        });
    </script>
    @endpush

    <!-- VALIDACION -->
    try {
    $this->validate([
    'documento' => 'required|string|max:20',
    'tipo_documento' => 'required|string|max:20',
    'nombre' => 'nullable|string|max:255',
    'apellido' => 'nullable|string|max:255',
    'nacimiento' => 'nullable|date',
    'num_socio' => 'nullable|integer|unique:jugadors,num_socio,' . $this->jugadorId,
    'telefono' => 'nullable|string|max:15',
    'email' => 'nullable|email|max:255',
    'direccion' => 'nullable|string|max:255',
    'ciudad' => 'nullable|string|max:255',
    'provincia' => 'nullable|string|max:255',
    'cod_pos' => 'nullable|string|max:10',
    'foto' => 'nullable|image|max:2048',
    'activo' => 'boolean'
    ], [
    'documento.required' => 'El campo documento es obligatorio.',
    'documento.max' => 'El documento no debe tener más de 20 caracteres.',
    'tipo_documento.required' => 'Debe seleccionar un tipo de documento.',
    'num_socio.unique' => 'El número de socio ya está en uso.',
    'email.email' => 'El formato del correo no es válido.',
    'foto.image' => 'La foto debe ser una imagen.',
    'foto.max' => 'La foto no debe superar los 2 MB.',
    ]);
    } catch (ValidationException $e) {
    $errores = collect($e->validator->errors()->all())->implode("\n");
    $this->dispatch('alertaError', message: $errores);
    throw $e; // opcional: si querés que también se muestren los errores normales
    }
    <!-- MODAL -->

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <div x-data="{ show: false }"
        x-show="show"
        x-on:static-modal.window="show = true"
        x-cloak
        class="shadow-2xs fixed inset-0 z-50 flex items-center justify-center bg-[rgba(0,0,0,0.5)] p-4 sm:p-6">

        <div class="bg-gradient-custom dark:bg-gray-800 rounded-xl shadow-2xl max-w-xl w-full p-6 sm:p-8 relative transform transition-all scale-100 opacity-100 ease-out duration-300
                max-h-[90vh] overflow-y-auto"> <!-- Clases añadidas aquí -->
            <!-- Botón de Cierre -->
            <button @click="show = false"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-200 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full p-1">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <h2 class="text-2xl font-extrabold mb-6 text-white dark:text-gray-100 text-center border-b pb-3 border-gray-200 dark:border-gray-700">
                Detalles del Campeonato
            </h2>

            @if($campeonatoSeleccionado)
            <div class="grid grid-cols-1 md:grid-cols-1 gap-y-4 gap-x-6 text-gray-700 dark:text-[#efb810]">

                <div class="grid grid-cols-1 md:grid-cols-1 gap-y-4 gap-x-6 text-[#efb810] font-semibold dark:text-gray-300">

                    <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                        <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Nombre Campeonato:</strong> {{ ucwords(strtolower($campeonatoSeleccionado->nombre)) }}
                    </p>
                    <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                        <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Cantidad de Grupos: </strong> {{ ucwords(strtolower($campeonatoSeleccionado->cantidad_grupos)) }}
                    </p>
                    <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                        <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Cantidad de Equipo x Grupo: </strong>{{$campeonatoSeleccionado->cantidad_equipos_grupo}}
                    </p>
                    <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                        <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Ptos Ganados:</strong> {{ ucwords(strtolower($campeonatoSeleccionado->puntos_ganado)) }}
                    </p>
                    <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                        <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Ptos Empatados:</strong> {{ ucwords(strtolower($campeonatoSeleccionado->puntos_empatado)) }}
                    </p>
                    <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                        <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Ptos Perdidos:</strong> {{ $campeonatoSeleccionado->puntos_perdido }}
                    </p>
                    <h4 class="text-1xl font-extrabold mb-6 text-white dark:text-gray-100 text-center border-b pb-3 border-gray-200 dark:border-gray-700">
                        Puntos por Tarjeta para Fair Play
                    </h4>
                    <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                        <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Puntos Tarjeta Amarilla:</strong> {{ ucwords(strtolower($campeonatoSeleccionado->puntos_tarjeta_amarilla)) }}
                    </p>
                    <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                        <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Puntos Doble Amarilla:</strong> {{ ucwords(strtolower($campeonatoSeleccionado->puntos_doble_amarilla)) }}
                    </p>
                    <p class="pb-2 mb-2 border-b border-gray-200 dark:border-gray-700">
                        <strong class="font-semibold text-gray-400 dark:text-[#efb810]">Puntos Tarjeta Roja:</strong> {{ ucwords(strtolower($campeonatoSeleccionado->puntos_tarjeta_roja)) }}
                    </p>
                    <h4 class="text-1xl font-extrabold mb-6 text-white dark:text-gray-100 text-center border-b pb-3 border-gray-200 dark:border-gray-700">
                        Criterios de Desempate
                    </h4>
                    <p class=">
                         <strong class=" font-semibold text-gray-400 dark:text-[#efb810]"></strong>

                        @foreach ($campeonatoSeleccionado->criterioDesempate as $criterio)

                    <div> Prioridad: {{ $criterio->orden }} - {{ ucwords(strtolower($criterio->criterio)) }}</div>
                    @endforeach
                    </p>

                </div>

                @else
                <p class="text-center text-gray-600 dark:text-gray-400 py-4">No se ha seleccionado ningún campeonato para mostrar.</p>
                @endif
            </div>
        </div>
    </div>