@php
    /** @var App\Models\Incomes\Income $income */
@endphp

<div>
    @include('partials.livewire.incomes.show.card-show')

    <div class="button-toolbar-wrap my-3">
        <x-livewire.loading-button label="{{ $hasFavorited ? 'Quitar favorito' : 'Añadir favorito' }}"
            theme="outline-primary" icon="star" icon-solid="{{ $hasFavorited }}"
            wire:click="toggleFavorite" wire:target="toggleFavorite" />

        <a href="{{ route('incomes.create', ['copy' => $income->id]) }}" class="btn btn-outline-info">
            <i class="fas fa-fw fa-copy mr-1"></i> Crear Copia
        </a>

        <a href="{{ route('incomes.edit', $income->id) }}" class="btn btn-outline-primary">
            <i class="fas fa-fw fa-edit mr-1"></i> Editar
        </a>

        <x-livewire.loading-button label="Eliminar" theme="outline-danger" icon="trash-alt"
            wire:click="delete" wire:target="delete" wire:swal-delete="¿Está seguro de eliminar este ingreso?" />

        <a href="{{ route('incomes.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-fw fa-chevron-left mr-1"></i> Volver
        </a>
    </div>
</div>
