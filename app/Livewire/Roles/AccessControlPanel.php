<?php

namespace App\Livewire\Roles;

use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;

class AccessControlPanel extends Component
{
    use WithPagination;

    public $roleName;
    public $permissionName;
    public $selectedRole;
    public $selectedPermissions = [];

    public $search = '';
    public $selectedUserId;
    public $selectedUserRole;

    protected $rules = [
        'roleName' => 'required|string|unique:roles,name',
        'permissionName' => 'required|string|unique:permissions,name',
        'selectedRole' => 'required|exists:roles,id',
        'selectedPermissions' => 'nullable|array',
        'selectedUserId' => 'required|exists:users,id',
        'selectedUserRole' => 'required|exists:roles,name',
    ];



    public function createRole()
    {
        $this->validate(['roleName' => $this->rules['roleName']]);

        Role::create(['name' => $this->roleName]);

        $this->roleName = '';
        session()->flash('role_success', '¡Rol creado exitosamente!');
    }

    public function createPermission()
    {
        $this->validate(['permissionName' => $this->rules['permissionName']]);

        Permission::create(['name' => $this->permissionName]);

        $this->permissionName = '';
        session()->flash('permission_success', '¡Permiso creado exitosamente!');
    }

    public function updatedSelectedRole($roleId)
    {
        if ($roleId) {
            $role = Role::with('permissions')->find($roleId);
            $this->selectedPermissions = $role->permissions->pluck('id')->toArray();
        } else {
            $this->selectedPermissions = [];
        }
    }

    public function assignPermissionsToRole()
    {
        $this->validate(['selectedRole' => $this->rules['selectedRole']]);

        $role = Role::find($this->selectedRole);

        if ($role) {
            $permissions = Permission::whereIn('id', $this->selectedPermissions)->get();
            $role->syncPermissions($permissions);
            session()->flash('assignment_success', 'Permisos asignados al rol exitosamente.');
            $this->reset(['selectedRole', 'selectedPermissions']);
        } else {
            session()->flash('assignment_error', 'Error: Rol no encontrado.');
        }
    }

    public function assignRoleToUser()
    {
        $this->validate([
            'selectedUserId' => $this->rules['selectedUserId'],
            'selectedUserRole' => $this->rules['selectedUserRole']
        ]);

        $user = User::find($this->selectedUserId);

        if ($user) {
            $user->syncRoles([$this->selectedUserRole]);
            session()->flash('user_assignment_success', 'Rol asignado al usuario exitosamente.');
            $this->reset(['selectedUserId', 'selectedUserRole']);
        } else {
            session()->flash('user_assignment_error', 'Error: Usuario no encontrado.');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.roles.access-control-panel', [
            'roles' => Role::all(),
            'permissions' => Permission::all(),
            'users' => User::where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%")
                ->with('roles', 'permissions')
                ->paginate(10),
        ]);
    }
}
