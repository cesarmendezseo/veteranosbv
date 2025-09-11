<?php

namespace App\Livewire\Roles;

use App\Models\User;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\On;
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
    public $activeTab = 'create';

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

    /**
     * Elimina un rol específico de un usuario.
     */
    public function removeRoleFromUser(int $userId, string $roleName)
    {
        LivewireAlert::title('ATENCION')
            ->text('Esta por ELIMINAR un permiso, lo confirma?')
            ->asConfirm()
            ->confirmButtonText('Sí, Eliminar ')
            ->cancelButtonText('No, Cancelar')
            ->confirmButtonColor('#00A321') // Verde (Tailwind green-600)
            ->cancelButtonColor('#FF6600')  // Rojo (Tailwind red-600)
            ->warning()
            ->onConfirm('borrarRolUsuario', ['id' => $userId, 'roleName' => $roleName])
            ->onDeny('keepRolUsuario', ['id' => $userId])
            ->show();
    }


    public function borrarRolUsuario($data)
    {

        $userId = $data['id'];
        $roleName = $data['roleName'];

        $user = User::find($userId);

        if (!$user) {
            // session()->flash('user_error', 'Error: Usuario no encontrado.');
            LivewireAlert::title('Error')
                ->text('Usuario no encontrado..')
                ->error()
                ->toast()
                ->position('top')
                ->show();
            return;
        }

        if ($user->hasRole($roleName)) {
            $user->removeRole($roleName);
            // session()->flash('user_assignment_success', "Rol '{$roleName}' eliminado del usuario exitosamente.");
            LivewireAlert::title('Correcto')
                ->text('Rol ' . $roleName . ' eliminado del usuario exitosamente')
                ->success()
                ->toast()
                ->position('top')
                ->show();
        } else {
            //session()->flash('user_assignment_error', "El usuario no tiene el rol '{$roleName}'.");
            LivewireAlert::title('Error')
                ->text('El usuario no tiene el rol ' . $roleName . '...')
                ->error()
                ->toast()
                ->position('top')
                ->show();
        }
    }

    public function keepRolUsuario() {}


    public function deleteRole(int $roleId)
    {
        LivewireAlert::title('ATENCION')
            ->text('Esta por ELIMINAR un permiso, lo confirma?')
            ->asConfirm()
            ->confirmButtonText('Sí, Eliminar ')
            ->cancelButtonText('No, Cancelar')
            ->confirmButtonColor('#00A321') // Verde (Tailwind green-600)
            ->cancelButtonColor('#FF6600')  // Rojo (Tailwind red-600)
            ->warning()
            ->onConfirm('borrarRole', ['id' => $roleId])
            ->onDeny('keepRole', ['id' => $roleId])
            ->show();
    }


    public function borrarRole($data)
    {
        $roleId = $data['id'];
        $role = Role::find($roleId);

        if ($role) {
            if ($role->users()->count() > 0) {
                //session()->flash('role_error', 'No se puede eliminar el rol porque tiene usuarios asignados.');
                LivewireAlert::title('Error')
                    ->text('No se puede eliminar el rol porque tiene usuarios asignados..')
                    ->error()
                    ->toast()
                    ->position('top')
                    ->show();
                return;
            }

            $role->delete();
            LivewireAlert::title('OK')
                ->text('Rol eliminado..')
                ->success()
                ->toast()
                ->position('top')
                ->show();
        } else {
            // session()->flash('role_error', 'Error: Rol no encontrado.');
            LivewireAlert::title('Error')
                ->text('Rol no encontrado')
                ->error()
                ->toast()
                ->position('top')
                ->show();
        }
    }

    public function keepRole()
    {
        // si queremos hacer algo cuando el usuario dice no al querer borrar un rol
    }


    public function deletePermission(int $permissionId)
    {


        LivewireAlert::title('ATENCION')
            ->text('Esta por ELIMINAR un permiso, lo confirma?')
            ->asConfirm()
            ->confirmButtonText('Sí, Eliminar ')
            ->cancelButtonText('No, Cancelar')
            ->confirmButtonColor('#00A321') // Verde (Tailwind green-600)
            ->cancelButtonColor('#FF6600')  // Rojo (Tailwind red-600)
            ->warning()
            ->onConfirm('borrarPermiso', ['id' => $permissionId])
            ->onDeny('keepPermiso', ['id' => $permissionId])
            ->show();
    }
    public function borrarPermiso($data)
    {
        $permissionId = $data['id'];
        $permission = Permission::find($permissionId);

        if ($permission) {

            if ($permission->roles()->count() > 0) {
                //session()->flash('permission_error', 'No se puede eliminar el permiso porque está asignado a uno o más roles.');
                LivewireAlert::title('Error')
                    ->text('No se puede eliminar el permiso porque está asignado a uno o más roles..')
                    ->error()
                    ->toast()
                    ->position('top')
                    ->show();
                return;
            }

            $permission->delete();

            LivewireAlert::title('OK')
                ->text('Permiso Eliminado..')
                ->success()
                ->toast()
                ->position('top')
                ->show();
        } else {

            LivewireAlert::title('Error')
                ->text('Permiso no encontrado..')
                ->error()
                ->toast()
                ->position('top')
                ->show();
        }
    }

    public function keepPermiso() {}

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $tabs = [
            'create' => 'Crear',
            'assign_permissions' => 'Asignar Permisos',
            'assign_roles' => 'Asignar Roles',
            'remove_roles' => 'Lista Roles',
            'delete' => 'Eliminar',
        ];

        return view('livewire.roles.access-control-panel', [
            'roles' => Role::all(),
            'permissions' => Permission::all(),

            'users' => User::where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%")
                ->with('roles', 'permissions')
                ->paginate(10),
            'tabs' => $tabs,
        ]);
    }
}
