@php
    /** @var App\Models\RequestModel $requestModel */
@endphp

<div>
    <x-livewire.table.search-pane>
        @include('partials.livewire.requests-model.table.filters')
    </x-livewire.table.search-pane>

    <div class="form-row mt-2">
        @forelse ($requests as $requestModel)
            <div class="col-lg-4 col-md-6 col-sm-12" wire:key="card-{{ $requestModel->id }}">

                <div class="card card-dark">
                    @include('partials.requests.card.card-header')
                    @include('partials.requests.card.card-body')

                    <div class="card-footer">
                        <a href="{{ route('accounting.requests.show', $requestModel->id) }}"
                            class="btn btn-outline-primary">
                            <i class="fas fa-fw fa-eye mr-1"></i> Ver #{{ number_format($requestModel->id) }}
                        </a>
                    </div>
                </div>
            </div>
        @empty
            @include('partials.requests.table.empty')
        @endforelse
    </div>

    {{ $requests->links() }}
</div>
