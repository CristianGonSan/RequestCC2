<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CostCentersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        require database_path('seeders/data/CostCentersData.php');

        DB::transaction(function () use ($costCenters) {
            $company = null;
            foreach ($costCenters as $costCenter) {
                $name = $costCenter[0] ?? 'Default';
                $description = $costCenter[1] ?? 'Default';

                if (!strpos($name, '-')) {
                    $company = Company::firstOrCreate(
                        ['name' => $description],
                        ['name' => $description]
                    );

                    $company->costCenters()->firstOrCreate(
                        ['name' => $name],
                        [
                            'name' => $name,
                            'description' => $description
                        ]
                    );
                } else {
                    $company?->costCenters()->firstOrCreate(
                        ['name' => $name],
                        [
                            'name' => $name,
                            'description' => $description
                        ]
                    );
                }
            }
        });
    }
}
