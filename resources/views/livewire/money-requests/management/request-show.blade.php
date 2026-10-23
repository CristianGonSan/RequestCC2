@use('App\Enums\Requests\MoneyRequestStatus as Status')

@php
    /** @var App\Enums\Requests\MoneyRequestStatus $status */
    $status = $moneyRequest->status;
@endphp

<div>
    @include('partials.livewire.money-requests.show.card-show')

    <div class="button-toolbar-wrap my-3">
        <x-livewire.loading-button label="{{ $hasFavorited ? 'Quitar favorito' : 'Añadir favorito' }}"
            theme="outline-primary" icon="star" icon-solid="{{ $hasFavorited }}"
            wire:click="toggleFavorite" wire:target="toggleFavorite" />

        @if ($status->isPending())
            <a href="{{ route('management.money-requests.edit', $moneyRequest->id) }}" class="btn btn-outline-primary">
                <i class="fas fa-fw fa-edit mr-1"></i> Editar
            </a>
        @endif

        @unless ($status->isCancelled())
            <button type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown">
                <i class="fas fa-fw fa-ellipsis-vertical mr-1"></i> Acciones
            </button>
            <div class="dropdown-menu">
                @if ($status->canChangeTo(Status::Accepted))
                    <button class="dropdown-item" wire:click="acceptRequest"
                        wire:swal-confirm="¿Está seguro de aceptar esta solicitud?">
                        Aceptar
                    </button>
                @endif
                @if ($status->canChangeTo(Status::Rejected))
                    <button class="dropdown-item" wire:click="rejectRequest"
                        wire:swal-confirm="¿Está seguro de rechazar esta solicitud?">
                        Rechazar
                    </button>
                @endif
                @if ($status->canChangeTo(Status::Pending))
                    <button class="dropdown-item" wire:click="markAsPending"
                        wire:swal-confirm="¿Está seguro de pasar a pendiente esta solicitud?">
                        Pendiente
                    </button>
                @endif
                @unless ($moneyRequest->is_transfer)
                    <div class="dropdown-divider"></div>
                    @if ($status->canChangeTo(Status::Paid))
                        <button class="dropdown-item" wire:click="markAsPaid"
                            wire:swal-confirm="¿Está seguro de pagar esta solicitud?">
                            Pagar
                        </button>
                    @endif
                    @if ($status->canChangeTo(Status::Cancelled))
                        <button class="dropdown-item" wire:click="cancelRequest"
                            wire:swal-confirm="¿Está seguro de cancelar esta solicitud?">
                            Cancelar
                        </button>
                    @endif
                @endunless
            </div>
        @endunless

        <a href="{{ route('management.money-requests.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-fw fa-chevron-left mr-1"></i> Volver
        </a>
    </div>
</div>
