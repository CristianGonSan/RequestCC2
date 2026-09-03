@php
    /** @var App\Models\MoneyRequests\MoneyRequest $moneyRequest */
@endphp

<div class="card-body py-1" style="height: 180px; overflow-y: auto;">
    <div>
        <span class="badge w-100 {{ $moneyRequest->is_transfer ? 'badge-transfer' : 'badge-cash' }}">
            {{ $moneyRequest->paymentMethod() }}
        </span>
    </div>
    <div class="d-flex justify-content-between align-items-center">
        <div>{{ $moneyRequest->costCenter->name }}</div>
        <strong class="text-nowrap ml-2">{{ $moneyRequest->formattedAmount() }}</strong>
    </div>

    <div class="mt-1 pt-1 border-top">
        <strong>Beneficiario:</strong> {{ $moneyRequest->payee ?? 'Sin datos' }}
    </div>
    <div>
        <strong>Tipo:</strong> {{ $moneyRequest->type->name }}
    </div>

    <div class="mt-1 pt-1 border-top">
        <strong>Concepto:</strong> {{ $moneyRequest->concept ?? 'Sin datos' }}
    </div>
</div>