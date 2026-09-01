@php
    /** @var App\Models\MaterialRequests\MaterialRequest $materialRequest */
@endphp

<div class="card-header">
    <div class="d-flex justify-content-between">
        <div>{{ $materialRequest->created_at->format('d/m/Y h:i a') }}</div>
        <div>
            <span class="badge badge-{{ $materialRequest->status->bootstrapColorClass() }}">
                {{ $materialRequest->status->label() }}
            </span>
        </div>
    </div>
    <div class="mt-1">{{ $materialRequest->user->name }}</div>
</div>
