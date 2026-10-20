<?php

namespace App\Observers\Incomes;

use App\Enums\Common\ChangeLogEvent;
use App\Models\Catalogs\CostCenter;
use App\Models\Catalogs\Type;
use App\Models\Incomes\Income;
use Illuminate\Support\Facades\Auth;

class IncomeObserver
{
    public function updated(Income $income): void
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
            'is_transfer',
            'income_date',
        ];

        foreach ($trackedFields as $field) {
            $before = $income->getRawOriginal($field);
            $after  = $income->getAttributes()[$field] ?? null;

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

        $editCount = $income->changeLogs()
            ->where('event', ChangeLogEvent::Edited)
            ->count() + 1;

        $income->changeLogs()->create([
            'user_id' => Auth::id(),
            'event'   => ChangeLogEvent::Edited,
            'details' => [
                'version'    => 1,
                'count_edit' => $editCount,
                'fields'     => $fields,
            ],
        ]);
    }
}
