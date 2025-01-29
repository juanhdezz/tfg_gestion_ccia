<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Elimina roles y permisos existentes para evitar duplicados
        //Permission::truncate();
        //Role::truncate();

        // Crear permisos
        $permissions = [
            'crear-usuarios',
            'editar-usuarios',
            'eliminar-usuarios',
            'ver-usuarios',
            'crear-artículos',
            'editar-artículos',
            'eliminar-artículos',
            'ver-artículos',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Crear rol admin y asignarle todos los permisos
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all()); // Asignar todos los permisos al rol
    }
}

