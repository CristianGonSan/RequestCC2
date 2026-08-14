@php
    $isActive = $company->is_active;
    $isInUse = $company->isInUse();
@endphp

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted">Nombre</dt>
                    <dd class="col-sm-8 font-weight-bold mb-0">{{ $company->name }}</dd>
                </dl>
            </div>
        </div>

        <div class="mb-3">
            <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-outline-primary mr-1">
                <i class="fas fa-edit mr-1"></i> Editar
            </a>

            <x-livewire.loading-button label="Eliminar" theme="outline-danger" class="mr-1" icon="trash"
                wire:click="delete" wire:target="delete" wire:swal-delete="¿Eliminar esta empresa?" :disabled="$isInUse"
                :title="$isInUse ? 'No se puede eliminar: la empresa está en uso' : ''" />

            <a href="{{ route('companies.index') }}" class="btn btn-outline-secondary mr-1">
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
                    <dd class="col-6" title="{{ $company->created_at->format('d/m/Y H:i') }}" data-toggle="tooltip"
                        data-placement="left">
                        {{ $company->created_at->diffForHumans() }}
                    </dd>

                    <dt class="col-6 text-muted">Actualizado</dt>
                    <dd class="col-6 mb-0" title="{{ $company->updated_at->format('d/m/Y H:i') }}" data-toggle="tooltip"
                        data-placement="left">
                        {{ $company->updated_at->diffForHumans() }}
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>
