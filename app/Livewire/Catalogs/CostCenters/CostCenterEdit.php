<?php

namespace App\Livewire\Catalogs\CostCenters;

use App\Models\Catalogs\Company;
use App\Models\Catalogs\CostCenter;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class CostCenterEdit extends Component
{
    use FlashToast, Toast;

    #[Locked]
    public int $costCenterId;

    public string $name;
    public string $description;
    public int $company_id;
    public string $companyText;

    public function mount(int $costCenterId): void
    {
        $this->costCenterId         = $costCenterId;
        $costCenter                 = $this->costCenter();

        $this->company_id           = $costCenter->company_id;
        $this->companyText          = $costCenter->company->name;
        $this->name                 = $costCenter->name;
        $this->description          = $costCenter->description;
    }

    public function render(): View
    {
        return view('livewire.catalogs.cost-centers.cost-center-edit', [
            'companies' => Company::options(),
        ]);
    }

    public function save(): void
    {
        $costCenter = $this->costCenter();

        $rules = [
            'name'          => ['required', 'string', 'max:64', Rule::unique('cost_centers')->ignore($costCenter->id)],
            'description'   => ['nullable', 'string', 'max:255'],
        ];

        if ($this->company_id !== $costCenter->company_id) {
            $rules['company_id'] = ['required', 'integer', Rule::exists('companies', 'id')->where('is_active', true),];
        }

        $validated = $this->validate($rules);

        $costCenter->update($validated);

        $this->toastSuccess('Información actualizada');
    }

    private ?CostCenter $costCenter = null;

    private function costCenter(): CostCenter
    {
        return $this->costCenter ??= CostCenter::findOrFail($this->costCenterId);
    }
}
