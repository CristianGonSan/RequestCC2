@php
    /** @var App\Models\MoneyRequests\MoneyRequest $moneyRequest */
@endphp

<div class="card-header py-2">
    <div class="d-flex justify-content-between align-items-center">
        <div class="small text-muted">{{ $moneyRequest->created_at->format('d/m/Y h:i a') }}</div>
        <span class="badge badge-{{ $moneyRequest->status_bs_color }}">
            {{ $moneyRequest->status_label }}
        </span>
    </div>
    <strong>{{ $moneyRequest->user->name }}</strong>
</div>