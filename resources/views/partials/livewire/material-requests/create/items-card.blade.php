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
                        <th style="min-width: 200px">Unidad *</th>
                        <th style="min-width: 160px; width: 160px;">Cantidad *</th>
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
                                <x-adminlte-select fgroup-class="mb-0" class="custom-select" name="items.{{ $key }}.unit_id"
                                    wire:model="items.{{ $key }}.unit_id" igroup-size="sm">
                                    <x-adminlte-options :options="$unitOptions" empty-option="Selecciona la unidad..." />
                                </x-adminlte-select>
                            </td>
                            <td class="align-middle p-2">
                                <x-adminlte-input type="number" name="items.{{ $key }}.quantity_requested" placeholder="0"
                                    step="0.001" min="0.001" max="999999999.999"
                                    wire:model="items.{{ $key }}.quantity_requested" igroup-size="sm" fgroup-class="mb-0"
                                    required />
                            </td>
                            <td class="align-middle text-center">
                                <x-livewire.loading-button theme="outline-danger" class="btn-sm" icon="trash-alt"
                                    title="Quitar material" wire:click="removeItem('{{ $key }}')"
                                    wire:target="removeItem('{{ $key }}')" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-5">
                                <i class="fas fa-plus-circle fa-2x mb-2 d-block"></i>
                                Agregue un material para comenzar
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
