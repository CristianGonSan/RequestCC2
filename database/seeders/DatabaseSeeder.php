<?php

namespace Database\Seeders;

use Database\Seeders\CostCentersSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PermissionNamesSeeder::class);
        //$this->call(TypeSeeder::class);
        //$this->call(CostCentersSeeder::class);
    }
}
