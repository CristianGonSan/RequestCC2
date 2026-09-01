<div>
    <form wire:submit="save">
        <div class="card">
            <div class="card-body">
                <div class="form-row">
                    <x-adminlte-textarea fgroup-class="col-md-12" name="concept" label="Concepto *" rows="3"
                        placeholder="Inserte el concepto..." wire:model="concept" maxlength="255" required />

                    <x-form.select-wire-ignore fgroup-class="col-md-6" name="cost_center_id" label="Centro de Costos *"
                        required>
                    </x-form.select-wire-ignore>

                    <x-form.select-wire-ignore fgroup-class="col-md-6" id="type_id" name="type_id"
                        label="Tipo de movimiento *" required>
                        <x-adminlte-options :options="$typeOptions" empty-option="Selecciona el tipo..." />
                    </x-form.select-wire-ignore>
                </div>
            </div>
        </div>

        <h2 class="h5">Materiales solicitados</h2>

        <div x-on:keydown.enter.prevent>
            @include('partials.livewire.material-requests.create.items-card')
        </div>

        <div class="mb-3">
            <x-livewire.loading-button type="submit" wire:target='save' label="Guardar" wire:target="save" />

            <a href="{{ route('material-requests.index') }}" class="btn btn-outline-secondary ml-1"
                wire:loading.attr="disabled">
                Cancelar
            </a>
        </div>
    </form>
</div>

@push('js')
    <script>
        document.addEventListener("livewire:initialized", () => {
            const $wire = Livewire.first();

            let select2Builder = new LivewireSelect2Builder($wire);

            const typeSelect = select2Builder.selector('#type_id').wireModel('type_id')
                .value(@json($type_id ?? null), @json($typeText))
                .placeholder('Selecciona el tipo')
                .build();

            const materialSelect = select2Builder.selector('#material_id').wireModel('material_id')
                .placeholder('Seleccionar material')
                .appendConfig({
                    ajax: {
                        url: "{{ route('lookups.materials.select2') }}",
                        dataType: 'json',
                        delay: 250,
                        cache: true,
                        data: function (params) {
                            return {
                                term: params.term,
                                active: true,
                            };
                        },
                    },
                }).build();

            const costCenterSelect = select2Builder.selector('#cost_center_id').wireModel('cost_center_id')
                .value(@json($cost_center_id ?? null), @json($costCenterText))
                .placeholder('Selecciona el centro de costos')
                .appendConfig({
                    ajax: {
                        url: "{{ route('lookups.cost-centers.select2.auth') }}",
                        dataType: 'json',
                        delay: 250,
                        cache: true,
                        data: function (params) {
                            return {
                                term: params.term,
                                active: true,
                            };
                        },
                    },
                    templateResult: data => {
                        if (data.loading) return data.text;
                        return $(`
                                                            <div class="p-1">
                                                                <strong>${data.text}</strong>
                                                                <small class="d-block">${data.company}</small>
                                                                <small>${data.description}</small>
                                                            </div>
                                                            `);
                    }
                }).build();
        });
    </script>
@endpush
