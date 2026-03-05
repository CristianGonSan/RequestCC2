<?php

namespace App\Livewire\Admin\CostCenter;

use App\Models\Company;
use Livewire\Component;
use Livewire\WithPagination;

class ShowCostCenter extends Component
{
    use WithPagination;

    public $costCenter;

    public $company_id;
    public $name;
    public $description;

    public $companiesOptions;

    public function mount($costCenter) {
        $this->costCenter = $costCenter;

        $this->company_id = $costCenter->company_id;
        $this->name = $costCenter->name;
        $this->description = $costCenter->description;

        $companies = Company::all();

        foreach ($companies as $company) {
            $this->companiesOptions[$company->id] = $company->name;
        }
    }

    public function render()
    {
        return view('livewire.admin.cost-center.show-cost-center', [
            'requests' => $this->costCenter->requests()->orderBy('id', 'desc')->paginate(12, ['*'], 'requestsPage')
        ]);
    }

    public function save()
    {
        $validated = $this->validate([
            'company_id' => ['nullable', 'exists:companies,id'],
            'name' => ['required', 'string', 'max:255', 'unique:cost_centers,name,' . $this->costCenter->id],
            'description' => 'nullable'
        ]);

        $validated['company_id'] = $validated['company_id'] == '' ? null : $validated['company_id'];

        $this->costCenter->update($validated);

        session()->flash('message', 'Actualizado.');
    }

    public function disable() {
        $this->costCenter->enabled = false;
        $this->costCenter->save();
    }

    public function enable() {
        $this->costCenter->enabled = true;
        $this->costCenter->save();
    }

    public function delete() {
        $this->costCenter->delete();

        session()->flash('success', 'Centro de Cosotos Eliminado Exitosamente.');
        $this->redirect(route('admin.cost-centers.index'));
    }
}
