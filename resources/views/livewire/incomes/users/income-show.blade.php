@php
    /** @var App\Models\Incomes\Income $income */
@endphp

<div>
    @include('partials.livewire.incomes.show.card-show')

    <div class="my-3">
        <a href="{{ route('incomes.create', ['copy' => $income->id]) }}" class="btn btn-outline-info mr-1">
            <i class="fas fa-fw fa-copy mr-1"></i> Crear Copia
        </a>

        <a href="{{ route('incomes.edit', $income->id) }}" class="btn btn-outline-primary mr-1">
            <i class="fas fa-fw fa-edit mr-1"></i> Editar
        </a>

        <x-livewire.loading-button label="Eliminar" theme="outline-danger" class="mr-1" icon="trash-alt"
            wire:click="delete" wire:target="delete" wire:swal-delete="¿Está seguro de eliminar este ingreso?" />

        <a href="{{ route('incomes.index') }}" class="btn btn-outline-secondary mr-1">
            <i class="fas fa-fw fa-chevron-left mr-1"></i> Volver
        </a>
    </div>
</div>
