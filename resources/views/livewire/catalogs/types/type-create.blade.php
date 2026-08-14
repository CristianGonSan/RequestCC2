<div>
    <form wire:submit="save">
        <div class="card">
            <div class="card-body form-row">
                <x-adminlte-input fgroup-class="col-12" name="name" label="Nombre *"
                    placeholder="Escribe el nombre del tipo" type="text" maxlength="64" wire:model="name" required
                    autofocus />

                <div class="col-12">
                    <hr>
                </div>

                <div class="col-12">
                    <x-checkbox name="createAnother" label="Guardar y crear otro"
                        title="Permite ingresar otro tipo tras guardar" wire:model='createAnother' />
                </div>
            </div>
        </div>

        <div class="mb-3">
            <x-livewire.loading-button type='submit' label="Guardar" />

            <a href="{{ route('types.index') }}" class="btn btn-outline-secondary ml-1">
                Cancelar
            </a>
        </div>
    </form>
</div>
