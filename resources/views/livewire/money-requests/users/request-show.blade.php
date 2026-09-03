@php
    /** @var App\Enums\Requests\MoneyRequestStatus $status */
    $status = $moneyRequest->status;
@endphp

<div>
    @include('partials.money-requests.card-show')

    <div class="my-3">
        <a href="{{ route('money-requests.create', ['copy' => $moneyRequest->id]) }}" class="btn btn-outline-info mr-1">
            <i class="fas fa-fw fa-copy mr-1"></i> Crear Copia
        </a>

        @if ($status->isPending())
            <a href="{{ route('money-requests.edit', $moneyRequest->id) }}" class="btn btn-outline-primary mr-1">
                <i class="fas fa-fw fa-edit mr-1"></i> Editar
            </a>

            <x-livewire.loading-button label="Eliminar" theme="outline-danger" class="mr-1" icon="trash-alt"
                wire:click="delete" wire:target="delete" wire:swal-delete="¿Está seguro de eliminar esta solicitud?" />
        @endif

        <a href="{{ route('money-requests.index') }}" class="btn btn-outline-secondary mr-1">
            <i class="fas fa-fw fa-chevron-left mr-1"></i> Volver
        </a>
    </div>
</div>