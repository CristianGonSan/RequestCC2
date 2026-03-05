<div class="mb-3">
    <div class="row" wire:ignore>
        <div class="input-group col-md-11 col-10">
            <input wire:keydown.enter="search()" type="text" id="search" name="search" wire:model="filters.search"
                class="form-control" placeholder="Enter para Buscar...">
            <div class="input-group-append">
                <button wire:click="search()" class="btn btn-outline-info">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </div>

        <div class="col-md-1 col-2">
            <button class="btn btn-outline-info w-100" data-toggle="collapse" data-target="#filters1"
                aria-expanded="true" aria-controls="filters1">
                <i class="fas fa-filter"></i>
            </button>
        </div>
    </div>
    <div id="filters1" class="collapse row mt-3" wire:ignore>
        <div class="col-md-2 mb-1">
            <select id="perPage" name="perPage" wire:model="filters.perPage" class="custom-select">
                <option value="12">12 por página</option>
                <option value="24">24 por página</option>
                <option value="36">36 por página</option>
                <option value="48">48 por página</option>
                <option value="60">60 por página</option>
            </select>
        </div>
        <div class="col-md-2 col-6 mb-1">
            <select id="orderBy" name="orderBy" wire:model="filters.orderBy" class="custom-select">
                <option value="created_at">Ordenar por Fecha</option>
                <option value="payee">Ordenar por Beneficiario</option>
                <option value="cost_center">Ordenar por Centro de Costos</option>
                <option value="amount">Ordenar por Monto</option>
                <option value="status">Ordenar por Estatus</option>
                <option value="type">Ordenar por Tipo</option>
            </select>
        </div>
        <div class="col-md-2 col-6 mb-1">
            <select id="orderDirection" name="orderDirection" wire:model="filters.orderDirection"
                class="custom-select">
                <option value="desc">Descendente</option>
                <option value="asc">Ascendente</option>
            </select>
        </div>
        <div class="col-md-2 mb-1">
            <select id="payMethod" name="payMethod" wire:model="filters.payMethod" class="custom-select">
                <option value="-1">Filtrar por método</option>
                <option value="0">Efectivo</option>
                <option value="1">Transferencia</option>
            </select>
        </div>
        <x-adminlte-select fgroup-class="col-md-2 mb-1" class="custom-select" name="type"
            wire:model="filters.type" placeholder="Filtrar por tipo" label-class="text-lightblue" required
            enable-old-support>
            <x-adminlte-options :options="$typeOptions" empty-option="Filtrar por tipo" />
        </x-adminlte-select>
        <x-adminlte-select fgroup-class="col-md-2 mb-1" class="custom-select" name="status"
            wire:model="filters.status" placeholder="Filtrar por tipo" label-class="text-lightblue" required
            enable-old-support>
            <x-adminlte-options :options="$statusOptions" empty-option="Filtrar por estatus" />
        </x-adminlte-select>

        <div class="col-md-2 col-6 mb-1">
            <input type="number" id="minAmount" min="0" name="minAmount" wire:model="filters.minAmount"
                class="form-control" placeholder="monto minimo">
        </div>

        <div class="col-md-2 col-6 mb-0">
            <input type="number" id="maxAmount" min="0" name="maxAmount" wire:model="filters.maxAmount"
                class="form-control" placeholder="monto maximo">
        </div>

        <div class="col-md-2 col-6 mb-1">
            <input type="date" id="minDate" name="minDate" wire:model="filters.minDate"
                class="form-control" placeholder="fecha minima">
        </div>

        <div class="col-md-2 col-6 mb-0">
            <input type="date" id="maxDate" name="maxDate" wire:model="filters.maxDate"
                class="form-control" placeholder="fecha maxima">
        </div>

        <div class="col-md-2 col-6 mb-0">
            <button type="button" id="reset" wire:click="resetFilters" class="btn btn-outline-info w-100">
                Reiniciar Filtros
            </button>
        </div>

        <div class="col-md-2 col-6 mb-0">
            <button class="btn btn-outline-success w-100" wire:click="export">
                <i class="fa-solid fa-file-excel mr-1"></i> Exportar
            </button>
        </div>
    </div>

    <div wire:loading class="col-md-12">
        <div class="d-flex align-items-center justify-content-center p-2">
            <div class="spinner-border text-primary mr-2" role="status">
                <span class="sr-only">Cargando...</span>
            </div>
            <strong>Procesando su solicitud, por favor espere...</strong>
        </div>
    </div>
</div>
