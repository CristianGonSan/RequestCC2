<?php

namespace Database\Factories\Catalogs;

use App\Models\Catalogs\Material;
use App\Models\Catalogs\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

class MaterialFactory extends Factory
{
    protected $model = Material::class;

    public function definition(): array
    {
        return [
            'name' => ucfirst($this->faker->words(3, true)).' '.Str::random(8),
            'code' => $this->faker->boolean(50)
                ? strtoupper(Str::random(3)).'-'.strtoupper(Str::random(3)).rand(100, 999)
                : null,
            'base_unit_id' => Unit::inRandomOrder()->value('id'),
            'description' => $this->faker->optional(0.7)->sentence(),
            'is_external' => $this->faker->boolean(30),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
