<?php

namespace App\Traits\Requests;

trait Status
{
    const STATUS_PENDING = 'S01';
    const STATUS_ACCEPTED = 'S02';
    const STATUS_REJECTED = 'S03';
    const STATUS_CANCELED = 'S04';
    const STATUS_PAID = 'S05';

    const STATUSES_TEXT = [
        self::STATUS_PENDING => 'Pendiente',
        self::STATUS_ACCEPTED => 'Aceptada',
        self::STATUS_REJECTED => 'Rechazada',
        self::STATUS_CANCELED => 'Cancelada',
        self::STATUS_PAID => 'Pagada'
    ];

    const STATUSES_BS_CLASS = [
        self::STATUS_PENDING => 'warning',
        self::STATUS_ACCEPTED => 'success',
        self::STATUS_REJECTED => 'danger',
        self::STATUS_CANCELED => 'secondary',
        self::STATUS_PAID => 'info'
    ];

    public function getStatusText(): string
    {
        return self::STATUSES_TEXT[$this->status] ?? 'Desconocido';
    }

    public function getStatusBSClass(): string
    {
        return self::STATUSES_BS_CLASS[$this->status] ?? 'secondary';
    }

    public function isStatus(string $status): bool
    {
        return $this->status === $status;
    }

    public static function getStatuses(): array
    {
        return self::STATUSES_TEXT;
    }

    public static function getStatusKeys(): array
    {
        return array_keys(self::STATUSES_TEXT);
    }



    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isAccepted(): bool
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELED;
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }
}
