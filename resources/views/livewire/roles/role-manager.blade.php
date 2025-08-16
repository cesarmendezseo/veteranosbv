<!-- resources/views/livewire/role-manager.blade.php -->
<div class="space-y-6">
    <h2 class="text-xl font-bold">Crear Rol</h2>
    <input type="text" wire:model="roleName" placeholder="Nombre del rol" />
    <button wire:click="createRole">Crear Rol</button>

    <h2 class="text-xl font-bold">Crear Permiso</h2>
    <input type="text" wire:model="permissionName" placeholder="Nombre del permiso" />
    <button wire:click="createPermission">Crear Permiso</button>

    <h2 class="text-xl font-bold">Asignar Permisos a Rol</h2>
    <select wire:model="selectedRole">
        <option value="">-- Selecciona un rol --</option>
        @foreach($roles as $role)
        <option value="{{ $role->id }}">{{ $role->name }}</option>
        @endforeach
    </select>

    <div>
        @foreach($permissions as $perm)
        <label>
            <input type="checkbox" wire:model="selectedPermissions" value="{{ $perm->name }}">
            {{ $perm->name }}
        </label>
        @endforeach
    </div>

    <button wire:click="assignPermissions">Asignar Permisos</button>
</div>