<?php

namespace App\Livewire\Catalogs\CostCenters;

use App\Models\Company;
use App\Models\CostCenter;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class CostCenterCreate extends Component
{
    use FlashToast, Toast;

    public string $name = '';
    public string $description = '';
    public ?int $company_id = null;

    public bool $createAnother = false;

    public function render(): View
    {
        $companies = Company::active()
            ->pluck('name', 'id');

        return view('livewire.catalogs.cost-centers.cost-center-create', [
            'companies' => $companies,
        ]);
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name'          => ['required', 'string', 'max:64', Rule::unique('cost_centers')],
            'description'   => ['nullable', 'string', 'max:255'],
            'company_id'    => ['required', 'integer', Rule::exists('companies', 'id')->where('is_active', true)],
        ]);

        $costCenter = CostCenter::create($validated);

        if ($this->createAnother) {
            $this->reset([
                'company_id',
                'name',
                'description',
            ]);

            $this->dispatch('reset');
            $this->toastSuccess('Centro de costos creado');
        } else {
            $this->flashToastSuccess('Centro de costos creado');
            redirect()->route('cost-centers.show', $costCenter->id);
        }
    }
}
