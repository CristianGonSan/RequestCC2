<?php

namespace App\Observers\MaterialRequests;

use App\Models\MaterialRequests\MaterialRequestFulfillment;
use App\Models\MaterialRequests\MaterialRequestItem;

class MaterialRequestFulfillmentObserver
{
    public function created(MaterialRequestFulfillment $fulfillment): void
    {
        $this->recalculate($fulfillment);
    }

    public function updated(MaterialRequestFulfillment $fulfillment): void
    {
        $this->recalculate($fulfillment);
    }

    public function deleted(MaterialRequestFulfillment $fulfillment): void
    {
        $this->recalculate($fulfillment);
    }

    private function recalculate(MaterialRequestFulfillment $fulfillment): void
    {
        $item = MaterialRequestItem::findOrFail($fulfillment->material_request_item_id);

        $item->quantity_fulfilled = $item->fulfillments()->sum('quantity');
        $item->save();

        $materialRequest = $item->materialRequest;
        $materialRequest->total_spent = $materialRequest->fulfillments()->sum('cost');
        $materialRequest->save();
    }
}
