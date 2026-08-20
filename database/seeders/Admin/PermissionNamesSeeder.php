<?php

namespace Database\Seeders\Admin;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionNamesSeeder extends Seeder
{
    /**
     * Mapa de nombre antiguo => nombre nuevo.
     * Ajusta los valores de la izquierda si en tu BD están en otro formato.
     */
    private array $replacements = [
        'Gestionar Solicitudes'      => 'manage_requests',
        'Gestionar Contabilidad'     => 'manage_accounting',
        'Gestionar Usuarios'         => 'manage_users',
        'Gestionar Roles'            => 'manage_roles',
        'Gestionar Permisos'         => 'manage_permissions',
        'Gestionar Tipos'            => 'manage_types',
        'Gestionar Empresas'         => 'manage_companies',
        'Gestionar Centro de Costos' => 'manage_cost_centers',
        'Exportar'                   => 'export',
        'Ver Resumen'                => 'view_summary',
        'Gestionar Configuraciones'  => 'manage_configurations',
        'Gestionar Desarrollo'       => 'manage_development',
    ];

    public function run(): void
    {
        $updated = 0;
        $skipped = 0;
        $notFound = [];

        DB::transaction(function () use (&$updated, &$skipped, &$notFound) {
            foreach ($this->replacements as $oldName => $newName) {
                $permission = Permission::where('name', $oldName)->first();

                if (! $permission) {
                    $notFound[] = $oldName;
                    continue;
                }

                if ($permission->name === $newName) {
                    $skipped++;
                    continue;
                }

                $permission->name = $newName;
                $permission->save();
                $updated++;
            }
        });

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Reporte en consola
        $this->command->info("✔ Permisos actualizados: {$updated}");
        $this->command->warn("  Permisos ya al día (sin cambios): {$skipped}");

        if (! empty($notFound)) {
            $this->command->error('  No encontrados en BD: ' . implode(', ', $notFound));
        }
    }
}
