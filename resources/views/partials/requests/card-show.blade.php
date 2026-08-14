<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-4 text-muted">Estatus</dt>
                    <dd class="col-8">
                        <span class="badge bg-{{ $requestModel->status->bootstrapColorClass() }}">
                            {{ $requestModel->status->label() }}
                        </span>
                    </dd>
                </dl>
            </div>

            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-4 text-muted">Solicitado el</dt>
                    <dd class="col-8">{{ $requestModel->created_at->format('d/m/Y h:i a') }}</dd>
                </dl>
            </div>

            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-4 text-muted">Método de pago</dt>
                    <dd class="col-8">
                        <span class="badge {{ $requestModel->is_transfer ? 'badge-transfer' : 'badge-cash' }}">
                            {{ $requestModel->paymentMethod() }}
                        </span>
                    </dd>
                </dl>
            </div>

            <div class="col-12">
                <dl class="row mb-0">
                    <dt class="col-md-2 text-muted">Monto</dt>
                    <dd class="col-md-10">
                        <span class="h3 font-weight-bold text-success d-block mb-0">
                            {{ $requestModel->formattedAmount() }}
                        </span>
                        <em class="text-muted">{{ ucfirst($requestModel->amountToWord()) }}</em>
                    </dd>
                </dl>
            </div>

            <div class="col-12">
                <dl class="row mb-0">
                    <dt class="col-md-2 text-muted">Concepto</dt>
                    <dd class="col-md-10">{{ $requestModel->concept }}</dd>
                </dl>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-4 text-muted">Solicitante</dt>
                    <dd class="col-8">{{ $requestModel->user->name ?? 'N/D' }}</dd>
                </dl>
            </div>

            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-4 text-muted">Beneficiario</dt>
                    <dd class="col-8">{{ $requestModel->payee ?? 'N/D' }}</dd>
                </dl>
            </div>

            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-4 text-muted">Centro de costos</dt>
                    <dd class="col-8">{{ $requestModel->costCenter->name }}</dd>
                </dl>
            </div>

            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-4 text-muted">Tipo</dt>
                    <dd class="col-8">{{ $requestModel->type->name }}</dd>
                </dl>
            </div>
        </div>

        @if ($requestModel->is_transfer)
            <hr>

            <div class="row">
                <div class="col-md-6">
                    <dl class="row mb-0">
                        <dt class="col-4 text-muted">Banco</dt>
                        <dd class="col-8">{{ $requestModel->bank ?? 'N/D' }}</dd>
                    </dl>
                </div>

                <div class="col-md-6">
                    <dl class="row mb-0">
                        <dt class="col-4 text-muted">Cuenta</dt>
                        <dd class="col-8">{{ $requestModel->account ?? 'N/D' }}</dd>
                    </dl>
                </div>

                <div class="col-md-6">
                    <dl class="row mb-0">
                        <dt class="col-4 text-muted">Tarjeta</dt>
                        <dd class="col-8">{{ $requestModel->card ?? 'N/D' }}</dd>
                    </dl>
                </div>

                <div class="col-md-6">
                    <dl class="row mb-0">
                        <dt class="col-4 text-muted">Sucursal</dt>
                        <dd class="col-8">{{ $requestModel->branch ?? 'N/D' }}</dd>
                    </dl>
                </div>

                <div class="col-md-6">
                    <dl class="row mb-0">
                        <dt class="col-4 text-muted">Referencia</dt>
                        <dd class="col-8">{{ $requestModel->reference ?? 'N/D' }}</dd>
                    </dl>
                </div>

                <div class="col-md-6">
                    <dl class="row mb-0">
                        <dt class="col-4 text-muted">Convenio</dt>
                        <dd class="col-8">{{ $requestModel->covenant ?? 'N/D' }}</dd>
                    </dl>
                </div>
            </div>
        @endif

    </div>
</div>
