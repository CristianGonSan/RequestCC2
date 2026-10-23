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

        @unless ($moneyRequest->is_transfer && $status->isCancelled())
            <button type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown">
                <i class="fas fa-fw fa-ellipsis-vertical mr-1"></i> Acciones
            </button>
            <div class="dropdown-menu">
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
            </div>
        @endunless

        <a href="{{ route('accounting.money-requests.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-fw fa-chevron-left mr-1"></i> Volver
        </a>
    </div>
</div>
