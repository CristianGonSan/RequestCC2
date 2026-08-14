<div>
    <form wire:submit="save">
        <div class="card">
            <div class="card-body form-row">
                <x-adminlte-input fgroup-class="col-md-6" name="name" label="Nombre *" placeholder="Nombre de usuario"
                    autocomplete="username" type="text" maxlength="128" wire:model="name" required />

                <x-adminlte-input fgroup-class="col-md-6" name="email" label="Correo electrónico *"
                    placeholder="ejemplo@gmail.com" autocomplete="email" type="email" maxlength="255"
                    wire:model="email" required />

                <x-form.select-wire-ignore fgroup-class="col-12" name="roles" label="Roles" multiple>
                    <x-adminlte-options :options="$roles" :selected="$selectedRoles" />
                </x-form.select-wire-ignore>
            </div>
        </div>

        <div class="mb-3">
            <x-livewire.loading-button type='submit' label="Guardar cambios" />

            <a href="{{ route('users.show', $userId) }}" class="btn btn-outline-secondary ml-1">
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

            const rolesSelect = select2Builder.selector('#roles')
                .wireModel('selectedRoles')
                .placeholder('Seleccione los roles...')
                .build();
        });
    </script>
@endpush
