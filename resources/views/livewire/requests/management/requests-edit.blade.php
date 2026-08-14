<div>
    <form wire:submit="save">
        <div class="card">
            <div class="card-header bg-dark">
                <ul class="nav nav-tabs card-header-tabs">
                    @if ($requestModel->is_transfer)
                        <li class="nav-item">
                            <a class="nav-link active">
                                <i class="fa-solid fa-credit-card mr-1"></i>Transferencia
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link active">
                                <i class="fa-solid fa-money-bill mr-1"></i>Efectivo
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
            <div class="card-body">
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

                @if ($requestModel->is_transfer)
                    <div class="form-row">
                        <x-adminlte-input fgroup-class="col-md-4" name="bank" label="Banco *" placeholder="banco"
                            wire:model="bank" maxlength="128" x-bind:disabled="!isTransfer" />

                        <x-form.input-wire-ignore fgroup-class="col-md-4" name="card" label="Tarjeta/CLABE *"
                            placeholder="tarjeta" wire:model="card" x-bind:disabled="!isTransfer"
                            data-inputmask="'mask': '****-****-****-****[-****]', 'placeholder': '_'" />

                        <x-form.input-wire-ignore fgroup-class="col-md-4" name="account" label="Cuenta"
                            placeholder="cuenta" wire:model="account" x-bind:disabled="!isTransfer"
                            data-inputmask="'mask': '****-****-****-****[-****]'" />

                        <x-adminlte-input fgroup-class="col-md-4" name="branch" label="Sucursal" placeholder="sucursal"
                            wire:model="branch" maxlength="128" x-bind:disabled="!isTransfer" />

                        <x-adminlte-input fgroup-class="col-md-4" name="reference" label="Referencia"
                            placeholder="referencia" wire:model="reference" maxlength="128"
                            x-bind:disabled="!isTransfer" />

                        <x-adminlte-input fgroup-class="col-md-4" name="covenant" label="Convenio"
                            placeholder="convenio" wire:model="covenant" maxlength="128"
                            x-bind:disabled="!isTransfer" />
                    </div>
                @endif
            </div>
        </div>
        <div class="mb-3">
            <x-livewire.loading-button type='submit' label="Guardar" />

            <a href="{{ route('management.requests.show', $requestModel->id) }}" class="btn btn-outline-secondary ml-1">
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
                .appendConfig({
                    placeholder: 'Selecciona el tipo',
                    minimumInputLength: 0
                }).build();

            const costCenterSelect = select2Builder.selector('#cost_center_id').wireModel('cost_center_id')
                .value(@json($cost_center_id), @json($costCenterText))
                .appendConfig({
                    placeholder: 'Selecciona el centro de costos',
                    ajax: {
                        url: "{{ route('lookups.cost-centers.select2') }}",
                        dataType: 'json',
                        delay: 250,
                        cache: true,
                        data: function(params) {
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

            const amount = $('#amount');
            const card = $('#card');
            const account = $('#account');

            amount.on('change', function() {
                $wire.set('amount', $(this).val(), false);
            });

            card.on('change', function() {
                $wire.set('card', $(this).val(), false);
            });

            account.on('change', function() {
                $wire.set('account', $(this).val(), false);
            });

            $("input[data-inputmask]").inputmask({
                rightAlign: false
            });

        });
    </script>
@endpush
