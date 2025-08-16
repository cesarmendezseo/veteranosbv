<?php

namespace App\Livewire\Roles;

use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AccessControlPanel extends Component
{
    public $roleName, $permissionName;
    public $selectedRole, $selectedPermissions = [];

    public $search = '';
    public $selectedUserId, $selectedUserRole;

    public function createRole()
    {
        $this->validate(['roleName' => 'required|unique:roles,name']);
        Role::create(['name' => $this->roleName]);
        $this->roleName = '';
    }

    public function createPermission()
    {
        $this->validate(['permissionName' => 'required|unique:permissions,name']);
        Permission::create(['name' => $this->permissionName]);
        $this->permissionName = '';
    }

    public function assignPermissionsToRole()
    {
        $role = Role::find($this->selectedRole);
        $role?->syncPermissions($this->selectedPermissions);
    }

    public function assignRoleToUser()
    {
        $user = User::find($this->selectedUserId);
        if ($user && $this->selectedUserRole) {
            $user->syncRoles([$this->selectedUserRole]);
        }
    }

    public function render()
    {
        return view('livewire.roles.access-control-panel', [
            'roles' => Role::all(),
            'permissions' => Permission::all(),
            'users' => User::where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%")
                ->get(),
        ]);
    }
}
