<?php

namespace App\Livewire\Admin\Company;

use Livewire\Component;
use Livewire\WithPagination;

class ShowCompany extends Component
{
    use WithPagination;

    public $company;

    public $name;
    public $description;

    public $ccName = 'C';
    public $ccDescription;

    public function mount($company) {
        $this->company = $company;

        $this->name = $company->name;
        $this->description = $company->description;
    }

    public function render()
    {
        return view('livewire.admin.company.show-company', [
            'users' => $this->company->users()->orderBy('id', 'desc')->paginate(12, ['*'], 'usersPage'),
            'costCenters' => $this->company->costCenters()->orderBy('name')->paginate(12, ['*'], 'costCentersPage')
        ]);
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable']
        ]);

        $this->company->update($validated);

        session()->flash('message', 'Actualizado.');
    }

    public function createCostCenter() {
        $validated = $this->validate([
            'ccName' => ['required', 'string', 'max:255', 'unique:cost_centers,name'],
            'ccDescription' => ['nullable']
        ]);

        $this->company->costCenters()->create([
            'name' => $validated['ccName'],
            'description' => $validated['ccDescription']
        ]);

        $this->reset(['ccName', 'ccDescription']);

        session()->flash('ccMessage', 'Creado.');
        $this->refreshPage();
    }

    public function disable() {
        $this->company->enabled = false;
        $this->company->save();
    }

    public function enable() {
        $this->company->enabled = true;
        $this->company->save();
    }

    public function delete() {
        $this->company->delete();

        session()->flash('success', 'Empresa Eliminada Exitosamente.');
        $this->redirect(route('admin.companies.index'));
    }

    public function refreshPage(): void
    {
        $this->resetPage();
    }
}
