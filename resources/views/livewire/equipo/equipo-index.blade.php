<div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-100 uppercase bg-gray-500 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Logo
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Nombre
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Ciudad
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Provincia
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Cod. Pos.
                    </th>

                    <th scope="col" class="px-6 py-3">
                        Descripción
                    </th>

                    <th scope="col" class="px-6 py-3">
                        Acción
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($equipos as $equipo)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        @if ($equipo->logo)
                        <img src="{{ asset('storage/' . $equipo->logo) }}" alt="Logo" class="h-16 w-16 rounded-full object-cover">
                        @else
                        Sin logo
                        @endif
                    </th>

                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ strtoupper($equipo->nombre) }}
                    </th>
                    <td class="px-6 py-4">
                        {{ strToupper($equipo->ciudad) }}
                    </td>
                    <td class="px-6 py-4">
                        {{ strToupper($equipo->provincia )}}
                    </td>
                    <td class="px-6 py-4">
                        {{ strToupper($equipo->cod_pos )}}
                    </td>
                    <td class="px-6 py-4">
                        {{ strToupper($equipo->descripcion )}}
                    </td>
                    <td class="px-6 py-4 text-right flex gap-2 justify-end">
                        {{-- Editar --}}
                        <a href="{{ route('equipo.editar', $equipo->id) }}"
                            class="text-white bg-gradient-to-r from-teal-400 via-teal-500 to-teal-600 hover:bg-gradient-to-br
              focus:ring-4 focus:outline-none focus:ring-teal-300 dark:focus:ring-teal-800
              shadow-lg shadow-teal-500/50 dark:shadow-lg dark:shadow-teal-800/80
              font-medium rounded-full text-sm
              h-10 w-10 flex items-center justify-center"
                            title="Editar">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                        </a>

                        {{-- Borrar --}}
                        <button wire:click="borrar({{ $equipo->id }})"
                            class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br
              focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800
              shadow-lg shadow-red-500/50 dark:shadow-lg dark:shadow-red-800/80
              font-medium rounded-full text-sm
              h-10 w-10 flex items-center justify-center"
                            title="Borrar">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </button>

                        {{-- Logo --}}
                        <a href="{{ route('equipo.logo.upload', $equipo->id) }}"
                            class="text-white bg-gradient-to-r from-purple-500 via-purple-600 to-purple-700 hover:bg-gradient-to-br
              focus:ring-4 focus:outline-none focus:ring-purple-300 dark:focus:ring-purple-800
              shadow-lg shadow-purple-500/50 dark:shadow-lg dark:shadow-purple-800/80
              font-medium rounded-full text-sm
              h-10 w-10 flex items-center justify-center"
                            title="Subir logo">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-camera-icon lucide-camera h-6 w-6">
                                <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z" />
                                <circle cx="12" cy="13" r="3" />
                            </svg>
                        </a>
                    </td>



                </tr>
                @endforeach


            </tbody>
        </table>
    </div>




</div>