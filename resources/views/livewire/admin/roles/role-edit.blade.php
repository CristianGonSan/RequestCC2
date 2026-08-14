<div>
    <form wire:submit="save">
        <div class="card">
            <div class="card-body">
                <x-adminlte-input name="name" label="Nombre *" placeholder="Nombre del rol" type="text" maxlength="64"
                    wire:model="name" required autofocus />

                <x-form.select-wire-ignore name="permissions" label="Permisos" multiple>
                    <x-adminlte-options :options="$permissions" :selected="$selectedPermissions" />
                </x-form.select-wire-ignore>
            </div>

        </div>
        <div class="mb-3">
            <x-livewire.loading-button type='submit' label=" Guardar cambios" />

            <a href="{{ route('roles.show', $roleId) }}" class="btn btn-outline-secondary ml-1">
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

            select2Builder.selector('#permissions')
                .wireModel('selectedPermissions')
                .placeholder('Seleccione los permisos...')
                .build();
        });
    </script>
@endpush
