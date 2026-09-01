@php
    /** @var App\Models\MaterialRequests\MaterialRequest $materialRequest */
    $requestedSum = (float) ($materialRequest->items_requested_sum ?? 0);
    $fulfilledSum = (float) ($materialRequest->items_fulfilled_sum ?? 0);
    $percentage = $requestedSum > 0 ? ($fulfilledSum / $requestedSum) * 100 : 0;
@endphp

<div class="card-body py-1" style="height: 180px; overflow-y: auto;">
    <div class="d-flex justify-content-between align-items-center">
        <div>{{ $materialRequest->costCenter->name }}</div>
        <strong class="text-nowrap ml-2">${{ number_format((float) $materialRequest->total_spent, 2) }}</strong>
    </div>

    <div class="mt-1 pt-1 border-top">
        <strong>Tipo:</strong> {{ $materialRequest->type->name }}
    </div>

    <div class="mt-1 pt-1 border-top">
        <strong>Concepto:</strong> {{ $materialRequest->concept ?? 'Sin datos' }}
    </div>

    <div class="mt-1 pt-1 border-top d-flex justify-content-between align-items-center">
        <span><strong>Items:</strong> {{ $materialRequest->items_count }}</span>
        <span class="badge bg-{{ $percentage >= 100 ? 'success' : 'warning' }}">
            {{ number_format($percentage, 0) }}% cumplido
        </span>
    </div>
</div>
