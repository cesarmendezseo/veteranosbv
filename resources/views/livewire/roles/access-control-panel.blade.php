<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 bg-purple-100 shawdon-xl rounded-b">
     <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Roles y Permisos') }}
        </h2>
        </x-slot>

    @if (session()->has('role_success') || session()->has('permission_success') || session()->has('assignment_success') || session()->has('role_error') || session()->has('permission_error') || session()->has('user_assignment_success') || session()->has('user_assignment_error'))
    <div class="mb-4">
        @if (session()->has('role_success') || session()->has('permission_success') || session()->has('assignment_success') || session()->has('user_assignment_success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
            <p>{{ session('role_success') ?? session('permission_success') ?? session('assignment_success') ?? session('user_assignment_success') }}</p>
        </div>
        @endif
        @if (session()->has('role_error') || session()->has('permission_error') || session()->has('user_assignment_error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
            <p>{{ session('role_error') ?? session('permission_error') ?? session('user_assignment_error') }}</p>
        </div>
        @endif
    </div>
    @endif

    <div class="border-b border-gray-500 mb-6 bg-slate-300 rounded-t-2xl">
        <ul class="flex flex-wrap -mb-px text-basic font-semibold text-center text-gray-900">
            @foreach($tabs as $tabId => $tabName)
            <li class="me-2">
                <a 
                    wire:click.prevent="$set('activeTab', '{{ $tabId }}')" 
                    href="#" 
                    class="inline-block p-4 border-b-2 rounded-t-lg {{ $activeTab === $tabId ? 'border-blue-600 text-blue-600' : 'border-transparent hover:text-gray-500 hover:border-accent' }}"
                >
                    {{ $tabName }}
                </a>
            </li>
            @endforeach
        </ul>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md">

        <div x-show="$wire.activeTab === 'create'">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Crear Roles y Permisos</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <form wire:submit.prevent="createRole" class="flex flex-col gap-4">
                    <input type="text" wire:model="roleName" placeholder="Nombre del nuevo rol" class="border p-2 border-gray-400 rounded w-full focus:ring-blue-500 focus:border-blue-500">
                    @error('roleName') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    <button type="submit" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600 transition-colors">Crear Rol</button>
                </form>
                <form wire:submit.prevent="createPermission" class="flex flex-col gap-4">
                    <input type="text" wire:model="permissionName" placeholder="Nombre del nuevo permiso" class="border p-2 border-gray-400 rounded w-full focus:ring-green-500 focus:border-green-500">
                    @error('permissionName') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    <button type="submit" class="bg-green-500 text-white p-2 rounded hover:bg-green-600 transition-colors">Crear Permiso</button>
                </form>
            </div>
        </div>

        <div x-show="$wire.activeTab === 'assign_permissions'">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Asignar Permisos a un Rol</h3>
            <form wire:submit.prevent="assignPermissionsToRole" class="flex flex-col gap-4">
                <select wire:model.live="selectedRole" class="border p-2 border-gray-400 rounded focus:ring-purple-500 focus:border-purple-500">
                    <option value="">Seleccione un Rol</option>
                    @foreach ($roles as $role)
                    <option value="{{ $role->id }}">{{ strtoupper($role->name) }}</option>
                    @endforeach
                </select>
                @error('selectedRole') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach ($permissions as $permission)
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->id }}" class="form-checkbox text-blue-600 rounded">
                        <span class="ml-2 text-sm text-gray-700">{{ $permission->name }}</span>
                    </label>
                    @endforeach
                </div>

                <button type="submit" class="bg-purple-500 text-white p-2 rounded hover:bg-purple-600 transition-colors">Asignar Permisos</button>
            </form>
        </div>

        <div x-show="$wire.activeTab === 'assign_roles'">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Asignar Rol a un Usuario</h3>
            <form wire:submit.prevent="assignRoleToUser" class="flex flex-col gap-4">
                <input type="text" wire:model.live="search" placeholder="Buscar usuario por nombre o email..." class="border p-2 border-gray-400 rounded w-full focus:ring-indigo-500 focus:border-indigo-500">

                <div class="bg-gray-300 max-h-70 overflow-y-auto border rounded-md">
                    @if($users->isEmpty() && $search !== '')
                    <p class="p-4 text-sm text-gray-500">No se encontraron usuarios.</p>
                    @else
                    <ul class="divide-y divide-gray-200 p-4">
                        @foreach($users as $user)
                        <li wire:click="$set('selectedUserId', {{ $user->id }})" class="p-4 border-gray-600 cursor-pointer hover:bg-gray-100 {{ $selectedUserId == $user->id ? 'bg-indigo-100' : '' }}">
                            <p class="font-semibold text-gray-800">{{ ucwords($user->name) }}</p>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>

                <select wire:model="selectedUserRole" class="border p-2 border-gray-400 bg-pink-200 rounded focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Seleccione un Rol</option>
                    @foreach ($roles as $role)
                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
                @error('selectedUserRole') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror

                <button type="submit" class="cursor-pointer bg-indigo-500 text-white p-2 rounded hover:bg-indigo-600 transition-colors">Asignar Rol</button>
            </form>
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
        
        <div x-show="$wire.activeTab === 'remove_roles'">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Quitar Roles a Usuarios</h3>
            <input type="text" wire:model.live="search" placeholder="Buscar usuario por nombre o email..." class="border border-gray-500 p-2 mb-2 rounded w-full focus:ring-indigo-500 focus:border-indigo-500">

            <div class="bg-gray-100 rounded-lg p-4">
                @foreach($users as $user)
                    <div class="border-b border-gray-200 pb-4 mb-4 last:border-b-0 last:pb-0">
                        <div class="flex items-center justify-between">
                            <p class="text-lg font-semibold text-gray-700">{{ $user->name }}</p>
                            <span class="text-sm text-gray-500">{{ $user->email }}</span>
                        </div>
        
                        <div class="mt-2 flex flex-wrap gap-2">
                            @forelse($user->roles as $role)
                                <div class="flex items-center space-x-2 bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-1 rounded-full">
                                    <span>{{ $role->name }}</span>
                                    <button 
                                        wire:click="removeRoleFromUser({{ $user->id }}, '{{ $role->name }}')"
                                        class="cursor-pointer text-red-600 hover:text-red-800 transition-colors duration-200 focus:outline-none"
                                        aria-label="Quitar rol"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            @empty
                                <span class="text-sm text-gray-500 italic">No tiene roles asignados.</span>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">
        {{ $users->links() }}
    </div>
        </div>

        <div x-show="$wire.activeTab === 'delete'">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Eliminar Roles y Permisos</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                    <h4 class="text-lg font-semibold mb-2 text-red-700">Eliminar Roles</h4>
                    <p class="text-sm text-red-600 mb-4">Solo puedes eliminar roles que no estén asignados a ningún usuario.</p>
                    <div class="flex flex-col gap-2">
                        @foreach ($roles as $role)
                            <div class="flex items-center justify-between p-2 bg-white rounded-md shadow-sm">
                                <span class="text-gray-800">{{ $role->name }}</span>
                                <button wire:click="deleteRole({{ $role->id }})" class="cursor-pointer text-red-500 hover:text-red-700 font-bold text-sm"
                                 >
                                    Eliminar
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                    <h4 class="text-lg font-semibold mb-2 text-red-700">Eliminar Permisos</h4>
                    <p class="text-sm text-red-600 mb-4">Solo puedes eliminar permisos que no estén asignados a ningún rol.</p>
                    <div class="flex flex-col gap-2">
                        @foreach ($permissions as $permission)
                            <div class="flex items-center justify-between p-2 bg-white rounded-md shadow-sm">
                                <span class="text-gray-800">{{ $permission->name }}</span>
                                <button wire:click="deletePermission({{ $permission->id }})" class="cursor-pointer text-red-500 hover:text-red-700 font-bold text-sm"
                                    >
                                    Eliminar
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
   
</div>