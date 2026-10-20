<?php

namespace App\Enums\Common;

enum ChangeLogEvent: string
{
    case StatusChanged = 'status_changed';
    case Edited        = 'edited';

    public function label(): string
    {
        return match ($this) {
            self::StatusChanged => 'Cambio de estatus',
            self::Edited        => 'Edición',
        };
    }

    public function bootstrapColorClass(): string
    {
        return match ($this) {
            self::StatusChanged => 'success',
            self::Edited        => 'warning',
        };
    }
}
