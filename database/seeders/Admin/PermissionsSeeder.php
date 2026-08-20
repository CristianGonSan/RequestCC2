<?php

namespace Database\Seeders\Admin;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
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
            'manage_requests',
            'manage_accounting',
            'manage_users',
            'manage_roles',
            'manage_permissions',
            'manage_types',
            'manage_companies',
            'manage_cost_centers',
            'export',
            'view_summary',
            'manage_configurations',
            'manage_development',
            'manage_units',
            'manage_materials',
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
