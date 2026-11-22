<?php

namespace App\Enums\Requests;

enum ChargeType: string
{
    case Iva        = 'iva';
    case Commission = 'commission';

    public function label(): string
    {
        return match ($this) {
            self::Iva        => 'IVA',
            self::Commission => 'Comisión',
        };
    }

    public function labelWithRate(): string
    {
        return "{$this->label()} ({$this->chargeRate()}%)";
    }

    public function labelWithPercentage(): string
    {
        return "{$this->label()} ({$this->percentage()}%)";
    }

    public function percentage(): int
    {
        return match ($this) {
            self::Iva        => 16,
            self::Commission => 8,
        };
    }

    public function chargeRate(): string
    {
        return number_format($this->percentage(), 4, '.', '');
    }

    public function chargeValue(): string
    {
        return bcdiv((string) $this->percentage(), '100', 6);
    }

    public function chargeAmount(string $subtotal): string
    {
        return bcmul($subtotal, $this->chargeValue(), 2);
    }

    public function applyTo(string $subtotal): string
    {
        return bcadd($subtotal, $this->chargeAmount($subtotal), 2);
    }

    public function bootstrapColorClass(): string
    {
        return match ($this) {
            self::Iva        => 'primary',
            self::Commission => 'success',
        };
    }

    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }

        return $options;
    }

    public static function details(): array
    {
        $details = [];

        foreach (self::cases() as $case) {
            $details[$case->value] = [
                'label'      => $case->label(),
                'percentage' => $case->percentage(),
            ];
        }

        return $details;
    }
}
