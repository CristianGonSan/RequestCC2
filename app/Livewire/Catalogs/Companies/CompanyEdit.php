<?php

namespace App\Livewire\Catalogs\Companies;

use App\Models\Company;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class CompanyEdit extends Component
{
    use FlashToast, Toast;

    #[Locked]
    public int $companyId;

    public string $name;

    public function mount(int $companyId): void
    {
        $this->companyId    = $companyId;
        $company            = $this->company();

        $this->name         = $company->name;
    }

    public function render(): View
    {
        return view('livewire.catalogs.companies.company-edit');
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:64', Rule::unique('companies')->ignore($this->companyId)],
        ]);

        $this->company()->update($validated);

        $this->toastSuccess('Información actualizada');
    }

    private ?Company $company = null;

    private function company(): Company
    {
        return $this->company ??= Company::findOrFail($this->companyId);
    }
}
