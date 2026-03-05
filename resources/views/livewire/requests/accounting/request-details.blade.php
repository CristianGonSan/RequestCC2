@php
    use App\Models\RequestModel;
@endphp

<div class="card">
    <div class="card-header bg-dark d-flex">
        Solicitud #{{ $requestModel->id }}
        <div wire:loading class="ml-auto">
            <i class="fas fa-spinner fa-spin"> </i>
        </div>
    </div>
    <div class="card-body">
        @include('partials.requests.details')
    </div>
    <div class="card-footer">
        <div class="btn-group">
            <button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown">
                Acciones
            </button>
            <div class="dropdown-menu">
                @if ($requestModel->isCancelled())
                    <div class="dropdown-item">
                        No hay acciones permitidas
                    </div>
                @else
                    @if (!$requestModel->isPaid())
                        <button class="dropdown-item" onclick="updateStatus('{{ RequestModel::STATUS_PAID }}')">
                            Pagada
                        </button>
                    @endif
                    @if (!$requestModel->isCancelled())
                        <button class="dropdown-item" onclick="updateStatus('{{ RequestModel::STATUS_CANCELED }}')">
                            Cancelar
                        </button>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        function updateStatus(status) {
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
