<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // Encontrar los roles recién creados y asignar permisos
        $roles = ['administrador_jumillano', 'administrador_lavazza', 'administrador_impacto'];

        foreach ($roles as $role) {
            $roleInstance = Role::findByName($role);

            // Asignar permisos específicos para el área correspondiente al rol
            $area = strtolower(str_replace('administrador_', '', $role));

            $productPermissions = ['create product', 'edit product', 'delete product', 'view product'];
            $userPermissions = ['create user', 'edit user', 'delete user', 'view user'];

            foreach ($productPermissions as $permission) {
                Permission::create(['name' => $permission . '_area_' . $area]);
                $roleInstance->givePermissionTo($permission . '_area_' . $area);
            }

            foreach ($userPermissions as $permission) {
                Permission::create(['name' => $permission . '_area_' . $area]);
                $roleInstance->givePermissionTo($permission . '_area_' . $area);
            }
        }
    }
}
