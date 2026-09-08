@php
    /** @var App\Models\MaterialRequests\MaterialRequest $materialRequest */
@endphp

<div class="card-header py-2">
    <div class="d-flex justify-content-between align-items-center">
        <div class="small text-muted">{{ $materialRequest->created_at->format('d/m/Y h:i a') }}</div>
        <span class="badge badge-{{ $materialRequest->status_bs_color }}">
            {{ $materialRequest->status_label }}
        </span>
    </div>
    <strong>{{ $materialRequest->user->name }}</strong>
</div>
