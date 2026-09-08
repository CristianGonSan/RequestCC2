@php
    /** @var App\Models\Incomes\Income $income */

    $company = $income->company;
    $type = $income->type;
@endphp

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 order-1">
                <dl class="row mb-2">
                    <dt class="col-md-4 text-muted">Fecha</dt>
                    <dd class="col-md-8 mb-0">{{ $income->income_date->format('d/m/Y') }}</dd>
                </dl>
            </div>

            <div class="col-md-6 order-2">
                <dl class="row mb-2">
                    <dt class="col-6 col-md-4 text-muted">Método de pago</dt>
                    <dd class="col-6 col-md-8 mb-0">
                        <span class="badge {{ $income->is_transfer ? 'badge-transfer' : 'badge-cash' }}">
                            {{ $income->payment_method }}
                        </span>
                    </dd>
                </dl>
            </div>

            <div class="col-12 order-3">
                <dl class="row mb-2">
                    <dt class="col-md-2 text-muted">Monto</dt>
                    <dd class="col-md-10 mb-0">
                        <span class="h3 font-weight-bold text-success d-block mb-0">
                            {{ $income->formatted_amount }}
                        </span>
                        <em class="text-muted">{{ ucfirst($income->amount_to_word) }}</em>
                    </dd>
                </dl>
            </div>

            <div class="col-12 order-4 order-md-4">
                <dl class="row mb-2">
                    <dt class="col-md-2 text-muted">Unidad de negocio</dt>
                    <dd class="col-md-10 mb-0">
                        <span class="d-block mb-0">{{ $company->name }}</span>
                        <em class="text-muted">{{ $company->description ?? 'Sin descripción' }}</em>
                    </dd>
                </dl>
            </div>

            <div class="col-12 order-4">
                <dl class="row mb-2">
                    <dt class="col-md-2 text-muted">Concepto</dt>
                    <dd class="col-md-10 mb-0">{{ $income->concept }}</dd>
                </dl>
            </div>
        </div>

        <hr class="mt-2">

        <div class="row">
            <div class="col-md-6">
                <dl class="row mb-2">
                    <dt class="col-md-4 text-muted">Registrado por</dt>
                    <dd class="col-md-8 mb-0">{{ $income->user->name }}</dd>
                </dl>
            </div>

            <div class="col-md-6">
                <dl class="row mb-2">
                    <dt class="col-md-4 text-muted">Beneficiario</dt>
                    <dd class="col-md-8 mb-0">{{ $income->payee }}</dd>
                </dl>
            </div>

            <div class="col-md-6">
                <dl class="row mb-2">
                    <dt class="col-md-4 text-muted">Tipo</dt>
                    <dd class="col-md-8 mb-0">{{ $type->name }}</dd>
                </dl>
            </div>
        </div>

        @if ($income->is_transfer)
            <hr class="mt-2">

            <div class="row">
                <div class="col-md-6">
                    <dl class="row mb-2">
                        <dt class="col-4 text-muted">Banco</dt>
                        <dd class="col-8 mb-0">{{ $income->bank ?? 'N/D' }}</dd>
                    </dl>
                </div>

                <div class="col-md-6">
                    <dl class="row mb-2">
                        <dt class="col-4 text-muted">Cuenta</dt>
                        <dd class="col-8 mb-0">{{ $income->account ?? 'N/D' }}</dd>
                    </dl>
                </div>

                <div class="col-md-6">
                    <dl class="row mb-2">
                        <dt class="col-4 text-muted">Tarjeta</dt>
                        <dd class="col-8 mb-0">{{ $income->card ?? 'N/D' }}</dd>
                    </dl>
                </div>

                <div class="col-md-6">
                    <dl class="row mb-2">
                        <dt class="col-4 text-muted">Sucursal</dt>
                        <dd class="col-8 mb-0">{{ $income->branch ?? 'N/D' }}</dd>
                    </dl>
                </div>

                <div class="col-md-6">
                    <dl class="row mb-2">
                        <dt class="col-4 text-muted">Referencia</dt>
                        <dd class="col-8 mb-0">{{ $income->reference ?? 'N/D' }}</dd>
                    </dl>
                </div>

                <div class="col-md-6">
                    <dl class="row mb-2">
                        <dt class="col-4 text-muted">Convenio</dt>
                        <dd class="col-8 mb-0">{{ $income->covenant ?? 'N/D' }}</dd>
                    </dl>
                </div>
            </div>
        @endif

    </div>
</div>
