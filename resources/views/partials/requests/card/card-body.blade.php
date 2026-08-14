<div class="card-body py-1" style="height: 180px; overflow-y: auto;">
    <div>
        <span class="badge w-100 {{ $requestModel->is_transfer ? 'badge-transfer' : 'badge-cash' }}">
            {{ $requestModel->paymentMethod() }}
        </span>
    </div>
    <div class="d-flex justify-content-between align-items-center">
        <div>{{ $requestModel->costCenter->name }}</div>
        <strong class="text-nowrap ml-2">{{ $requestModel->formattedAmount() }}</strong>
    </div>

    <div class="mt-1 pt-1 border-top">
        <strong>Beneficiario:</strong> {{ $requestModel->payee ?? 'Sin datos' }}
    </div>
    <div>
        <strong>Tipo:</strong> {{ $requestModel->type->name }}
    </div>

    <div class="mt-1 pt-1 border-top">
        <strong>Concepto:</strong> {{ $requestModel->concept ?? 'Sin datos' }}
    </div>
</div>
