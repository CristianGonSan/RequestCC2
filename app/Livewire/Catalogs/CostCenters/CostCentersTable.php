<?php

namespace App\Livewire\Catalogs\CostCenters;

use App\Models\CostCenter;
use App\Traits\Livewire\Tables\HasLivewireTableBehavior;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Session;
use Livewire\Component;

class CostCentersTable extends Component
{
    use HasLivewireTableBehavior;

    #[Session]
    public string $searchTerm = '';

    #[Session]
    public int $perPage = 12;

    #[Session]
    public int $page = 1;

    #[Session]
    public string $sortColumn = 'id';

    #[Session]
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
            'column' => 'company',
            'label' => 'Empresa',
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

    public function mount(): void
    {
        $this->setPage($this->page);
    }

    public function render(): View
    {
        $costCenters = $this->getQuery()->paginate($this->perPage);

        return view('livewire.catalogs.cost-centers.cost-centers-table', [
            'costCenters' => $costCenters,
        ]);
    }

    private function getQuery(): Builder
    {
        $query = CostCenter::query();

        $query->with([
            'company:id,name',
        ]);

        $query->join('companies', 'cost_centers.company_id', '=', 'companies.id')
            ->select('cost_centers.*');

        if ($term = $this->searchTerm) {
            $query->where(function (Builder $query) use ($term): void {
                $query->where('cost_centers.name', 'like', "%$term%")
                    ->orWhere('cost_centers.description', 'like', "%$term%")
                    ->orWhere('companies.name', 'like', "%$term%");
            });
        }

        $sortable = [
            'id' => 'cost_centers.id',
            'name' => 'cost_centers.name',
            'description' => 'cost_centers.description',
            'is_active' => 'cost_centers.is_active',
            'company' => 'companies.name',
        ];

        $column = $sortable[$this->sortColumn] ?? 'cost_centers.name';

        $query->orderBy($column, $this->sortDirection);

        return $query;
    }
}
