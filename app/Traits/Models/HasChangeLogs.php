<?php

namespace App\Traits\Models;

use App\Enums\Common\ChangeLogEvent;
use App\Models\Common\ChangeLog;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasChangeLogs
{
    public function changeLogs(): MorphMany
    {
        return $this->morphMany(ChangeLog::class, 'subject');
    }

    public function changeLogsCount(?ChangeLogEvent $event = null): int
    {
        $query = $this->changeLogs();

        if ($event !== null) {
            $query->where('event', $event);
        }

        return $query->count();
    }

    public function getEditedChangeLogsCountAttribute(): int
    {
        return $this->changeLogsCount(ChangeLogEvent::Edited);
    }
}
