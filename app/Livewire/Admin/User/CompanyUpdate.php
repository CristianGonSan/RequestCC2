<?php

namespace App\Livewire\Admin\User;

use App\Models\Company;
use Livewire\Component;

class CompanyUpdate extends Component
{
    public $user;

    public $companies = [];
    public $selectedCompanies = [];

    public function mount($user)
    {
        $this->user = $user;

        $this->companies = Company::where('enabled', true)->get();

        foreach ($this->user->companies as $company) {
            $this->selectedCompanies[$company->id] = true;
        }
    }

    public function render()
    {
        return view('livewire.admin.user.company-update');
    }

    public function save()
    {
        $keys = [];

        foreach ($this->selectedCompanies as $key => $boolean) {
            if ($boolean) {
                $keys[] = $key;
            }
        }

        $this->user->companies()->sync($keys);

        session()->flash('company_message', 'Actualizado.');
    }
}
