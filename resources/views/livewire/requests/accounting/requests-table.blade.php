<div>
    @include('livewire.partials.requests.table.filters')

    <div class="row">
        @forelse ($requests as $request)
            <div class="col-md-4 col-sm-6" wire:key="card-{{ $request->id }}}">
                <x-requests.card :request="$request" class="card-dark">
                    <x-slot name=footer>
                        <div class="btn-group">
                            <a href="{{ route('accounting.requests.show', $request->id) }}" class="btn btn-outline-info">
                                Ver #{{ $request->id }}
                            </a>
                        </div>
                    </x-slot>
                </x-requests.card>
            </div>
        @empty
            @include('partials.requests.table.empty')
        @endforelse
    </div>

    {{ $requests->links() }}
</div>
