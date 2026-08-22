<div>
    <form wire:submit="save">
        <div class="card">
            <div class="card-body form-row">
                <x-adminlte-input fgroup-class="col-md-8" name="name" label="Nombre *" placeholder="Nombre de la unidad"
                    type="text" maxlength="64" wire:model="name" required />

                <x-adminlte-input fgroup-class="col-md-4" name="symbol" label="Símbolo *" placeholder="Ej. kg"
                    type="text" maxlength="16" wire:model="symbol" required />
            </div>
        </div>

        <div class="mb-3">
            <x-livewire.loading-button type='submit' label="Guardar cambios" />

            <a href="{{ route('units.show', $unitId) }}" class="btn btn-outline-secondary ml-1">
                Cancelar
            </a>
        </div>
    </form>
</div>
