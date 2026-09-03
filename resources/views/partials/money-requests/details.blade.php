<div class="row">
    @if ($moneyRequest->edit_count > 0)
        <div wire:ignore class="col-md-12">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Atención:</strong> Este registro ha sido editado {{ $moneyRequest->edit_count }}
                {{ $moneyRequest->edit_count === 1 ? 'vez' : 'veces' }}.

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    @endif
    <div class="col-md-12">
        {{ $moneyRequest->created_at->format('d-m-Y h:i:s a') }}
    </div>
    <div class="col-md-12">
        <strong class="text-{{ $moneyRequest->status->bootstrapColorClass() }}">
            {{ $moneyRequest->status->label() }}
        </strong>
    </div>
    <div class="col-md-6">
        <strong>Solicitante:</strong> {{ $moneyRequest->user->name ?? 'N/D' }}
    </div>
    <div class="col-md-6 mb-3">
        <strong>Beneficiario:</strong> {{ $moneyRequest->payee ?? 'N/D' }}
    </div>
    <div class="col-md-12 mb-3">
        <strong>Modo de Pago:</strong> <span class="badge"
            style="{{ $moneyRequest->is_transfer ? 'background-color: #A3C8FF' : 'background-color: #FFB998' }}">
            {{ $moneyRequest->paymentMethod() }}
        </span>
    </div>
    <div class="col-md-12">
        <strong class="d-block">Monto:</strong>
        <span class="h4 text-success">
            {{ $moneyRequest->formattedAmount() }}
        </span>
    </div>
    <div class="col-md-12 mb-3">
        <em class="text-muted">
            {{ ucfirst($moneyRequest->amountToWord()) }}
        </em>
    </div>

    <div class="col-md-12 mb-3">
        <strong>Concepto:</strong> {{ $moneyRequest->concept }}
    </div>
    <div class="col-md-6">
        <strong>Centro de Costos:</strong> {{ $moneyRequest->costCenter->name }}
    </div>
    <div class="col-md-6 mb-3">
        <strong>Tipo:</strong> {{ $moneyRequest->type->name }}
    </div>
    <div class="col-md-6">
        <strong>Banco:</strong> {{ $moneyRequest->bank ?? 'N/D' }}
    </div>
    <div class="col-md-6">
        <strong>Cuenta:</strong> {{ $moneyRequest->account ?? 'N/D' }}
    </div>
    <div class="col-md-6">
        <strong>Tarjeta:</strong> {{ $moneyRequest->card ?? 'N/D' }}
    </div>
    <div class="col-md-6">
        <strong>Sucursal:</strong> {{ $moneyRequest->branch ?? 'N/D' }}
    </div>
    <div class="col-md-6">
        <strong>Referencia:</strong> {{ $moneyRequest->reference ?? 'N/D' }}
    </div>
    <div class="col-md-6">
        <strong>Convenio:</strong> {{ $moneyRequest->covenant ?? 'N/D' }}
    </div>
</div>