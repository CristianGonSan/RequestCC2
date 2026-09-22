<div>
    <x-livewire.table.search-pane>
        @include('partials.livewire.incomes.table.filters')
    </x-livewire.table.search-pane>

    <div class="form-row mt-2">
        @forelse ($incomes as $income)
            @php
                /** @var App\Models\Incomes\Income $income */

                $hasFavorited = $income->has_favorited;
            @endphp

            <div class="col-lg-4 col-md-6 col-sm-12 d-flex" wire:key="card-{{ $income->id }}">

                <div class="card card-outline card-dark w-100">
                    @include('partials.livewire.incomes.table.card.card-header')
                    @include('partials.livewire.incomes.table.card.card-body')

                    <div class="card-footer py-2">
                        <div class="d-flex">
                            <div class="btn-group" role="group">
                                <a href="{{ route('incomes.show', $income->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-fw fa-eye mr-1"></i> Ver #{{ number_format($income->id) }}
                                </a>
                                <x-livewire.loading-button theme="outline-primary" class="btn-sm" icon="star"
                                    icon-solid="{{ $hasFavorited }}" wire:click="toggleFavorite({{ $income->id }})"
                                    wire:target="toggleFavorite({{ $income->id }})"
                                    title="{{ $hasFavorited ? 'Quitar favorito' : 'Añadir favorito' }}" />
                            </div>

                            <x-livewire.loading-button label="Eliminar" theme="outline-danger" class="ml-auto btn-sm"
                                icon="trash-alt" wire:click="deleteIncome({{ $income->id }})"
                                wire:target="deleteIncome({{ $income->id }})"
                                wire:swal-delete="¿Está seguro de eliminar este ingreso?" />
                        </div>
                    </div>
                </div>

            </div>
        @empty
            @include('partials.livewire.incomes.table.empty')
        @endforelse
    </div>

    {{ $incomes->links() }}
</div>
