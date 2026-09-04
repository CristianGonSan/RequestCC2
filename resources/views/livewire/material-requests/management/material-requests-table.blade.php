<div>
    <x-livewire.table.search-pane>
        @include('partials.livewire.material-requests.table.filters')
    </x-livewire.table.search-pane>

    <div class="form-row mt-2">
        @forelse ($materialRequests as $materialRequest)
            @php
                /** @var App\Models\MaterialRequests\MaterialRequest $materialRequest */
            @endphp

            <div class="col-lg-4 col-md-6 col-sm-12" wire:key="card-{{ $materialRequest->id }}">

                <div class="card card-dark">
                    @include('partials.livewire.material-requests.table.card.card-header')
                    @include('partials.livewire.material-requests.table.card.card-body')

                    <div class="card-footer">
                        <div class="d-flex">
                            <a href="{{ route('management.material-requests.show', $materialRequest->id) }}"
                                class="btn btn-outline-primary">
                                <i class="fas fa-fw fa-eye mr-1"></i> Ver #{{ number_format($materialRequest->id) }}
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        @empty
            @include('partials.livewire.material-requests.table.empty')
        @endforelse
    </div>

    {{ $materialRequests->links() }}
</div>
