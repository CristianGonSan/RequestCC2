<div>
    <form wire:submit="save">
        <div class="card">
            <div class="card-body form-row">
                <x-adminlte-input fgroup-class="col-md-6" name="name" label="Nombre *"
                    placeholder="Escribe el nombre completo" type="text" maxlength="128" wire:model="name" required
                    autofocus />

                <x-adminlte-input fgroup-class="col-md-6" name="email" label="Correo electrónico *"
                    placeholder="ejemplo@gmail.com" type="email" maxlength="255" wire:model="email" required />

                <x-form.select-wire-ignore fgroup-class="col-12" name="roles" label="Roles" multiple>
                    <x-adminlte-options :options="$roles" />
                </x-form.select-wire-ignore>

                <x-adminlte-input fgroup-class="col-md-6" name="password" label="Contraseña *"
                    placeholder="Mínimo 8 caracteres" type="password" maxlength="64" wire:model="password" required />

                <x-adminlte-input fgroup-class="col-md-6" name="password_confirmation" label="Confirmar Contraseña *"
                    placeholder="Repetir contraseña" type="password" maxlength="64" wire:model="password_confirmation"
                    required />

                <div class="col-12">
                    <hr>
                </div>

                <div class="col-12">
                    <x-checkbox name="createAnother" label="Guardar y crear otro"
                        title="Permite ingresar otro usuario tras guardar" wire:model='createAnother' />
                </div>
            </div>
        </div>

        <div class="mb-3">
            <x-livewire.loading-button type='submit' label="Guardar" />

            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary ml-1">
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
