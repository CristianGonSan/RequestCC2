@php
    /** @var App\Models\RequestModel $requestModel */
@endphp

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 order-2 order-md-1">
                <dl class="row mb-2">
                    <dt class="col-6 col-md-4 text-muted">Estatus</dt>
                    <dd class="col-6 col-md-8 mb-0">
                        <span class="badge bg-{{ $requestModel->status->bootstrapColorClass() }}">
                            {{ $requestModel->status->label() }}
                        </span>
                    </dd>
                </dl>
            </div>

            <div class="col-md-6 order-1 order-md-2">
                <dl class="row mb-2">
                    <dt class="col-md-4 text-muted">Solicitado el</dt>
                    <dd class="col-md-8 mb-0">{{ $requestModel->created_at->format('d/m/Y h:i a') }}</dd>
                </dl>
            </div>

            <div class="col-md-6 order-3 order-md-3">
                <dl class="row mb-2">
                    <dt class="col-6 col-md-4 text-muted">Método de pago</dt>
                    <dd class="col-6 col-md-8 mb-0">
                        <span class="badge {{ $requestModel->is_transfer ? 'badge-transfer' : 'badge-cash' }}">
                            {{ $requestModel->paymentMethod() }}
                        </span>
                    </dd>
                </dl>
            </div>

            <div class="col-12 order-4 order-md-4">
                <dl class="row mb-2">
                    <dt class="col-md-2 text-muted">Monto</dt>
                    <dd class="col-md-10 mb-0">
                        <span class="h3 font-weight-bold text-success d-block mb-0">
                            {{ $requestModel->formattedAmount() }}
                        </span>
                        <em class="text-muted">{{ ucfirst($requestModel->amountToWord()) }}</em>
                    </dd>
                </dl>
            </div>

            <div class="col-12 order-5 order-md-5">
                <dl class="row mb-2">
                    <dt class="col-md-2 text-muted">Concepto</dt>
                    <dd class="col-md-10 mb-0">{{ $requestModel->concept }}</dd>
                </dl>
            </div>
        </div>

        <hr class="mt-2">

        <div class="row">
            <div class="col-md-6">
                <dl class="row mb-2">
                    <dt class="col-md-4 text-muted">Solicitante</dt>
                    <dd class="col-md-8 mb-0">{{ $requestModel->user->name ?? 'N/D' }}</dd>
                </dl>
            </div>

            <div class="col-md-6">
                <dl class="row mb-2">
                    <dt class="col-md-4 text-muted">Beneficiario</dt>
                    <dd class="col-md-8 mb-0">{{ $requestModel->payee ?? 'N/D' }}</dd>
                </dl>
            </div>

            <div class="col-md-6">
                <dl class="row mb-2">
                    <dt class="col-md-4 text-muted">Centro de costos</dt>
                    <dd class="col-md-8 mb-0">{{ $requestModel->costCenter->name }}</dd>
                </dl>
            </div>

            <div class="col-md-6">
                <dl class="row mb-2">
                    <dt class="col-md-4 text-muted">Tipo</dt>
                    <dd class="col-md-8 mb-0">{{ $requestModel->type->name }}</dd>
                </dl>
            </div>
        </div>

        @if ($requestModel->is_transfer)
            <hr class="mt-2">

            <div class="row">
                <div class="col-md-6">
                    <dl class="row mb-2">
                        <dt class="col-4 text-muted">Banco</dt>
                        <dd class="col-8 mb-0">{{ $requestModel->bank ?? 'N/D' }}</dd>
                    </dl>
                </div>

                <div class="col-md-6">
                    <dl class="row mb-2">
                        <dt class="col-4 text-muted">Cuenta</dt>
                        <dd class="col-8 mb-0">{{ $requestModel->account ?? 'N/D' }}</dd>
                    </dl>
                </div>

                <div class="col-md-6">
                    <dl class="row mb-2">
                        <dt class="col-4 text-muted">Tarjeta</dt>
                        <dd class="col-8 mb-0">{{ $requestModel->card ?? 'N/D' }}</dd>
                    </dl>
                </div>

                <div class="col-md-6">
                    <dl class="row mb-2">
                        <dt class="col-4 text-muted">Sucursal</dt>
                        <dd class="col-8 mb-0">{{ $requestModel->branch ?? 'N/D' }}</dd>
                    </dl>
                </div>

                <div class="col-md-6">
                    <dl class="row mb-2">
                        <dt class="col-4 text-muted">Referencia</dt>
                        <dd class="col-8 mb-0">{{ $requestModel->reference ?? 'N/D' }}</dd>
                    </dl>
                </div>

                <div class="col-md-6">
                    <dl class="row mb-2">
                        <dt class="col-4 text-muted">Convenio</dt>
                        <dd class="col-8 mb-0">{{ $requestModel->covenant ?? 'N/D' }}</dd>
                    </dl>
                </div>
            </div>
        @endif

    </div>
</div>
