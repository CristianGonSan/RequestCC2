@php
    /** @var App\Enums\Requests\MoneyRequestStatus $status */
    $status = $moneyRequest->status;
@endphp

<div>
    @include('partials.money-requests.card-show')

    <div class="my-3">
        @if ($moneyRequest->is_transfer && !$status->isCancelled())
            <button type="button" class="btn btn-outline-primary dropdown-toggle mr-1" data-toggle="dropdown">
                <i class="fas fa-fw fa-ellipsis-vertical mr-1"></i> Acciones
            </button>
            <div class="dropdown-menu">
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
            </div>
        @endif

        <a href="{{ route('accounting.money-requests.index') }}" class="btn btn-outline-secondary mr-1">
            <i class="fas fa-fw fa-chevron-left mr-1"></i> Volver
        </a>
    </div>
</div>