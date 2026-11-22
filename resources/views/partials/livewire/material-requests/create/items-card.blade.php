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
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="text-nowrap border-top-0">
                    <tr>
                        <th style="min-width: 220px">Material</th>
                        <th style="min-width: 260px">Cantidad/Unidad *</th>
                        <th style="min-width: 160px; width: 160px;">Costo unitario *</th>
                        <th style="min-width: 160px">Total</th>
                        <th class="text-center" style="width: 1%">Quitar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $key => $item)
                        <tr wire:key="item-{{ $key }}">
                            <td class="align-middle">
                                {{ $item['material_name'] }}
                            </td>
                            <td class="align-middle p-2">
                                <div class="input-group input-group-sm">
                                    <input type="number" class="form-control" name="items.{{ $key }}.quantity_requested"
                                        placeholder="0" step="0.001" min="0.001" max="999999999.999"
                                        wire:model.live.debounce.300ms="items.{{ $key }}.quantity_requested" required>

                                    <select class="form-control custom-select" name="items.{{ $key }}.unit_id"
                                        wire:model="items.{{ $key }}.unit_id" required>
                                        @foreach ($unitOptions as $unitId => $unitName)
                                            <option value="{{ $unitId }}">{{ $unitName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </td>
                            <td class="align-middle p-2">
                                <x-adminlte-input type="number" name="items.{{ $key }}.unit_cost" placeholder="0.00"
                                    step="0.01" min="0" max="999999999999.99"
                                    wire:model.live.debounce.300ms="items.{{ $key }}.unit_cost" igroup-size="sm"
                                    fgroup-class="mb-0" required />
                            </td>
                            <td class="align-middle text-nowrap">{{ $totals['lineTotals'][$key] }}</td>
                            <td class="align-middle text-center">
                                @if ($item['can_delete'] ?? true)
                                    <x-livewire.loading-button theme="outline-danger" class="btn-sm" icon="trash-alt"
                                        title="Quitar material" wire:click="removeItem('{{ $key }}')"
                                        wire:target="removeItem('{{ $key }}')" />
                                @else
                                    <i class="fas fa-ban text-muted" title="Ya tiene cantidades suministradas"></i>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="fas fa-plus-circle fa-2x mb-2 d-block"></i>
                                Agregue un material para comenzar
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="font-weight-bold">
                        <td colspan="2"></td>
                        <td class="text-right">Subtotal</td>
                        <td class="text-nowrap">{{ $totals['subtotal'] }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td class="text-right">
                            <div class="input-group input-group-sm ml-auto">
                                <select class="form-control custom-select" id="charge_type" name="charge_type"
                                    aria-label="Tipo de cargo aplicado" wire:model.change="charge_type" required>
                                    @foreach ($chargeDetails as $value => $chargeDetail)
                                        <option value="{{ $value }}">
                                            {{ $chargeDetail['label'] }} ({{ $chargeDetail['percentage'] }}%)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </td>
                        <td class="text-nowrap">{{ $totals['chargeAmount'] }}</td>
                        <td></td>
                    </tr>
                    <tr class="font-weight-bold">
                        <td colspan="2"></td>
                        <td class="text-right">Total</td>
                        <td class="text-nowrap">{{ $totals['total'] }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
