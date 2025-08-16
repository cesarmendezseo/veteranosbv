<?php

namespace App\Livewire\Roles;

use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserRoleAssigner extends Component
{

    public $search = '';
    public $selectedUserId;
    public $selectedRole;

    public function assignRole()
    {
        $user = User::find($this->selectedUserId);
        if ($user && $this->selectedRole) {
            $user->syncRoles([$this->selectedRole]); // Reemplaza roles anteriores
        }
    }

    public function render()
    {
        return view('livewire.roles.user-role-assigner', [
            'users' => User::where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%")
                ->get(),
            'roles' => Role::all(),
        ]);
    }
}
