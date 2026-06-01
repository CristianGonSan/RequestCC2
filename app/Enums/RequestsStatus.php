<?php

namespace App\Enums\Requests;

enum RequestsStatus: string
{
    case Pending  = 'S01';
    case Accepted = 'S02';
    case Rejected = 'S03';
    case Canceled = 'S04';
    case Paid     = 'S05';

    public function label(): string
    {
        return match ($this) {
            self::Pending  => 'Pendiente',
            self::Accepted => 'Aceptada',
            self::Rejected => 'Rechazada',
            self::Canceled => 'Cancelada',
            self::Paid     => 'Pagada',
        };
    }

    public function bootstrapClass(): string
    {
        return match ($this) {
            self::Pending  => 'warning',
            self::Accepted => 'success',
            self::Rejected => 'danger',
            self::Canceled => 'secondary',
            self::Paid     => 'info',
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

    public function isCanceled(): bool
    {
        return $this === self::Canceled;
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
}
