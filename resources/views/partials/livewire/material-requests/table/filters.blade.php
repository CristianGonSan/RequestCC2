<div class="form-row my-1">
    <x-adminlte-select fgroup-class="col-md-4 col-6 mb-0" class="custom-select" name="orderBy"
        wire:model.live="sortColumn" label="Ordenar por" label-class="text-muted mb-0">
        <option value="created_at">Fecha</option>
        <option value="id">ID</option>
        <option value="cost_center">Centro de Costos</option>
        <option value="total_spent">Total gastado</option>
        <option value="status">Estatus</option>
        <option value="type">Tipo</option>
    </x-adminlte-select>

    <x-adminlte-select fgroup-class="col-md-2 col-6 mb-0" class="custom-select" name="orderDirection"
        wire:model.live="sortDirection" label="Dirección" label-class="text-muted mb-0">
        <option value="desc">Descendente</option>
        <option value="asc">Ascendente</option>
    </x-adminlte-select>

    <x-adminlte-select fgroup-class="col-md-2 col-6 mb-0" class="custom-select" name="type"
        wire:model.live="filters.type" placeholder="Filtrar por tipo" label="Tipo" label-class="text-muted mb-0">
        <x-adminlte-options :options="$typeOptions" empty-option="Filtrar por tipo" />
    </x-adminlte-select>

    <x-adminlte-select fgroup-class="col-md-2 col-6 mb-0" class="custom-select" name="status"
        wire:model.live="filters.status" placeholder="Filtrar por estatus" label="Estatus"
        label-class="text-muted mb-0">
        <x-adminlte-options :options="$statusOptions" empty-option="Filtrar por estatus" />
    </x-adminlte-select>

    <div class="form-group col-md-2 col-6 mb-0">
        <label class="text-muted mb-0">Total mínimo</label>
        <input type="number" class="form-control" min="0" step="0.01" placeholder="-∞"
            wire:model.live.debounce.600ms="filters.minTotalSpent" />
    </div>

    <div class="form-group col-md-2 col-6 mb-0">
        <label class="text-muted mb-0">Total máximo</label>
        <input type="number" class="form-control" min="0" step="0.01" placeholder="∞"
            wire:model.live.debounce.600ms="filters.maxTotalSpent" />
    </div>

    <div class="form-group col-md-2 col-6 mb-0">
        <label class="text-muted mb-0">Fecha mínima</label>
        <input type="datetime-local" class="form-control" wire:model.live="filters.minDate" />
    </div>

    <div class="form-group col-md-2 col-6 mb-0">
        <label class="text-muted mb-0">Fecha máxima</label>
        <input type="datetime-local" class="form-control" wire:model.live="filters.maxDate" />
    </div>

    <div class="form-group col-md-2 col-6 mb-0 d-flex align-items-end">
        <x-livewire.loading-button theme="outline-info" class="w-100 mt-2" wire:click="resetFilters"
            wire:target='resetFilters' label="Restablecer Filtros" icon="rotate-left" />
    </div>
</div>
<hr class="my-2">
