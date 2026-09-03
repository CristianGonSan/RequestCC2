<?php

namespace App\Enums\Requests;

enum MoneyRequestStatus: string
{
    case Pending = 'S01';
    case Accepted = 'S02';
    case Rejected = 'S03';
    case Cancelled = 'S04';
    case Paid = 'S05';

    public function label(): string
    {
        return match ($this) {
            self::Pending   => 'Pendiente',
            self::Accepted  => 'Aceptada',
            self::Rejected  => 'Rechazada',
            self::Cancelled => 'Cancelada',
            self::Paid      => 'Pagada',
        };
    }

    public function bootstrapColorClass(): string
    {
        return match ($this) {
            self::Pending   => 'warning',
            self::Accepted  => 'success',
            self::Rejected  => 'danger',
            self::Cancelled => 'secondary',
            self::Paid      => 'info',
        };
    }

    public function isPending(): bool
    {
        return $this === self::Pending;
    }

    public function isAccepted(): bool
    {
        return $this === self::Accepted;
    }

    public function isRejected(): bool
    {
        return $this === self::Rejected;
    }

    public function isCancelled(): bool
    {
        return $this === self::Cancelled;
    }

    public function isPaid(): bool
    {
        return $this === self::Paid;
    }

    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }

        return $options;
    }

    /**
     * @param  self[]  $excluded
     */
    public static function exclude(array $excluded): array
    {
        $options = [];

        foreach (self::cases() as $status) {
            if (! \in_array($status, $excluded, true)) {
                $options[$status->value] = $status->label();
            }
        }

        return $options;
    }
}
