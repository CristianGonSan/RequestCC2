<div>
    <form wire:submit="save">
        <div class="card">
            <div class="card-body form-row">
                <x-adminlte-input fgroup-class="col-12" name="name" label="Nombre *" placeholder="Nombre del tipo" type="text"
                    maxlength="64" wire:model="name" required />
            </div>
        </div>

        <div class="mb-3">
            <x-livewire.loading-button type='submit' label="Guardar cambios" />

            <a href="{{ route('types.show', $typeId) }}" class="btn btn-outline-secondary ml-1">
                Cancelar
            </a>
        </div>
    </form>
</div>
