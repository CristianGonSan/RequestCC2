<?php

namespace App\Livewire\Catalogs\Companies;

use App\Models\Company;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class CompanyCreate extends Component
{
    use FlashToast, Toast;

    public string $name = '';

    public bool $createAnother = false;

    public function render(): View
    {
        return view('livewire.catalogs.companies.company-create');
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:64', Rule::unique('companies')],
        ]);

        $company = Company::create($validated);

        if ($this->createAnother) {
            $this->reset([
                'name',
            ]);

            $this->dispatch('reset');
            $this->toastSuccess('Empresa creada');
        } else {
            $this->flashToastSuccess('Empresa creada');
            redirect()->route('companies.show', $company->id);
        }
    }
}
