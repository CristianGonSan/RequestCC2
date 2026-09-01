@props([
    'readonly' => true
])

@php
    /** @var App\Models\MaterialRequests\MaterialRequest $materialRequest */
@endphp

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            Movimientos
        </h3>
    </div>

    <div class="card-body p-0">
        <ul class="list-group list-group-flush">
            @forelse ($materialRequest->fulfillments()->orderByDesc('created_at')->get() as $fulfillment)
                <li class="list-group-item" wire:key="fulfillment-{{ $fulfillment->id }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div>
                                <strong>{{ $fulfillment->materialRequestItem->material->name }}</strong>
                                @if ($fulfillment->materialRequestItem->material->code)
                                    <span class="text-muted">({{ $fulfillment->materialRequestItem->material->code }})</span>
                                @endif
                            </div>

                            <div class="mt-1">
                                <span>{{ number_format((float) $fulfillment->quantity, 3) }}
                                    {{ $fulfillment->materialRequestItem->unit->symbol }}</span>
                                <span class="text-muted">&mdash; ${{ number_format((float) $fulfillment->cost, 2) }}</span>
                            </div>

                            <div class="mt-1">
                                <small class="text-muted">
                                    {{ $fulfillment->created_at->format('d/m/Y h:i A') }}
                                    &middot; {{ $fulfillment->user->name }}
                                </small>
                            </div>
                        </div>

                        @if (!$readonly)
                            @if ($fulfillment->isCurrentUser())
                                <x-livewire.loading-button label="Eliminar" theme="outline-danger" class="btn-sm mr-1"
                                    icon="trash-alt" wire:click="deleteFulfillment({{ $fulfillment->id }})"
                                    wire:target="deleteFulfillment({{ $fulfillment->id }})"
                                    wire:swal-delete="¿Está seguro de eliminar este movimiento?" />
                            @endif
                        @endif
                    </div>
                </li>
            @empty
                <li class="list-group-item text-center text-muted py-3">
                    Sin movimientos registrados
                </li>
            @endforelse
        </ul>
    </div>
</div>
