<div class="card mb-0">
    <form wire:submit.prevent="save">
        <div class="card-header">
            Empresas
        </div>
        <ul class="list-group list-group-flush overflow-auto" style="max-height: 400px;">
            @forelse ($companies as $company)
                <li class="list-group-item py-1" wire:key="company-{{ $company->id }}">
                    <div class="icheck-primary">
                        <input type="checkbox" id="company_{{ $company->id }}"
                            wire:model.defer="selectedCompanies.{{ $company->id }}" />
                        <label for="company_{{ $company->id }}">
                            {{ $company->name }}
                        </label>
                    </div>
                </li>
            @empty
                <li class="list-group-item text-muted">No hay datos.</li>
            @endforelse
        </ul>
        <div class="card-footer d-flex align-items-center justify-content-end">
            @if (session()->has('company_message'))
                <span class="text-success mr-3">{{ session('company_message') }}</span>
            @endif
            <button class="btn btn-outline-success btn-sm" type="submit">
                <i class="fas fa-lg fa-arrows-rotate mr-1" wire:loading.class="fa-spin"></i>Actualizar
            </button>
        </div>
    </form>
</div>
