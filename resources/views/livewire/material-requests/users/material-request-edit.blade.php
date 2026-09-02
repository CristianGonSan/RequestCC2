@php
    /** @var App\Models\MaterialRequests\MaterialRequest $materialRequest */
@endphp

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
            <x-livewire.loading-button type="submit" wire:target='save' label="Actualizar" wire:target="save" />

            <a href="{{ route('material-requests.show', $materialRequest->id) }}" class="btn btn-outline-secondary ml-1"
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
                .value(@json($type_id), @json($typeText))
                .placeholder('Selecciona el tipo')
                .build();

            const costCenterSelect = select2Builder.selector('#cost_center_id').wireModel('cost_center_id')
                .value(@json($cost_center_id), @json($costCenterText))
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
                    templateResult: function (data) {
                        if (data.loading) return data.text;

                        return $(`
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <div>
                                    <strong class="d-block">${data.text}</strong>
                                    ${data.company ? `<small class="d-block opacity-75">Empresa: ${data.company}</small>` : ''}
                                    ${data.description ? `<small class="d-block opacity-75 text-truncate" style="max-width: 300px;">${data.description}</small>` : ''}
                                </div>
                            </div>
                        `);
                    }
                }).build();


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
                    templateResult: function (data) {
                        if (data.loading) return data.text;

                        const badge = data.is_external
                            ? '<span class="badge badge-info float-right">Externo</span>'
                            : '<span class="badge badge-secondary float-right">Interno</span>';

                        return $(`
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <div>
                                    <strong class="d-block">${data.text}</strong>
                                    <small class="d-block opacity-75">Código: ${data.code || 'S/C'}</small>
                                    ${data.description ? `<small class="d-block opacity-75 text-truncate" style="max-width: 300px;">${data.description}</small>` : ''}
                                </div>
                                <div>
                                    ${badge}
                                </div>
                            </div>
                        `);
                    }
                }).build();
        });
    </script>
@endpush
