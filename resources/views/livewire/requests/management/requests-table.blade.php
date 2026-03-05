<div>
    @php
        use App\Models\RequestModel;
    @endphp

    @include('livewire.partials.requests.table.filters')

    <div class="row">
        @forelse ($requests as $request)
            <div class="col-md-4 col-sm-6" wire:key="card-{{ $request->id }}}">
                <x-requests.card :request="$request" class="card-dark">
                    <x-slot name=footer>
                        <div class="btn-group">
                            <a href="{{ route('management.requests.show', $request->id) }}" class="btn btn-outline-info">
                                Ver #{{ $request->id }}
                            </a>

                            <button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown">
                                <i class="fa-solid fa-bars"></i>
                            </button>
                            <div class="dropdown-menu">
                                @if ($request->isCancelled())
                                    <div class="dropdown-item">
                                        No hay acciones permitidas
                                    </div>
                                @else
                                    @if (!$request->isAccepted())
                                        <button class="dropdown-item"
                                            onclick="updateStatus({{ $request->id }}, '{{ RequestModel::STATUS_ACCEPTED }}')">
                                            Aceptar
                                        </button>
                                    @endif
                                    @if (!$request->isRejected())
                                        <button class="dropdown-item"
                                            onclick="updateStatus({{ $request->id }}, '{{ RequestModel::STATUS_REJECTED }}')">
                                            Rechazar
                                        </button>
                                    @endif
                                    @if (!$request->isPending())
                                        <button class="dropdown-item"
                                            onclick="updateStatus({{ $request->id }}, '{{ RequestModel::STATUS_PENDING }}')">
                                            Pendiente
                                        </button>
                                    @endif
                                    @if (!$request->is_transfer)
                                        <div class="dropdown-divider"></div>
                                        @if (!$request->isPaid())
                                            <button class="dropdown-item"
                                                onclick="updateStatus({{ $request->id }}, '{{ RequestModel::STATUS_PAID }}')">
                                                Pagada
                                            </button>
                                        @endif
                                        @if (!$request->isCancelled())
                                            <button class="dropdown-item"
                                                onclick="updateStatus({{ $request->id }}, '{{ RequestModel::STATUS_CANCELED }}')">
                                                Cancelar
                                            </button>
                                        @endif
                                    @endif
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
        function updateStatus(id, status) {
            const statusMessages = {
                "{{ RequestModel::STATUS_ACCEPTED }}": {
                    title: "¿Aceptar solicitud?",
                    text: "La solicitud será marcada como aceptada. ¿Desea continuar?",
                    success: "La solicitud ha sido aceptada correctamente."
                },
                "{{ RequestModel::STATUS_REJECTED }}": {
                    title: "¿Rechazar solicitud?",
                    text: "La solicitud será rechazada. ¿Está seguro de esta acción?",
                    success: "La solicitud ha sido rechazada correctamente."
                },
                "{{ RequestModel::STATUS_PENDING }}": {
                    title: "¿Marcar como pendiente?",
                    text: "La solicitud volverá a estado pendiente. ¿Desea continuar?",
                    success: "La solicitud ha sido marcada como pendiente."
                },
                "{{ RequestModel::STATUS_PAID }}": {
                    title: "¿Marcar como pagada?",
                    text: "La solicitud será marcada como pagada. ¿Confirma esta acción?",
                    success: "La solicitud ha sido marcada como pagada."
                },
                "{{ RequestModel::STATUS_CANCELED }}": {
                    title: "¿Cancelar solicitud?",
                    text: "La solicitud será cancelada y no podrá modificarse. ¿Está seguro?",
                    success: "La solicitud ha sido cancelada correctamente."
                }
            };

            const message = statusMessages[status] || {
                title: "¿Actualizar estado?",
                text: "¿Está seguro de actualizar el estado de la solicitud?",
                success: "Estatus actualizado correctamente."
            };

            Swal.fire({
                title: message.title,
                text: message.text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('updateStatus', {
                        id: id,
                        status: status
                    });

                    Livewire.on('showFeedback', () => {
                        Swal.fire({
                            title: "Estatus actualizado",
                            text: message.success,
                            icon: "success",
                            confirmButtonColor: '#3085d6'
                        });
                    });
                }
            });
        }
    </script>
@endpush
