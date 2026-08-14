<div>
    <form wire:submit="save">
        <div class="card">
            <div class="card-body form-row">
                <x-adminlte-input fgroup-class="col-md-6" name="name" label="Nombre *"
                    placeholder="Nombre del centro de costos" type="text" maxlength="64" wire:model="name" required />

                <x-form.select-wire-ignore fgroup-class="col-md-6" name="company_id" label="Empresa *" required>
                    <option value="">Selecciona una empresa</option>
                    @foreach ($companies as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </x-form.select-wire-ignore>

                <x-adminlte-textarea fgroup-class="col-12" name="description" label="Descripción"
                    placeholder="Descripción del centro de costos" maxlength="255" wire:model="description" />
            </div>
        </div>

        <div class="mb-3">
            <x-livewire.loading-button type='submit' label="Guardar cambios" />

            <a href="{{ route('cost-centers.show', $costCenterId) }}" class="btn btn-outline-secondary ml-1">
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
                .value(@json($company_id), @json($companyText))
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
