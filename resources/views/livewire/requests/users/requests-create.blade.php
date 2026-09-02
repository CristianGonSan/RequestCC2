<div>
    <form wire:submit="save" x-data="{ isTransfer: @entangle('is_transfer') }">
        <div class="card">
            <div class="card-body">
                <x-checkbox name="is_transfer" label="Es transferencia" x-model="isTransfer"
                    title="Indica si es una transferencia" />

                <hr>

                <div class="form-row">
                    <x-adminlte-textarea fgroup-class="col-md-12" name="concept" label="Concepto *" rows="3"
                        placeholder="Inserte el concepto..." wire:model="concept" maxlength="255" required />

                    <x-form.select-wire-ignore fgroup-class="col-md-6" name="cost_center_id" label="Centro de Costos *"
                        required>
                    </x-form.select-wire-ignore>

                    <x-adminlte-input fgroup-class="col-md-6" name="payee" label="Titular *" placeholder="titular"
                        wire:model="payee" maxlength="128" required />

                    <x-form.input-wire-ignore fgroup-class="col-md-6" name="amount" label="Monto *" placeholder="monto"
                        wire:model="amount" required
                        data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false, 'placeholder': '0', 'min': 0, 'max': 999999999999.99" />

                    <x-form.select-wire-ignore fgroup-class="col-md-6" id="type_id" name="type_id"
                        label="Tipo de movimiento *" required>
                        <x-adminlte-options :options="$typeOptions" empty-option="Selecciona el tipo..." />
                    </x-form.select-wire-ignore>
                </div>

                <div x-show="isTransfer" class="form-row">
                    <x-adminlte-input fgroup-class="col-md-4" name="bank" label="Banco *" placeholder="banco"
                        wire:model="bank" maxlength="128" x-bind:disabled="!isTransfer" />

                    <x-form.input-wire-ignore fgroup-class="col-md-4" name="card" label="Tarjeta/CLABE *"
                        placeholder="tarjeta" wire:model="card" x-bind:disabled="!isTransfer"
                        data-inputmask="'mask': '****-****-****-****[-****]', 'placeholder': '_'" />

                    <x-form.input-wire-ignore fgroup-class="col-md-4" name="account" label="Cuenta" placeholder="cuenta"
                        wire:model="account" x-bind:disabled="!isTransfer"
                        data-inputmask="'mask': '****-****-****-****[-****]'" />

                    <x-adminlte-input fgroup-class="col-md-4" name="branch" label="Sucursal" placeholder="sucursal"
                        wire:model="branch" maxlength="128" x-bind:disabled="!isTransfer" />

                    <x-adminlte-input fgroup-class="col-md-4" name="reference" label="Referencia"
                        placeholder="referencia" wire:model="reference" maxlength="128" x-bind:disabled="!isTransfer" />

                    <x-adminlte-input fgroup-class="col-md-4" name="covenant" label="Convenio" placeholder="convenio"
                        wire:model="covenant" maxlength="128" x-bind:disabled="!isTransfer" />
                </div>

                <hr>

                <x-checkbox name="createAnother" label="Guardar y crear otra"
                    title="Permite ingresar otra solicitud después de guardar" wire:model="createAnother" />
            </div>
        </div>
        <div class="mb-3">
            <x-livewire.loading-button type='submit' label="Guardar" />

            <a href="{{ $copyFromId === null ? route('requests.index') : route('requests.show', $copyFromId) }}"
                class="btn btn-outline-secondary ml-1">
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

            const amount = $('#amount');
            const card = $('#card');
            const account = $('#account');

            amount.on('change', function () {
                $wire.set('amount', $(this).val(), false);
            });

            card.on('change', function () {
                $wire.set('card', $(this).val(), false);
            });

            account.on('change', function () {
                $wire.set('account', $(this).val(), false);
            });

            Livewire.on('reset', () => {
                amount.val(null).trigger('change');
                card.val(null).trigger('change');
                account.val(null).trigger('change');
            });

            $("input[data-inputmask]").inputmask({
                rightAlign: false
            });
        });
    </script>
@endpush
