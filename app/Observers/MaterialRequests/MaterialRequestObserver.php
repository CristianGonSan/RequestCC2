<?php

namespace App\Observers\MaterialRequests;

use App\Enums\Common\ChangeLogEvent;
use App\Models\Common\ChangeLog;
use App\Models\MaterialRequests\MaterialRequest;
use Illuminate\Support\Facades\Auth;

class MaterialRequestObserver
{
    public function updated(MaterialRequest $materialRequest): void
    {
        $fields = ChangeLog::getChangesArray($materialRequest);

        if ($fields === []) {
            return;
        }

        ChangeLog::create([
            'user_id'      => Auth::id(),
            'subject_type' => $materialRequest->getMorphClass(),
            'subject_id'   => $materialRequest->getKey(),
            'event'        => \array_key_exists('status', $fields)
                ? ChangeLogEvent::StatusChanged
                : ChangeLogEvent::Edited,
            'details' => $fields,
        ]);
    }
}
