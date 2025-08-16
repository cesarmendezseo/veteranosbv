<!-- resources/views/livewire/user-role-assigner.blade.php -->
<div class="space-y-6">
    <h2 class="text-xl font-bold">Asignar Rol a Usuario</h2>

    <input type="text" wire:model="search" placeholder="Buscar usuario por nombre o email" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2
                border-gray-300 appearance-none dark:text-white dark:border-gray-600
                dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer">

    <select wire:model="selectedUserId" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        >
        <option value="">-- Selecciona un usuario --</option>
        @foreach($users as $user)
        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
        @endforeach
    </select>

    <select wire:model="selectedRole" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        >
        <option value="">-- Selecciona un rol --</option>
        @foreach($roles as $role)
        <option value="{{ $role->name }}">{{ $role->name }}</option>
        @endforeach
    </select>

    <button wire:click="assignRole" class="inline-flex items-center gap-2 mt-4  bg-blue-950 hover:bg-blue-800 text-white px-4 py-2 rounded ">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-save-icon lucide-save">
            <path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
            <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7" />
            <path d="M7 3v4a1 1 0 0 0 1 1h7" />
        </svg> <span>Asignar Rol</span>
    </button>
</div>