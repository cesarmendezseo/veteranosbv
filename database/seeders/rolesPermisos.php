<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class rolesPermisos extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        // Crear roles
        $adminRole = Role::firstOrCreate(['name' => 'administrador']);
        $userRole  = Role::firstOrCreate(['name' => 'user']);

        // Crear superusuario
        $superAdmin = User::firstOrCreate(
            ['email' => 'cesarmendez.seo@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('C3tr0g4r*30'),
            ]
        );

        // Asignar rol
        if (!$superAdmin->hasRole('administrador')) {
            $superAdmin->assignRole($adminRole);
        }
    }
}
