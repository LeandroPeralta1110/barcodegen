<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdditionalRolesPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Encontrar el rol "administrador" y obtener sus permisos
        $adminRole = Role::findByName('administrador');
        $adminPermissions = $adminRole->permissions()->pluck('name')->toArray();

        // Encontrar los roles recién creados y asignar los mismos permisos que "administrador"
        $roles = ['administrador_jumillano', 'administrador_lavazza', 'administrador_impacto'];

        foreach ($roles as $role) {
            $roleInstance = Role::findByName($role);

            // Asignar los mismos permisos que "administrador"
            $roleInstance->givePermissionTo($adminPermissions);
        }
    }
}
