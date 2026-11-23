<div class="card">
    <div class="card-header">
        <div class="form-row align-items-end">
            <x-form.select-wire-ignore fgroup-class="col-md-8 mb-0" label-class="text-muted mb-0" id="material_id"
                name="material_id" label="Material *" wire:loading.attr="readonly" wire:target="save,addItem" />

            <div class="col-md-4">
                <x-livewire.loading-button label="Agregar material" icon="plus" wire:click="addItem"
                    wire:target="addItem" class="btn-block mt-2" />
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <ul class="list-group list-group-flush">
            @forelse ($items as $key => $item)
                <li class="list-group-item p-3" wire:key="item-{{ $key }}">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <div>
                            <strong>{{ $item['material_name'] }}</strong>@if ($item['material_code'] ?? null)
                                <strong class="text-muted"> ({{ $item['material_code'] }})</strong>
                            @endif
                        </div>
                        @if ($item['can_delete'] ?? true)
                            <x-livewire.loading-button theme="outline-danger" class="btn-sm ml-2" icon="trash-alt"
                                title="Quitar material" wire:click="removeItem('{{ $key }}')"
                                wire:target="removeItem('{{ $key }}')" />
                        @else
                            <i class="fas fa-ban text-muted ml-2" title="Ya tiene cantidades suministradas"></i>
                        @endif
                    </div>

                    <div class="form-row align-items-end">
                        <div class="form-group col-md-3 mb-2">
                            <label class="text-muted mb-0" for="item-quantity-{{ $key }}">Cantidad *</label>
                            <input id="item-quantity-{{ $key }}" type="number" class="form-control "
                                name="items.{{ $key }}.quantity_requested" placeholder="0" step="0.001" min="0.001"
                                max="999999999.999" wire:model.live.debounce.300ms="items.{{ $key }}.quantity_requested"
                                required>
                        </div>
                        <div class="form-group col-md-2 mb-2">
                            <label class="text-muted mb-0" for="item-unit-{{ $key }}">Unidad *</label>
                            <select id="item-unit-{{ $key }}" class="form-control custom-select "
                                name="items.{{ $key }}.unit_id" wire:model="items.{{ $key }}.unit_id" required>
                                @foreach ($unitOptions as $unitId => $unitName)
                                    <option value="{{ $unitId }}">{{ $unitName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3 mb-2">
                            <label class="text-muted mb-0" for="item-cost-{{ $key }}">Costo unitario *</label>
                            <input id="item-cost-{{ $key }}" type="number" class="form-control "
                                name="items.{{ $key }}.unit_cost" placeholder="0.00" step="0.01" min="0"
                                max="999999999999.99" wire:model.live.debounce.300ms="items.{{ $key }}.unit_cost" required>
                        </div>
                        <div class="form-group col-md-4 mb-2">
                            <label class="text-muted mb-0" for="item-total-{{ $key }}">Total del material</label>
                            <input id="item-total-{{ $key }}" type="text" class="form-control "
                                value="${{ number_format_decimal($totals['lineTotals'][$key], 2) }}" readonly>
                        </div>
                    </div>

                    <div class="form-group mt-1 mb-0">
                        <label class="text-muted mb-0" for="item-notes-{{ $key }}">Notas</label>
                        <textarea id="item-notes-{{ $key }}" class="form-control" name="items.{{ $key }}.notes" rows="1"
                            placeholder="Especifique detalles para este material..." maxlength="255"
                            wire:model="items.{{ $key }}.notes"></textarea>
                    </div>
                </li>
            @empty
                <li class="list-group-item text-center text-muted py-5">
                    <i class="fas fa-plus-circle fa-2x mb-2 d-block"></i>
                    Agregue un material para comenzar
                </li>
            @endforelse
        </ul>
    </div>

    <div class="card-footer">
        <div class="row align-items-center">
            <div class="form-group col-md-4 mb-2 mb-md-0">
                <label class="text-muted mb-1" for="request-subtotal">Subtotal</label>
                <input id="request-subtotal" type="text" class="form-control"
                    value="${{ number_format_decimal($totals['subtotal'], 2) }}" readonly>
            </div>
            <div class="form-group col-md-4 mb-2 mb-md-0">
                <label class="text-muted mb-1" for="request-charge-amount">Cargo</label>
                <div class="input-group">
                    <select class="custom-select" id="charge_type" name="charge_type"
                        aria-label="Tipo de cargo aplicado" wire:model.change="charge_type" required>
                        @foreach ($chargeDetails as $value => $chargeDetail)
                            <option value="{{ $value }}">
                                {{ $chargeDetail['label'] }} ({{ $chargeDetail['percentage'] }}%)
                            </option>
                        @endforeach
                    </select>
                    <input id="request-charge-amount" type="text" class="form-control"
                        value="${{ number_format_decimal($totals['chargeAmount'], 2) }}" readonly>
                </div>
            </div>
            <div class="form-group col-md-4 mb-0 font-weight-bold">
                <label class="text-muted mb-1" for="request-total">Total</label>
                <input id="request-total" type="text" class="form-control"
                    value="${{ number_format_decimal($totals['total'], 2) }}" readonly>
            </div>
        </div>
    </div>
</div>
