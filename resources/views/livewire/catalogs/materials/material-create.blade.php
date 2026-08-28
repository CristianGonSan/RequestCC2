<div>
    <form wire:submit="save">
        <div class="card">
            <div class="card-body form-row">
                <x-adminlte-input fgroup-class="col-md-12" name="name" label="Nombre *"
                    placeholder="Escribe el nombre del material" type="text" maxlength="64" wire:model="name"
                    required />

                <x-adminlte-input fgroup-class="col-md-6" name="code" label="Código" placeholder="Código del material"
                    type="text" maxlength="24" wire:model="code" />

                <x-form.select-wire-ignore fgroup-class="col-md-6" name="base_unit_id" label="Unidad base *" required>
                    <option value="">Selecciona una unidad</option>
                    <x-adminlte-options :options="$unitOptions" empty-option="Selecciona la unidad..." />
                </x-form.select-wire-ignore>

                <x-adminlte-textarea fgroup-class="col-12" name="description" label="Descripción"
                    placeholder="Escribe una descripción" maxlength="255" wire:model="description" />

                <div class="col-md-12 d-flex align-items-center">
                    <x-checkbox name="is_external" label="Material externo"
                        title="Indica si el material es de origen externo" wire:model='is_external' />
                </div>

                <div class="col-12">
                    <hr>
                </div>

                <div class="col-12">
                    <x-checkbox name="createAnother" label="Guardar y crear otro"
                        title="Permite ingresar otro material tras guardar" wire:model='createAnother' />
                </div>
            </div>
        </div>

        <div class="mb-3">
            <x-livewire.loading-button type='submit' label="Guardar" />

            <a href="{{ route('materials.index') }}" class="btn btn-outline-secondary ml-1">
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

            select2Builder.selector('#base_unit_id').wireModel('base_unit_id')
                .placeholder('Seleccionar unidad')
                .build();
        });
    </script>
@endpush
