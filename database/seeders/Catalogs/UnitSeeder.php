<?php

namespace Database\Seeders\Catalogs;

use App\Models\Catalogs\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            [
                'symbol' => 'kg',
                'name' => 'Kilogramo',
            ],
            [
                'symbol' => 't',
                'name' => 'Tonelada',
            ],
            [
                'symbol' => 'm³',
                'name' => 'Metro cúbico',
            ],
            [
                'symbol' => 'm²',
                'name' => 'Metro cuadrado',
            ],
            [
                'symbol' => 'm',
                'name' => 'Metro',
            ],
            [
                'symbol' => 'l',
                'name' => 'Litro',
            ],
            [
                'symbol' => 'gal',
                'name' => 'Galón',
            ],
            [
                'symbol' => 'pza',
                'name' => 'Pieza',
            ],
            [
                'symbol' => 'saco',
                'name' => 'Saco',
            ],
            [
                'symbol' => 'tambor',
                'name' => 'Tambor',
            ],
            [
                'symbol' => 'pipa',
                'name' => 'Pipa',
            ],
        ];

        foreach ($units as $unit) {
            Unit::firstOrCreate(
                ['symbol' => $unit['symbol']],
                $unit
            );
        }
    }
}
