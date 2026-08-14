<form wire:submit="save">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Seleccionar Tipos</h2>
        </div>

        <div class="card-body">
            <div class="row">
                @foreach ($types as $type)
                    <div class="col-md-4 col-sm-6 mb-2" wire:key="type-{{ $type->id }}">
                        <div class="icheck-primary">
                            <input type="checkbox" id="type_{{ $type->id }}"
                                wire:model="selectedTypes.{{ $type->id }}" />
                            <label for="type_{{ $type->id }}">
                                {{ $type->name }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="mb-3 mt-3">
        <x-livewire.loading-button type="submit" label="Actualizar" />
    </div>
</form>
