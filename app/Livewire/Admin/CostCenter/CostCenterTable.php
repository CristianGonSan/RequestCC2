<?php

namespace App\Livewire\Admin\CostCenter;

use App\Models\CostCenter;
use Livewire\Component;
use Livewire\WithPagination;

class CostCenterTable extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 12;

    public string $orderBy = 'id';
    public string $orderDirection = 'desc';

    protected array $queryString = ['search', 'perPage', 'orderBy', 'orderDirection'];

    public function render()
    {
        $costCenters = $this->getQuery()->with('company')->paginate($this->perPage);
        return view('livewire.admin.cost-center.cost-center-table', [
            'costCenters' => $costCenters
        ]);
    }

    public function getQuery()
    {
        $query = CostCenter::query();

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
