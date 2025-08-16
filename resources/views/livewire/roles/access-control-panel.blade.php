<!-- resources/views/livewire/access-control-panel.blade.php -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">

    <!-- Crear roles y permisos -->
    <div class="space-y-4">
        <h2 class="text-lg font-bold">Crear Rol</h2>
        <input type="text" wire:model="roleName" placeholder="Nombre del rol class=" class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 sm:text-sm" />

        <button wire:click="createRole" class="cursor-pointer inline-flex items-center gap-2 mt-4  bg-blue-950 hover:bg-blue-800 text-white px-4 py-2 rounded ">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-save-icon lucide-save">
                <path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
                <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7" />
                <path d="M7 3v4a1 1 0 0 0 1 1h7" />
            </svg><span>Crear Rol</span></button>

        <h2 class="text-lg font-bold">Crear Permiso</h2>
        <input type="text" wire:model="permissionName" class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 sm:text-sm" placeholder="Nombre del permiso" />
        <button wire:click="createPermission" class="cursor-pointer inline-flex items-center gap-2 mt-4  bg-blue-950 hover:bg-blue-800 text-white px-4 py-2 rounded ">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-save-icon lucide-save">
                <path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
                <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7" />
                <path d="M7 3v4a1 1 0 0 0 1 1h7" />
            </svg><span>Crear Permiso</span></button>
    </div>

    <!-- Asignar permisos a roles -->
    <div class="space-y-4">
        <h2 class="text-lg font-bold">Asignar Permisos a Rol</h2>
        <select wire:model="selectedRole" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option value="">-- Selecciona un rol --</option>
            @foreach($roles as $role)
            <option value="{{ $role->id }}">{{ $role->name }}</option>
            @endforeach
        </select>

        <div>
            @foreach($permissions as $perm)
            <div class="flex flex-col items-start gap-3">
                <label>
                    <input type="checkbox" wire:model="selectedPermissions" value="{{ $perm->name }}" class="size-5 rounded border-gray-300 shadow-sm mb-3">
                    <span class="font-medium text-gray-700"> {{ ucwords($perm->name) }}</span>
                </label>
            </div>
            @endforeach
        </div>

        <button wire:click="assignPermissionsToRole" class="cursor-pointer inline-flex items-center gap-2 mt-4  bg-blue-950 hover:bg-blue-800 text-white px-4 py-2 rounded ">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-save-icon lucide-save">
                <path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
                <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7" />
                <path d="M7 3v4a1 1 0 0 0 1 1h7" />
            </svg><span>Asignar Permisos</span></button>
    </div>

    <!-- Asignar roles a usuarios -->
    <div class="col-span-2 space-y-4">
        <h2 class="text-lg font-bold">Asignar Rol a Usuario</h2>
        <input type="text" wire:model="search" placeholder="Buscar usuario por nombre o email" class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 sm:text-sm" />

        <select wire:model="selectedUserId" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option value="">-- Selecciona un usuario --</option>
            @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
            @endforeach
        </select>

        <select wire:model="selectedUserRole" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option value="">-- Selecciona un rol --</option>
            @foreach($roles as $role)
            <option value="{{ $role->name }}">{{ $role->name }}</option>
            @endforeach
        </select>

        <button wire:click="assignRoleToUser" class="cursor-pointer inline-flex items-center gap-2 mt-4  bg-blue-950 hover:bg-blue-800 text-white px-4 py-2 rounded ">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-save-icon lucide-save">
                <path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
                <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7" />
                <path d="M7 3v4a1 1 0 0 0 1 1h7" />
            </svg><span>Asignar Rol</span></button>

        @if($selectedUserId)
        @php
        $user = $users->firstWhere('id', $selectedUserId);
        @endphp
        <div class="mt-4">
            <h3 class="font-semibold">Rol actual: {{ $user?->getRoleNames()->first() ?? 'Sin rol' }}</h3>
            <h3 class="font-semibold">Permisos: {{ implode(', ', $user?->getPermissionNames()->toArray() ?? []) }}</h3>
        </div>
        @endif
    </div>

</div>