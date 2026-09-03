@php
    /** @var App\Models\MoneyRequests\MoneyRequest $moneyRequest */
@endphp

<div>
    <x-livewire.table.search-pane>
        @include('partials.livewire.money-requests.table.filters', ['addUserOrder' => false])
    </x-livewire.table.search-pane>

    <div class="form-row mt-2">
        @forelse ($requests as $moneyRequest)
            <div class="col-lg-4 col-md-6 col-sm-12" wire:key="card-{{ $moneyRequest->id }}">

                <div class="card card-dark">
                    @include('partials.money-requests.card.card-header')
                    @include('partials.money-requests.card.card-body')

                    <div class="card-footer">
                        <div class="d-flex">
                            <a href="{{ route('money-requests.show', $moneyRequest->id) }}" class="btn btn-outline-primary">
                                <i class="fas fa-fw fa-eye mr-1"></i> Ver #{{ number_format($moneyRequest->id) }}
                            </a>

                            @if ($moneyRequest->status->isPending())
                                <x-livewire.loading-button label="Eliminar" theme="outline-danger" class="ml-auto"
                                    icon="trash-alt" wire:click="deleteRequest({{ $moneyRequest->id }})"
                                    wire:target="deleteRequest({{ $moneyRequest->id }})"
                                    wire:swal-delete="¿Está seguro de eliminar esta solicitud?" />
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        @empty
            @include('partials.requests.table.empty')
        @endforelse
    </div>

    {{ $requests->links() }}
</div>