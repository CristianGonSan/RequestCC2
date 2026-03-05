<?php

namespace App\Livewire\Admin\Company;

use App\Models\Company;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyTable extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 12;

    public string $orderBy = 'id';
    public string $orderDirection = 'desc';

    protected array $queryString = ['search', 'perPage', 'orderBy', 'orderDirection'];


    public function render()
    {
        $companies = $this->getQuery()->paginate($this->perPage);
        return view('livewire.admin.company.company-table', [
            'companies' => $companies
        ]);
    }

    public function getQuery()
    {
        $query = Company::query();

        $query->orderBy($this->orderBy, $this->orderDirection);

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        return $query;
    }

    public function refreshPage(): void
    {
        $this->resetPage();
    }
}
