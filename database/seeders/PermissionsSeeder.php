<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear permisos
        $permissions = [
            'Gestionar Solicitudes',
            'Gestionar Contabilidad',
            'Gestionar Usuarios',
            'Gestionar Roles',
            'Gestionar Permisos',
            'Gestionar Desarrollo',
            'Gestionar Configuraciones',
            'Ver Resumen',
            'Gestionar Centro de Costos',
            'Gestionar Tipos',
            'Gestionar Empresas'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission],
                ['name' => $permission]
            );
        }

        $role = Role::firstOrCreate(
            ['name' => 'Administrador'],
            ['name' => 'Administrador']
        );
        $role->givePermissionTo(Permission::all());

        $user = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrador',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('123456788'),
            ]
        );

        $user->assignRole($role);
    }
}
