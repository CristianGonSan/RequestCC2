<?php

namespace Database\Seeders;

use App\Models\Configuration;
use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $types = [
            [
                'key' => 'T01',
                'name' => 'Personal'
            ],
            [
                'key' => 'T02',
                'name' => 'Corporativa'
            ],
            [
                'key' => 'T03',
                'name' => 'Corporativa-Codias'
            ],
            [
                'key' => 'T04',
                'name' => 'Corporativa-Alisur'
            ],
            [
                'key' => 'T05',
                'name' => 'Corporativa-Petrosur'
            ],
            [
                'key' => 'T06',
                'name' => 'Corporativa-Ciz'
            ],
            [
                'key' => 'T07',
                'name' => 'Corporativa-Fizat'
            ],
            [
                'key' => 'T08',
                'name' => 'Corporativa-Edson'
            ],
            [
                'key' => 'T09',
                'name' => 'Corporativa-Tendencia'
            ],
            [
                'key' => 'T10',
                'name' => 'Corporativa-Decum'
            ]
        ];

        foreach ($types as $type) {
            Type::firstOrCreate(
                ['key' => $type['key']],  // Condición para verificar si existe
                [
                    'key' => $type['key'],
                    'name' => $type['name']
                ]  // Valores que se actualizan o crean
            );
        }
    }
}
