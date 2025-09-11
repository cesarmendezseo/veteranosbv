<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserList extends Component
{

    use WithPagination;

    public function render()
    {
        $users = User::with('roles.permissions', 'permissions')->paginate(10);
        return view('livewire.user-list', [
            'users' => $users,
        ]);
    }
}
