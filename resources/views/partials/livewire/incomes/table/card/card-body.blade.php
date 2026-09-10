@php
    /** @var App\Models\Incomes\Income $income */

    $costCenter = $income->costCenter;
    $company = $costCenter->company;
    $type = $income->type;
@endphp

<div class="card-body py-2">
    <div class="d-flex justify-content-between align-items-center">
        <strong class="text-truncate">{{ $income->amount_formatted }}</strong>
        <span class="badge {{ $income->is_transfer ? 'badge-transfer' : 'badge-cash' }} flex-shrink-0 ml-2">
            {{ $income->payment_method }}
        </span>
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
        <dt class="col-3 text-muted mb-0">Titular</dt>
        <dd class="col-9 text-truncate mb-0" title="{{ $income->payee }}">
            {{ $income->payee }}
        </dd>
        <dt class="col-3 text-muted mb-0">Tipo</dt>
        <dd class="col-9 text-truncate mb-0" title="{{ $type->name }}">
            {{ $type->name }}
        </dd>
    </dl>

    <hr class="my-1">

    <div class="mb-0 text-muted" title="{{ $income->concept }}"
        style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
        {{ $income->longText('concept') }}
    </div>
</div>
