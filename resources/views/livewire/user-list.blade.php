<div>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h2 class="text-2xl font-bold mb-4">Lista de Usuarios y Permisos</h2>

            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nombre
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Roles y Permisos
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-2">
                                {{-- Mostrar roles --}}
                                <div class="flex items-center gap-2">
                                    <h4 class="font-bold text-sm text-gray-700">Roles:</h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($user->roles as $role)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $role->name }}
                                        </span>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Mostrar permisos de los roles --}}
                                <div class="flex items-center gap-2">
                                    <h4 class="font-bold text-sm text-gray-700">Permisos por Rol:</h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($user->roles as $role)
                                        @foreach ($role->permissions as $permission)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                            {{ $permission->name }}
                                        </span>
                                        @endforeach
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Mostrar permisos directos --}}
                                @if ($user->permissions->isNotEmpty())
                                <div class="flex items-center gap-2">
                                    <h4 class="font-bold text-sm text-gray-700">Permisos Directos:</h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($user->permissions as $permission)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $permission->name }}
                                        </span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>