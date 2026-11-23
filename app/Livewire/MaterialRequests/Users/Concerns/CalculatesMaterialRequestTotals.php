<?php

namespace App\Livewire\MaterialRequests\Users\Concerns;

use App\Enums\Requests\ChargeType;

trait CalculatesMaterialRequestTotals
{
    /**
     * @param  array<string|int, array<string, mixed>>  $items
     * @return array{lineTotals: array<string|int, string>, subtotal: string, chargeAmount: string, total: string}
     */
    private function calculateTotals(array $items, ChargeType $chargeType): array
    {
        $subtotal  = '0.00';
        $lineTotals = [];

        foreach ($items as $key => $item) {
            $quantity = $this->normalizeDecimal($item['quantity_requested'] ?? null, 3);
            $unitCost = $this->normalizeDecimal($item['unit_cost'] ?? null, 2);

            $lineTotal = $quantity === null || $unitCost === null
                ? '0.00'
                : bcmul($quantity, $unitCost, 2);

            $lineTotals[$key] = $lineTotal;
            $subtotal = bcadd($subtotal, $lineTotal, 2);
        }

        $chargeTotals = $this->calculateChargeTotals($subtotal, $chargeType);

        return [
            'lineTotals'   => $lineTotals,
            'subtotal'     => $subtotal,
            'chargeAmount' => $chargeTotals['chargeAmount'],
            'total'        => $chargeTotals['total'],
        ];
    }

    /**
     * @return array{chargeAmount: string, total: string}
     */
    private function calculateChargeTotals(string $subtotal, ?ChargeType $chargeType): array
    {
        $chargeAmount = $chargeType === null
            ? '0.00'
            : bcmul($subtotal, bcdiv((string) $chargeType->percentage(), '100', 2), 2);

        return [
            'chargeAmount' => $chargeAmount,
            'total'        => bcadd($subtotal, $chargeAmount, 2),
        ];
    }

    private function normalizeDecimal(mixed $value, int $maxDecimals): ?string
    {
        if (! \is_string($value) && ! \is_int($value) && ! \is_float($value)) {
            return null;
        }

        $value = trim((string) $value);

        if (! preg_match('/^(?:\d+(?:\.\d*)?|\.\d+)$/D', $value)) {
            return null;
        }

        $fraction = explode('.', $value, 2)[1] ?? '';

        if (\strlen($fraction) > $maxDecimals) {
            return null;
        }

        if (str_starts_with($value, '.')) {
            $value = "0$value";
        }

        if (str_ends_with($value, '.')) {
            $value = substr($value, 0, -1);
        }

        return $value;
    }
}
