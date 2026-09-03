@php
    /** @var App\Models\MoneyRequests\MoneyRequest $moneyRequest */
@endphp

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 order-2 order-md-1">
                <dl class="row mb-2">
                    <dt class="col-6 col-md-4 text-muted">Estatus</dt>
                    <dd class="col-6 col-md-8 mb-0">
                        <span class="badge bg-{{ $moneyRequest->status->bootstrapColorClass() }}">
                            {{ $moneyRequest->status->label() }}
                        </span>
                    </dd>
                </dl>
            </div>

            <div class="col-md-6 order-1 order-md-2">
                <dl class="row mb-2">
                    <dt class="col-md-4 text-muted">Solicitado el</dt>
                    <dd class="col-md-8 mb-0">{{ $moneyRequest->created_at->format('d/m/Y h:i a') }}</dd>
                </dl>
            </div>

            <div class="col-md-6 order-3 order-md-3">
                <dl class="row mb-2">
                    <dt class="col-6 col-md-4 text-muted">Método de pago</dt>
                    <dd class="col-6 col-md-8 mb-0">
                        <span class="badge {{ $moneyRequest->is_transfer ? 'badge-transfer' : 'badge-cash' }}">
                            {{ $moneyRequest->paymentMethod() }}
                        </span>
                    </dd>
                </dl>
            </div>

            <div class="col-12 order-4 order-md-4">
                <dl class="row mb-2">
                    <dt class="col-md-2 text-muted">Monto</dt>
                    <dd class="col-md-10 mb-0">
                        <span class="h3 font-weight-bold text-success d-block mb-0">
                            {{ $moneyRequest->formattedAmount() }}
                        </span>
                        <em class="text-muted">{{ ucfirst($moneyRequest->amountToWord()) }}</em>
                    </dd>
                </dl>
            </div>

            <div class="col-12 order-5 order-md-5">
                <dl class="row mb-2">
                    <dt class="col-md-2 text-muted">Concepto</dt>
                    <dd class="col-md-10 mb-0">{{ $moneyRequest->concept }}</dd>
                </dl>
            </div>
        </div>

        <hr class="mt-2">

        <div class="row">
            <div class="col-md-6">
                <dl class="row mb-2">
                    <dt class="col-md-4 text-muted">Solicitante</dt>
                    <dd class="col-md-8 mb-0">{{ $moneyRequest->user->name ?? 'N/D' }}</dd>
                </dl>
            </div>

            <div class="col-md-6">
                <dl class="row mb-2">
                    <dt class="col-md-4 text-muted">Beneficiario</dt>
                    <dd class="col-md-8 mb-0">{{ $moneyRequest->payee ?? 'N/D' }}</dd>
                </dl>
            </div>

            <div class="col-md-6">
                <dl class="row mb-2">
                    <dt class="col-md-4 text-muted">Centro de costos</dt>
                    <dd class="col-md-8 mb-0">{{ $moneyRequest->costCenter->name }}</dd>
                </dl>
            </div>

            <div class="col-md-6">
                <dl class="row mb-2">
                    <dt class="col-md-4 text-muted">Tipo</dt>
                    <dd class="col-md-8 mb-0">{{ $moneyRequest->type->name }}</dd>
                </dl>
            </div>
        </div>

        @if ($moneyRequest->is_transfer)
            <hr class="mt-2">

            <div class="row">
                <div class="col-md-6">
                    <dl class="row mb-2">
                        <dt class="col-4 text-muted">Banco</dt>
                        <dd class="col-8 mb-0">{{ $moneyRequest->bank ?? 'N/D' }}</dd>
                    </dl>
                </div>

                <div class="col-md-6">
                    <dl class="row mb-2">
                        <dt class="col-4 text-muted">Cuenta</dt>
                        <dd class="col-8 mb-0">{{ $moneyRequest->account ?? 'N/D' }}</dd>
                    </dl>
                </div>

                <div class="col-md-6">
                    <dl class="row mb-2">
                        <dt class="col-4 text-muted">Tarjeta</dt>
                        <dd class="col-8 mb-0">{{ $moneyRequest->card ?? 'N/D' }}</dd>
                    </dl>
                </div>

                <div class="col-md-6">
                    <dl class="row mb-2">
                        <dt class="col-4 text-muted">Sucursal</dt>
                        <dd class="col-8 mb-0">{{ $moneyRequest->branch ?? 'N/D' }}</dd>
                    </dl>
                </div>

                <div class="col-md-6">
                    <dl class="row mb-2">
                        <dt class="col-4 text-muted">Referencia</dt>
                        <dd class="col-8 mb-0">{{ $moneyRequest->reference ?? 'N/D' }}</dd>
                    </dl>
                </div>

                <div class="col-md-6">
                    <dl class="row mb-2">
                        <dt class="col-4 text-muted">Convenio</dt>
                        <dd class="col-8 mb-0">{{ $moneyRequest->covenant ?? 'N/D' }}</dd>
                    </dl>
                </div>
            </div>
        @endif

    </div>
</div>