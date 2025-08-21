<div>
    <h2 class="text-2xl font-bold mb-6">Panel de Control de Acceso</h2>

    @if (session()->has('role_success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
        <p>{{ session('role_success') }}</p>
    </div>
    @endif
    @if (session()->has('permission_success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
        <p>{{ session('permission_success') }}</p>
    </div>
    @endif
    @if (session()->has('assignment_success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
        <p>{{ session('assignment_success') }}</p>
    </div>
    @endif

    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h3 class="text-xl font-semibold mb-4">Crear Roles y Permisos</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <form wire:submit.prevent="createRole" class="flex flex-col gap-4">
                <input type="text" wire:model="roleName" placeholder="Nombre del nuevo rol" class="border p-2 rounded w-full">
                @error('roleName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                <button type="submit" class="bg-blue-500 text-white p-2 rounded">Crear Rol</button>
            </form>
            <form wire:submit.prevent="createPermission" class="flex flex-col gap-4">
                <input type="text" wire:model="permissionName" placeholder="Nombre del nuevo permiso" class="border p-2 rounded w-full">
                @error('permissionName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                <button type="submit" class="bg-green-500 text-white p-2 rounded">Crear Permiso</button>
            </form>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h3 class="text-xl font-semibold mb-4">Asignar Permisos a un Rol</h3>
        <form wire:submit.prevent="assignPermissionsToRole" class="flex flex-col gap-4">
            <select wire:model.live="selectedRole" class="border p-2 rounded">
                <option value="">Seleccione un Rol</option>
                @foreach ($roles as $role)
                <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
            @error('selectedRole') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach ($permissions as $permission)
                <label class="inline-flex items-center">
                    <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->id }}" class="form-checkbox text-blue-600 rounded">
                    <span class="ml-2">{{ $permission->name }}</span>
                </label>
                @endforeach
            </div>

            <button type="submit" class="bg-purple-500 text-white p-2 rounded">Asignar Permisos</button>
        </form>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h3 class="text-xl font-semibold mb-4">Asignar Rol a un Usuario</h3>
        <form wire:submit.prevent="assignRoleToUser" class="flex flex-col gap-4">
            <input type="text" wire:model.live="search" placeholder="Buscar usuario por nombre o email..." class="border p-2 rounded w-full">

            <div class="bg-gray-50 max-h-48 overflow-y-auto border rounded-md">
                @if($users->isEmpty() && $search !== '')
                <p class="p-4 text-sm text-gray-500">No se encontraron usuarios.</p>
                @else
                <ul class="divide-y divide-gray-200">
                    @foreach($users as $user)
                    <li wire:click="$set('selectedUserId', {{ $user->id }})" class="p-4 cursor-pointer hover:bg-gray-100 {{ $selectedUserId == $user->id ? 'bg-blue-100' : '' }}">
                        <p class="font-semibold">{{ $user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>

            <select wire:model="selectedUserRole" class="border p-2 rounded">
                <option value="">Seleccione un Rol</option>
                @foreach ($roles as $role)
                <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
            </select>
            @error('selectedUserRole') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            <button type="submit" class="bg-indigo-500 text-white p-2 rounded">Asignar Rol</button>
        </form>
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>