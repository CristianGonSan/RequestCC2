@php
    /** @var App\Models\MaterialRequests\MaterialRequest $materialRequest */

    $costCenter = $materialRequest->costCenter;
    $company = $costCenter->company;
    $type = $materialRequest->type;
@endphp

<div class="card-body py-2">
    <div class="d-flex justify-content-between align-items-center">
        <strong class="text-truncate">${{ number_format((float) $materialRequest->total_spent, 2) }}</strong>
    </div>

    <hr class="my-2">

    <div class="text-truncate" title="{{ $costCenter->name }} - {{ $company->name }}">
        <strong>{{ $costCenter->name }}</strong><span> -
        </span><span>{{ $company->name }}</span>
    </div>
    <div class="small text-muted text-truncate" title="{{ $costCenter->description }}">
        <em>{{ $costCenter->description }}</em>
    </div>

    <hr class="my-2">

    <dl class="row mb-0">
        <dt class="col-3 text-muted mb-0">Tipo</dt>
        <dd class="col-9 text-truncate mb-0" title="{{ $type->name }}">
            {{ $type->name }}
        </dd>
    </dl>

    <hr class="my-1">

    <div class="mb-0 text-muted" title="{{ $materialRequest->concept }}"
        style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
        {{ $materialRequest->longText('concept') }}
    </div>
</div>
