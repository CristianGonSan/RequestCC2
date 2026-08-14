<div class="row">
    @if ($requestModel->edit_count > 0)
        <div wire:ignore class="col-md-12">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Atención:</strong> Este registro ha sido editado {{ $requestModel->edit_count }}
                {{ $requestModel->edit_count === 1 ? 'vez' : 'veces' }}.

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    @endif
    <div class="col-md-12">
        {{ $requestModel->created_at->format('d-m-Y h:i:s a') }}
    </div>
    <div class="col-md-12">
        <strong class="text-{{ $requestModel->status->bootstrapColorClass() }}">
            {{ $requestModel->status->label() }}
        </strong>
    </div>
    <div class="col-md-6">
        <strong>Solicitante:</strong> {{ $requestModel->user->name ?? 'N/D' }}
    </div>
    <div class="col-md-6 mb-3">
        <strong>Beneficiario:</strong> {{ $requestModel->payee ?? 'N/D' }}
    </div>
    <div class="col-md-12 mb-3">
        <strong>Modo de Pago:</strong> <span class="badge"
            style="{{ $requestModel->is_transfer ? 'background-color: #A3C8FF' : 'background-color: #FFB998' }}">
            {{ $requestModel->paymentMethod() }}
        </span>
    </div>
    <div class="col-md-12">
        <strong class="d-block">Monto:</strong>
        <span class="h4 text-success">
            {{ $requestModel->formattedAmount() }}
        </span>
    </div>
    <div class="col-md-12 mb-3">
        <em class="text-muted">
            {{ ucfirst($requestModel->amountToWord()) }}
        </em>
    </div>

    <div class="col-md-12 mb-3">
        <strong>Concepto:</strong> {{ $requestModel->concept }}
    </div>
    <div class="col-md-6">
        <strong>Centro de Costos:</strong> {{ $requestModel->costCenter->name }}
    </div>
    <div class="col-md-6 mb-3">
        <strong>Tipo:</strong> {{ $requestModel->type->name }}
    </div>
    <div class="col-md-6">
        <strong>Banco:</strong> {{ $requestModel->bank ?? 'N/D' }}
    </div>
    <div class="col-md-6">
        <strong>Cuenta:</strong> {{ $requestModel->account ?? 'N/D' }}
    </div>
    <div class="col-md-6">
        <strong>Tarjeta:</strong> {{ $requestModel->card ?? 'N/D' }}
    </div>
    <div class="col-md-6">
        <strong>Sucursal:</strong> {{ $requestModel->branch ?? 'N/D' }}
    </div>
    <div class="col-md-6">
        <strong>Referencia:</strong> {{ $requestModel->reference ?? 'N/D' }}
    </div>
    <div class="col-md-6">
        <strong>Convenio:</strong> {{ $requestModel->covenant ?? 'N/D' }}
    </div>
</div>
