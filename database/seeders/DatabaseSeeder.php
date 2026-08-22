<?php

namespace Database\Seeders;

use Database\Factories\Catalogs\MaterialFactory;
use Database\Seeders\Catalogs\UnitSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UnitSeeder::class);
        MaterialFactory::new()->count(20)->create();

        // $this->call(PermissionsSeeder::class);
        // $this->call(PermissionNamesSeeder::class);
        // $this->call(TypeSeeder::class);
        // $this->call(CostCentersSeeder::class);
    }
}
