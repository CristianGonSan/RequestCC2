<?php

namespace Tests\Unit;

use App\Enums\Requests\ChargeType;
use App\Livewire\MaterialRequests\Users\Concerns\CalculatesMaterialRequestTotals;
use PHPUnit\Framework\TestCase;

class MaterialRequestTotalsTest extends TestCase
{
    public function test_it_calculates_unformatted_line_and_request_totals(): void
    {
        $calculator = new class
        {
            use CalculatesMaterialRequestTotals;

            public function calculate(array $items, ChargeType $chargeType): array
            {
                return $this->calculateTotals($items, $chargeType);
            }
        };

        $totals = $calculator->calculate([
            'first' => ['quantity_requested' => '1.235', 'unit_cost' => '2.99'],
            'second' => ['quantity_requested' => '2', 'unit_cost' => '1.10'],
            'incomplete' => ['quantity_requested' => '', 'unit_cost' => '5.00'],
        ], ChargeType::Iva);

        $this->assertSame([
            'lineTotals' => [
                'first' => '3.69',
                'second' => '2.20',
                'incomplete' => '0.00',
            ],
            'subtotal' => '5.89',
            'chargeAmount' => '0.94',
            'total' => '6.83',
        ], $totals);
    }
}
