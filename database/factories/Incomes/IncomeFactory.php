<?php

namespace Database\Factories\Incomes;

use App\Models\Catalogs\CostCenter;
use App\Models\Catalogs\Type;
use App\Models\Incomes\Income;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Income>
 */
class IncomeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Income::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'        => User::inRandomOrder()->value('id'),
            'cost_center_id' => CostCenter::inRandomOrder()->value('id'),
            'type_id'        => Type::inRandomOrder()->value('id'),
            'concept'        => $this->faker->sentence(4),
            'payee'          => $this->faker->name(),
            'amount'         => $this->faker->randomFloat(2, 100, 50000),
            'is_transfer'    => false,
            'bank'           => null,
            'card'           => null,
            'account'        => null,
            'branch'         => null,
            'reference'      => null,
            'covenant'       => null,
            'income_date'    => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * State for transfer incomes with bank details.
     */
    public function transfer(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_transfer' => true,
            'bank'        => $this->faker->company(),
            'card'        => $this->faker->creditCardNumber(),
            'account'     => $this->faker->numerify('##########'),
            'branch'      => (string) $this->faker->randomNumber(4, true),
            'reference'   => $this->faker->bothify('REF-####??'),
            'covenant'    => $this->faker->bothify('COV-#####'),
        ]);
    }
}
