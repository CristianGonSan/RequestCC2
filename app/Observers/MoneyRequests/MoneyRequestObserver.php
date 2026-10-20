<?php

namespace App\Observers\MoneyRequests;

use App\Enums\Common\ChangeLogEvent;
use App\Models\Catalogs\CostCenter;
use App\Models\Catalogs\Type;
use App\Models\MoneyRequests\MoneyRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MoneyRequestObserver
{
    public function updating(MoneyRequest $moneyRequest): void
    {
        if ($moneyRequest->isDirty('status')) {
            $moneyRequest->paid_at = $moneyRequest->status->isPaid() ? Carbon::now() : null;
        }
    }

    public function updated(MoneyRequest $moneyRequest): void
    {
        $fields = [];

        $trackedFields = [
            'user_id',
            'cost_center_id',
            'type_id',
            'concept',
            'payee',
            'amount',
            'bank',
            'card',
            'account',
            'branch',
            'reference',
            'covenant',
            'status',
            'is_transfer',
            'paid_at',
        ];

        foreach ($trackedFields as $field) {
            $before = $moneyRequest->getRawOriginal($field);
            $after  = $moneyRequest->getAttributes()[$field] ?? null;

            if ($before != $after) {
                $fields[$field] = ['before' => $before, 'after' => $after];
            }
        }

        if (isset($fields['cost_center_id'])) {
            $beforeId = $fields['cost_center_id']['before'];
            $afterId  = $fields['cost_center_id']['after'];
            $names    = CostCenter::whereKey(array_filter([$beforeId, $afterId]))->pluck('name', 'id');

            $fields['cost_center_id']['before'] = $beforeId === null
                ? null
                : ['id' => $beforeId, 'name' => $names->get($beforeId)];
            $fields['cost_center_id']['after'] = $afterId === null
                ? null
                : ['id' => $afterId, 'name' => $names->get($afterId)];
        }

        if (isset($fields['type_id'])) {
            $beforeId = $fields['type_id']['before'];
            $afterId  = $fields['type_id']['after'];
            $names    = Type::whereKey(array_filter([$beforeId, $afterId]))->pluck('name', 'id');

            $fields['type_id']['before'] = $beforeId === null
                ? null
                : ['id' => $beforeId, 'name' => $names->get($beforeId)];
            $fields['type_id']['after'] = $afterId === null
                ? null
                : ['id' => $afterId, 'name' => $names->get($afterId)];
        }

        if ($fields === []) {
            return;
        }

        $event = \array_key_exists('status', $fields)
            ? ChangeLogEvent::StatusChanged
            : ChangeLogEvent::Edited;

        $editCount = $moneyRequest->changeLogs()
            ->where('event', ChangeLogEvent::Edited)
            ->count();

        if ($event === ChangeLogEvent::Edited) {
            $editCount++;
        }

        $moneyRequest->changeLogs()->create([
            'user_id' => Auth::id(),
            'event'   => $event,
            'details' => [
                'version'    => 1,
                'count_edit' => $editCount,
                'fields'     => $fields,
            ],
        ]);
    }
}
