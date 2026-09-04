<div>
    <x-livewire.table.search-pane>
        @include('partials.livewire.money-requests.table.filters', [
            'disabledPayMethod' => true
        ])
    </x-livewire.table.search-pane>

    <div class="form-row mt-2">
        @forelse ($requests as $moneyRequest)
            @php
                /** @var App\Models\MoneyRequests\MoneyRequest $moneyRequest */
            @endphp

            <div class="col-lg-4 col-md-6 col-sm-12" wire:key="card-{{ $moneyRequest->id }}">

                <div class="card card-dark">
                    @include('partials.money-requests.card.card-header')
                    @include('partials.money-requests.card.card-body')

                    <div class="card-footer">
                        <a href="{{ route('accounting.money-requests.show', $moneyRequest->id) }}"
                            class="btn btn-outline-primary">
                            <i class="fas fa-fw fa-eye mr-1"></i> Ver #{{ number_format($moneyRequest->id) }}
                        </a>
                    </div>
                </div>

            </div>
        @empty
            @include('partials.money-requests.table.empty')
        @endforelse
    </div>

    {{ $requests->links() }}
</div>
