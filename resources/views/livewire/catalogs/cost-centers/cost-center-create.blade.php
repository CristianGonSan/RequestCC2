<div>
    <form wire:submit="save">
        <div class="card">
            <div class="card-body form-row">
                <x-adminlte-input fgroup-class="col-md-6" name="name" label="Nombre *"
                    placeholder="Escribe el nombre del centro de costos" type="text" maxlength="64" wire:model="name"
                    required />

                <x-form.select-wire-ignore fgroup-class="col-md-6" name="company_id" label="Empresa *" required>
                    <option value="">Selecciona una empresa</option>
                    @foreach ($companies as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </x-form.select-wire-ignore>

                <x-adminlte-textarea fgroup-class="col-12" name="description" label="Descripción"
                    placeholder="Escribe una descripción" maxlength="255" wire:model="description"  />

                <div class="col-12">
                    <hr>
                </div>

                <div class="col-12">
                    <x-checkbox name="createAnother" label="Guardar y crear otro"
                        title="Permite ingresar otro centro de costos tras guardar" wire:model='createAnother' />
                </div>
            </div>
        </div>

        <div class="mb-3">
            <x-livewire.loading-button type='submit' label="Guardar" />

            <a href="{{ route('cost-centers.index') }}" class="btn btn-outline-secondary ml-1">
                Cancelar
            </a>
        </div>
    </form>
</div>

@push('js')
    <script>
        document.addEventListener("livewire:initialized", () => {
            let $wire = Livewire.first();

            let select2Builder = new LivewireSelect2Builder($wire);

            const companySelect = select2Builder.selector('#company_id').wireModel('company_id')
                .appendConfig({
                    placeholder: 'Seleccionar empresa',
                    minimumInputLength: 0
                }).build();

            Livewire.on('reset', () => {
                companySelect.val(null).trigger('change');
            });
        });
    </script>
@endpush
