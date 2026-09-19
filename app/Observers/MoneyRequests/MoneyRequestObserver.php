<?php

namespace App\Observers\MoneyRequests;

use App\Models\MoneyRequests\MoneyRequest;
use Carbon\Carbon;

class MoneyRequestObserver
{
    public function updating(MoneyRequest $moneyRequest): void
    {
        if ($moneyRequest->isDirty('status')) {
            $moneyRequest->paid_at = $moneyRequest->status->isPaid() ? Carbon::now() : null;
        }
    }
}
