<?php

namespace Database\Seeders;

use App\Models\Catalogs\Material;
use App\Models\Catalogs\Unit;
use App\Models\Incomes\Income;
use App\Models\MoneyRequests\MoneyRequest;
use Database\Seeders\Admin\PermissionsSeeder;
use Database\Seeders\Catalogs\CostCentersSeeder;
use Database\Seeders\Catalogs\TypeSeeder;
use Database\Seeders\Catalogs\UnitSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Income::factory()->count(1000)->create();
        //Income::factory()->count(1000)->transfer()->create();

        //MoneyRequest::factory()->count(1000)->create();
        //MoneyRequest::factory()->count(1000)->transfer()->create();

        Material::factory()->count(50)->create();
    }
}
