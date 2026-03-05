<div>
    @include('livewire.partials.requests.table.filters')

    <div class="row">
        @forelse ($requests as $request)
            <div class="col-md-4 col-sm-6" wire:key="card-{{ $request->id }}}">
                <x-requests.card :request="$request" class="card-dark">
                    <x-slot name=footer>
                        <div class="row">
                            <div class="col">
                                <div class="btn-group">
                                    <a href="{{ route('requests.show', $request->id) }}" class="btn btn-outline-info">
                                        Ver #{{ $request->id }}
                                    </a>
                                </div>
                            </div>
                            <div class="col text-right">
                                @if ($request->isPending())
                                    <button class="btn btn-outline-danger"
                                        x-on:click="deleteRequest({{ $request->id }})">
                                        Eliminar
                                    </button>
                                @endif
                            </div>
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

@push('js')
    <script>
        function deleteRequest(id) {
            Swal.fire({
                title: '¿Está seguro de eliminar esta solicitud?',
                text: "¡Esta acción es irreversible! Se eliminará permanentemente la solicitud.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('deleteRequest', {
                        id: id
                    });
                }
            });
        }

        Livewire.on('requestDeleted', () => {
            Swal.fire({
                title: "Solicitud eliminada",
                text: "La solicitud y todos sus datos han sido eliminados permanentemente.",
                icon: "success",
                confirmButtonColor: '#d33'
            });
        });
    </script>
@endpush
