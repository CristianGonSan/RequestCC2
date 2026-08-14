<div>
    <form wire:submit="save">
        <div class="card">
            <div class="card-body">
                <x-adminlte-input name="name" label="Nombre *" placeholder="Nombre del rol" type="text" maxlength="64"
                    wire:model="name" required autofocus />

                <x-form.select-wire-ignore name="permissions" label="Permisos" multiple>
                    <x-adminlte-options :options="$permissions" />
                </x-form.select-wire-ignore>

                <hr>

                <x-checkbox name="createAnother" label="Guardar y crear otra"
                    title="Permite ingresar otra categoria tras guardar" wire:model='createAnother' />
            </div>
        </div>
        <div class="mb-3">
            <x-livewire.loading-button type='submit' label="Guardar" />

            <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary ml-1">
                Cancelar
            </a>
        </div>
    </form>
</div>

@push('js')
    <script>
        document.addEventListener("livewire:initialized", () => {
            const $wire = Livewire.first();
            const select2Builder = new LivewireSelect2Builder($wire);

            const permisionsSelect = select2Builder.selector('#permissions')
                .wireModel('selectedPermissions')
                .placeholder('Seleccione los permisos...')
                .build();
        });
    </script>
@endpush
