@php
    /** @var App\Models\MaterialRequests\MaterialRequest $materialRequest */

    $costCenter = $materialRequest->costCenter;
    $company = $costCenter->company;
    $type = $materialRequest->type;
@endphp

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 order-2 order-md-1">
                <dl class="row mb-2">
                    <dt class="col-6 col-md-4 text-muted">Estatus</dt>
                    <dd class="col-6 col-md-8 mb-0">
                        <span class="badge bg-{{ $materialRequest->status_bs_color }}">
                            {{ $materialRequest->status_label }}
                        </span>
                    </dd>
                </dl>
            </div>

            <div class="col-md-6 order-1 order-md-2">
                <dl class="row mb-2">
                    <dt class="col-md-4 text-muted">Solicitado el</dt>
                    <dd class="col-md-8 mb-0">{{ $materialRequest->created_at->format('d/m/Y h:i a') }}</dd>
                </dl>
            </div>

            <div class="col-12 order-4 order-md-4">
                <dl class="row mb-2">
                    <dt class="col-md-2 text-muted">Total gastado</dt>
                    <dd class="col-md-10 mb-0">
                        <span class="h3 font-weight-bold text-success d-block mb-0">
                            {{ $materialRequest->formatted_total_spent }}
                        </span>
                        <em class="text-muted">{{ ucfirst($materialRequest->total_spent_to_words) }}</em>
                    </dd>
                </dl>
            </div>

            <div class="col-12 order-4 order-md-4">
                <dl class="row mb-2">
                    <dt class="col-md-2 text-muted">Centro de Costos</dt>
                    <dd class="col-md-10 mb-0">
                        <span class="d-block mb-0">{{ $costCenter->name }} -
                            {{ $company->name }}</span>
                        <em class="text-muted">{{ $costCenter->description ?? 'Sin descripción' }}</em>
                    </dd>
                </dl>
            </div>

            <div class="col-12 order-4 order-md-4">
                <dl class="row mb-2">
                    <dt class="col-md-2 text-muted">Concepto</dt>
                    <dd class="col-md-10 mb-0">{{ $materialRequest->concept }}</dd>
                </dl>
            </div>
        </div>

        <hr class="mt-2">

        <div class="row">
            <div class="col-md-6">
                <dl class="row mb-2">
                    <dt class="col-md-4 text-muted">Solicitante</dt>
                    <dd class="col-md-8 mb-0">{{ $materialRequest->user->name }}</dd>
                </dl>
            </div>

            <div class="col-md-6">
                <dl class="row mb-2">
                    <dt class="col-md-4 text-muted">Tipo</dt>
                    <dd class="col-md-8 mb-0">{{ $type->name }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>
