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

        <a href="{{ route('money-requests.create', ['copy' => $moneyRequest->id]) }}" class="btn btn-outline-info">
            <i class="fas fa-fw fa-copy mr-1"></i> Crear Copia
        </a>

        @if ($status->isPending())
            <a href="{{ route('money-requests.edit', $moneyRequest->id) }}" class="btn btn-outline-primary">
                <i class="fas fa-fw fa-edit mr-1"></i> Editar
            </a>

            <x-livewire.loading-button label="Eliminar" theme="outline-danger" icon="trash-alt"
                wire:click="delete" wire:target="delete" wire:swal-delete="¿Está seguro de eliminar esta solicitud?" />
        @endif

        <a href="{{ route('money-requests.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-fw fa-chevron-left mr-1"></i> Volver
        </a>
    </div>
</div>
