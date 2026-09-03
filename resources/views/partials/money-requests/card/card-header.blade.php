@php
    /** @var App\Models\MoneyRequests\MoneyRequest $moneyRequest */
@endphp

<div class="card-header">
    <div class="d-flex justify-content-between">
        <div>{{ $moneyRequest->created_at->format('d/m/Y h:i a') }}</div>
        <div>
            <span class="badge badge-{{ $moneyRequest->status->bootstrapColorClass() }}">
                {{ $moneyRequest->status->label() }}
            </span>
        </div>
    </div>
    <div class="mt-1">{{ $moneyRequest->user->name }}</div>
</div>