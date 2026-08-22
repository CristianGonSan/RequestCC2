@php
    $isActive = $material->is_active;
    $isInUse = $material->isInUse();
@endphp

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted">Nombre</dt>
                    <dd class="col-sm-8 font-weight-bold">{{ $material->name }}</dd>

                    <dt class="col-sm-4 text-muted">Código</dt>
                    <dd class="col-sm-8">{{ $material->code ?: 'Sin código' }}</dd>

                    <dt class="col-sm-4 text-muted">Unidad base</dt>
                    <dd class="col-sm-8">
                        <a href="{{ route('units.show', $material->baseUnit->id) }}" target="_blank">
                            {{ $material->baseUnit->name }} ({{ $material->baseUnit->symbol }})
                        </a>
                    </dd>

                    <dt class="col-sm-4 text-muted">Es externo</dt>
                    <dd class="col-sm-8">
                        {{ $material->is_external ? 'Si' : 'No' }}
                    </dd>

                    <dt class="col-sm-4 text-muted">Descripción</dt>
                    <dd class="col-sm-8 mb-0">{{ $material->description ?: 'Sin descripción' }}</dd>
                </dl>
            </div>
        </div>

        <div class="mb-3">
            <a href="{{ route('materials.edit', $material->id) }}" class="btn btn-outline-primary mr-1">
                <i class="fas fa-edit mr-1"></i> Editar
            </a>

            <x-livewire.loading-button label="Eliminar" theme="outline-danger" class="mr-1" icon="trash"
                wire:click="delete" wire:target="delete" wire:swal-delete="¿Eliminar este material?"
                :disabled="$isInUse" :title="$isInUse ? 'No se puede eliminar: el material está en uso' : ''" />

            <a href="{{ route('materials.index') }}" class="btn btn-outline-secondary mr-1">
                <i class="fas fa-fw fa-chevron-left mr-1"></i> Volver
            </a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="toggleActive"
                            {{ $isActive ? 'checked' : '' }} wire:click="toggleActive" wire:loading.attr="disabled"
                            wire:target="toggleActive" />
                        <label class="custom-control-label" for="toggleActive">
                            {{ $isActive ? 'Activo' : 'Inactivo' }}
                        </label>
                    </div>
                    <div class="text-primary">
                        <i class="fas fa-fw fa-circle-notch fa-spin" wire:loading wire:target='toggleActive'></i>
                    </div>
                </div>
                <hr>

                <dl class="row mb-0">
                    <dt class="col-6 text-muted">Creado</dt>
                    <dd class="col-6" title="{{ $material->created_at->format('d/m/Y H:i') }}" data-toggle="tooltip"
                        data-placement="left">
                        {{ $material->created_at->diffForHumans() }}
                    </dd>

                    <dt class="col-6 text-muted">Actualizado</dt>
                    <dd class="col-6 mb-0" title="{{ $material->updated_at->format('d/m/Y H:i') }}"
                        data-toggle="tooltip" data-placement="left">
                        {{ $material->updated_at->diffForHumans() }}
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>
