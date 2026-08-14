<?php

namespace App\Livewire\Catalogs\Companies;

use App\Models\CostCenter;
use App\Traits\Livewire\Tables\HasLivewireTableBehavior;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Session;
use Livewire\Component;

class CostCentersTable extends Component
{
    use HasLivewireTableBehavior;

    #[Locked]
    public int $companyId;

    public string $searchTerm = '';

    public int $perPage = 12;

    public int $page = 1;

    public string $sortColumn = 'id';

    public string $sortDirection = 'desc';

    protected array $theadConfig = [
        [
            'column' => 'id',
            'label' => 'ID',
            'align' => 'center',
            'style' => 'width: 1%;',
        ],
        [
            'column' => 'name',
            'label' => 'Nombre',
        ],
        [
            'label' => 'Descripción',
        ],
        [
            'column' => 'is_active',
            'label' => 'Activo',
            'align' => 'center',
            'style' => 'width: 1%;',
        ],
        [
            'label' => 'Ver más',
            'align' => 'center',
        ],
    ];

    public function mount(int $companyId): void
    {
        $this->companyId = $companyId;
        $this->setPage($this->page);
    }

    public function render(): View
    {
        $costCenters = $this->getQuery()->paginate($this->perPage);

        return view('livewire.catalogs.companies.cost-centers-table', [
            'costCenters' => $costCenters,
        ]);
    }

    private function getQuery(): Builder
    {
        $query = CostCenter::where('cost_centers.company_id', $this->companyId)
            ->join('companies', 'cost_centers.company_id', '=', 'companies.id')
            ->with([
                'company:id,name',
            ])
            ->select('cost_centers.*');

        if ($term = $this->searchTerm) {
            $query->where(function (Builder $query) use ($term): void {
                $query->where('cost_centers.name', 'like', "%$term%")
                    ->orWhere('cost_centers.description', 'like', "%$term%");
            });
        }

        $sortable = [
            'name'          => 'cost_centers.name',
            'description'   => 'cost_centers.description',
            'is_active'     => 'cost_centers.is_active',
        ];

        $column = $sortable[$this->sortColumn] ?? 'cost_centers.name';

        $query->orderBy($column, $this->sortDirection);

        return $query;
    }
}
