<div>
    <x-livewire.table.search-pane>
        @include('partials.livewire.money-requests.table.filters', [
            'disabledPayMethod' => true
        ])
    </x-livewire.table.search-pane>

    <div class="form-row mt-2">
        @forelse ($moneyRequests as $moneyRequest)
            @php
                /** @var App\Models\MoneyRequests\MoneyRequest $moneyRequest */
            @endphp

            <div class="col-lg-4 col-md-6 col-sm-12 d-flex" wire:key="card-{{ $moneyRequest->id }}">

                <div class="card card-outline card-{{ $moneyRequest->status_bs_color }} w-100">
                    @include('partials.livewire.money-requests.table.card.card-header')
                    @include('partials.livewire.money-requests.table.card.card-body')

                    <div class="card-footer py-2">
                        <a href="{{ route('accounting.money-requests.show', $moneyRequest->id) }}"
                            class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-fw fa-eye mr-1"></i> Ver #{{ number_format($moneyRequest->id) }}
                        </a>
                    </div>
                </div>

            </div>
        @empty
            @include('partials.livewire.money-requests.table.empty')
        @endforelse
    </div>

    {{ $moneyRequests->links() }}
</div>
