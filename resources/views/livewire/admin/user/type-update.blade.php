<div class="card mb-0">
    <form wire:submit.prevent="save">
        <div class="card-header">
            Tipos
        </div>
        <ul class="list-group list-group-flush overflow-auto" style="max-height: 400px;">
            @forelse ($types as $type)
                <li class="list-group-item py-1" wire:key="type-{{ $type->id }}">
                    <div class="icheck-primary">
                        <input type="checkbox" id="type_{{ $type->id }}"
                            wire:model.defer="selectedTypes.{{ $type->id }}" />
                        <label for="type_{{ $type->id }}">
                            {{ $type->name }}
                        </label>
                    </div>
                </li>
            @empty
                <li class="list-group-item text-muted">No hay datos.</li>
            @endforelse
        </ul>
        <div class="card-footer d-flex align-items-center justify-content-end">
            @if (session()->has('type_message'))
                <span class="text-success mr-3">{{ session('type_message') }}</span>
            @endif
            <button class="btn btn-outline-success btn-sm" type="submit">
                <i class="fas fa-lg fa-arrows-rotate mr-1" wire:loading.class="fa-spin"></i>Actualizar
            </button>
        </div>
    </form>
</div>
