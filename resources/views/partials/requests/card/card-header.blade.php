@php
    /** @var App\Models\RequestModel $requestModel */
@endphp

<div class="card-header">
    <div class="d-flex justify-content-between">
        <div>{{ $requestModel->created_at->format('d/m/Y h:i a') }}</div>
        <div>
            <span class="badge badge-{{ $requestModel->status->bootstrapColorClass() }}">
                {{ $requestModel->status->label() }}
            </span>
        </div>
    </div>
    <div class="mt-1">{{ $requestModel->user->name }}</div>
</div>
