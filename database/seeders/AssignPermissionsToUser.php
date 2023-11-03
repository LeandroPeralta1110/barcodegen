<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class AssignPermissionsToUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Encuentra el usuario al que deseas asignar permisos
        $user = User::where('email', 'leandroemmanuel_99@outlook.es')->first();

        // Encuentra los permisos que deseas asignar al usuario
        $permissions = Permission::whereIn('name', [
            'create product',
            'edit product',
            'delete product',
            'view product',
            'create user',
            'edit user',
            'delete user',
            'view user',
        ])->get();

        // Asigna los permisos al usuario
        $user->givePermissionTo($permissions);
    }
}
