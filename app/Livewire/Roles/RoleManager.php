<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleManager extends Component
{

    public $roleName;
    public $permissionName;
    public $selectedRole;
    public $selectedPermissions = [];

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

    public function assignPermissions()
    {
        $role = Role::find($this->selectedRole);
        $role->syncPermissions($this->selectedPermissions);
    }
    public function render()
    {
        return view('livewire.roles.role-manager', [
            'roles' => Role::all(),
            'permissions' => Permission::all(),
        ]);
    }
}
