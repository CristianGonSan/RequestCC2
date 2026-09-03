<?php

namespace App\Enums\Requests;

enum MaterialRequestStatus: string
{
    case Pending   = 'pending';
    case Accepted  = 'accepted';
    case Rejected  = 'rejected';
    case InProcess = 'in_process';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending   => 'Pendiente',
            self::Accepted  => 'Aceptada',
            self::Rejected  => 'Rechazada',
            self::InProcess => 'En proceso',
            self::Completed => 'Completada',
            self::Cancelled => 'Cancelada',
        };
    }

    public function bootstrapColorClass(): string
    {
        return match ($this) {
            self::Pending   => 'warning',
            self::Accepted  => 'success',
            self::Rejected  => 'danger',
            self::InProcess => 'info',
            self::Completed => 'primary',
            self::Cancelled => 'secondary',
        };
    }

    public function canChangeTo(self $newStatus): bool
    {
        $transitions = [
            self::Pending->value => [
                self::Accepted, self::Rejected, self::Cancelled,
            ],
            self::Accepted->value => [
                self::Pending, self::Rejected, self::InProcess, self::Cancelled,
            ],
            self::InProcess->value => [
                self::Completed, self::Cancelled,
            ],
            self::Rejected->value => [
                self::Pending,
            ],
            self::Completed->value => [],
            self::Cancelled->value => [],
        ];

        return \in_array($newStatus, $transitions[$this->value], true);
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

    public function isInProcess(): bool
    {
        return $this === self::InProcess;
    }

    public function isCancelled(): bool
    {
        return $this === self::Cancelled;
    }

    public function isCompleted(): bool
    {
        return $this === self::Completed;
    }

    public function colorClass()
    {
        return $this->bootstrapColorClass();
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
