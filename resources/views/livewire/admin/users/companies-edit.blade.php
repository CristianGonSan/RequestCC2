<form wire:submit="save">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Seleccionar Empresas</h2>
        </div>

        <div class="card-body">
            <div class="row">
                @foreach ($companies as $company)
                    <div class="col-md-4 col-sm-6 mb-2" wire:key="company-{{ $company->id }}">
                        <div class="icheck-primary">
                            <input type="checkbox" id="company_{{ $company->id }}"
                                wire:model="selectedCompanies.{{ $company->id }}" />
                            <label for="company_{{ $company->id }}">
                                {{ $company->name }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="mb-3 mt-3">
        <x-livewire.loading-button type="submit" label="Actualizar" />
    </div>
</form>
