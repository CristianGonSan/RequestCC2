<form wire:submit="save">
    <div class="card">
        <div class="card-body">
            <x-adminlte-input label="Nueva Contraseña" name="password" placeholder="Nueva Contraseña"
                autocomplete="new-password" type="password" maxlength="64" wire:model="password" required />

            <x-adminlte-input label="Confirmar Nueva Contraseña" name="password_confirmation" placeholder="Confirmar Nueva Contraseña"
                autocomplete="new-password" type="password" maxlength="64" wire:model="password_confirmation"
                required />
        </div>
    </div>

    <x-livewire.loading-button type='submit' label="Cambiar contraseña" class="mb-3" />
</form>
