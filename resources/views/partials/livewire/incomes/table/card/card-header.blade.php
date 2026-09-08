@php
    /** @var App\Models\Incomes\Income $income */
@endphp

<div class="card-header py-2">
    <div class="d-flex justify-content-between align-items-center">
        <div class="small text-muted">{{ $income->income_date->format('d/m/Y h:i a') }}</div>
    </div>
    <strong>{{ $income->user->name }}</strong>
</div>
