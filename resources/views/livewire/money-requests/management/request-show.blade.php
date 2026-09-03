@php
    /** @var App\Enums\Requests\MoneyRequestStatus $status */
    $status = $moneyRequest->status;
@endphp

<div>
    @include('partials.money-requests.card-show')

    <div class="my-3">
        @if ($status->isPending())
            <a href="{{ route('management.money-requests.edit', $moneyRequest->id) }}" class="btn btn-outline-primary mr-1">
                <i class="fas fa-fw fa-edit mr-1"></i> Editar
            </a>
        @endif

        @if (!$status->isCancelled())
            <button type="button" class="btn btn-outline-primary dropdown-toggle mr-1" data-toggle="dropdown">
                <i class="fas fa-fw fa-ellipsis-vertical mr-1"></i> Acciones
            </button>
            <div class="dropdown-menu">
                @if (!$status->isAccepted())
                    <button class="dropdown-item" wire:click="acceptRequest"
                        wire:swal-confirm="¿Está seguro de aceptar esta solicitud?">
                        Aceptar
                    </button>
                @endif
                @if (!$status->isRejected())
                    <button class="dropdown-item" wire:click="rejectRequest"
                        wire:swal-confirm="¿Está seguro de rechazar esta solicitud?">
                        Rechazar
                    </button>
                @endif
                @if (!$status->isPending())
                    <button class="dropdown-item" wire:click="markAsPending"
                        wire:swal-confirm="¿Está seguro de pasar a pendiente esta solicitud?">
                        Pendiente
                    </button>
                @endif
                @if (!$moneyRequest->is_transfer)
                    <div class="dropdown-divider"></div>
                    @if (!$status->isPaid())
                        <button class="dropdown-item" wire:click="markAsPaid"
                            wire:swal-confirm="¿Está seguro de pagar esta solicitud?">
                            Pagadar
                        </button>
                    @endif
                    @if (!$status->isCancelled())
                        <button class="dropdown-item" wire:click="cancelRequest"
                            wire:swal-confirm="¿Está seguro de cancelar esta solicitud?">
                            Cancelar
                        </button>
                    @endif
                @endif
            </div>
        @endif

        <a href="{{ route('management.money-requests.index') }}" class="btn btn-outline-secondary mr-1">
            <i class="fas fa-fw fa-chevron-left mr-1"></i> Volver
        </a>
    </div>
</div>