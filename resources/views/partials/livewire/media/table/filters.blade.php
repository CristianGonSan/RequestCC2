<div class="form-row my-1">
    <div class="form-group col-md-2 col-12 mb-0">
        <label class="text-muted mb-0">Tipo de archivo</label>
        <select wire:model.live="filters.group" class="custom-select">
            <option value="all">Todos</option>
            @foreach ($groupOptions as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-md-2 col-6 mb-0">
        <label class="text-muted mb-0">Ordenar por</label>
        <select wire:model.live="sortColumn" class="custom-select">
            <option value="created_at">Fecha</option>
            <option value="file_name">Nombre (A-Z)</option>
            <option value="size">Mayor tamaño</option>
        </select>
    </div>

    <div class="form-group col-md-2 col-6 mb-0">
        <label class="text-muted mb-0">Dirección</label>
        <select wire:model.live="sortDirection" class="custom-select">
            <option value="desc">Descendente</option>
            <option value="asc">Ascendente</option>
        </select>
    </div>

    <div class="form-group col-md-2 col-6 mb-0">
        <label class="text-muted mb-0">Fecha desde</label>
        <input type="date" class="form-control" wire:model.live="filters.dateFrom">
    </div>

    <div class="form-group col-md-2 col-6 mb-0">
        <label class="text-muted mb-0">Fecha hasta</label>
        <input type="date" class="form-control" wire:model.live="filters.dateTo">
    </div>

    <div class="form-group col-md-2 col-6 mb-0 d-flex align-items-end">
        <x-livewire.loading-button theme="{{ $filters['onlyMyFiles'] ? 'primary' : 'outline-primary' }}"
            class="w-100 mt-2" icon="user" wire:click="toggleOnlyMyFiles" wire:target="toggleOnlyMyFiles"
            label="Solo los míos"
            title="{{ $filters['onlyMyFiles'] ? 'Mostrar todos los archivos' : 'Mostrar solo mis archivos' }}" />
    </div>

    <div class="form-group col-md-3 col-6 mb-0 d-flex align-items-end">
        <x-livewire.loading-button theme="outline-info" class="w-100 mt-2" wire:click="resetFilters"
            wire:target="resetFilters" label="Reiniciar filtros" icon="rotate-left" />
    </div>
</div>
<hr class="my-3">
