@props([
    'readonly' => true
])

@php
    /** @var App\Models\MaterialRequests\MaterialRequest $materialRequest */
@endphp

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            Solicitado
        </h3>
    </div>

    <div class="card-body p-0">
        <ul class="list-group list-group-flush">
            @forelse ($materialRequest->items()->orderByDesc('created_at')->get() as $item)
                <li class="list-group-item" wire:key='item-{{ $item->id }}'>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div>
                                <strong>{{ $item->material->name }}</strong>
                                @if ($item->material->code)
                                    <span class="text-muted">({{ $item->material->code }})</span>
                                @endif
                            </div>

                            <div class="mt-1">
                                <span>
                                    Suplido {{ number_format($item->quantity_fulfilled, 3) }}
                                    de {{ number_format($item->quantity_requested, 3) }}
                                    {{ $item->unit->symbol }}/s
                                </span>
                            </div>
                        </div>

                        @if ($item->isFullyFulfilled())
                            <span class="badge badge-success">Completado</span>
                        @else
                            @unless($readonly)
                                <x-livewire.loading-button label="Suplir" theme="outline-primary" class="btn-sm mr-1"
                                    icon="truck-ramp-box" wire:click="fulfillItem({{ $item->id }})"
                                    wire:target="fulfillItem({{ $item->id }})" />
                            @endunless
                        @endif
                    </div>

                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar bg-{{ $item->isFullyFulfilled() ? 'success' : 'warning' }}"
                            role="progressbar" style="width: {{ min($item->percentage_fulfilled, 100) }}%;"
                            aria-valuenow="{{ $item->percentage_fulfilled }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>

                    <div class="mt-1">
                        <span class="text-muted">Gastado: ${{ number_format($item->total_spent, 2) }}</span>
                    </div>
                </li>
            @empty
                <li class="list-group-item text-center text-muted py-3">
                    Sin materiales registrados
                </li>
            @endforelse
        </ul>
    </div>
</div>
