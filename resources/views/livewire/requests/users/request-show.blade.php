@php
    /** @var App\Enums\Requests\RequestStatus $status */
    $status = $requestModel->status;
@endphp

<div>
    @include('partials.requests.card-show')

    <div class="my-3">
        <a href="{{ route('requests.create', ['copy' => $requestModel->id]) }}" class="btn btn-outline-info mr-1">
            <i class="fas fa-fw fa-copy mr-1"></i> Crear Copia
        </a>

        @if ($status->isPending())
            <a href="{{ route('requests.edit', $requestModel->id) }}" class="btn btn-outline-primary mr-1">
                <i class="fas fa-fw fa-edit mr-1"></i> Editar
            </a>

            <x-livewire.loading-button label="Eliminar" theme="outline-danger" class="mr-1" icon="trash-alt"
                wire:click="delete" wire:target="delete" wire:swal-delete="¿Está seguro de eliminar esta solicitud?" />
        @endif

        <a href="{{ route('requests.index') }}" class="btn btn-outline-secondary mr-1">
            <i class="fas fa-fw fa-chevron-left mr-1"></i> Volver
        </a>
    </div>
</div>
