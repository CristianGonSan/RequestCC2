<?php

namespace App\Livewire\Media;

use App\Models\Catalogs\CostCenter;
use App\Traits\Livewire\Tables\HasLivewireTableBehavior;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Session;
use Livewire\Component;

class MediaTable extends Component
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
            'label'  => 'ID',
            'align'  => 'center',
            'style'  => 'width: 1%;',
        ],
        [
            'column' => 'name',
            'label'  => 'Nombre',
        ],
        [
            'column' => 'company',
            'label'  => 'Empresa',
        ],
        [
            'label' => 'Descripción',
        ],
        [
            'column' => 'is_active',
            'label'  => 'Activo',
            'align'  => 'center',
            'style'  => 'width: 1%;',
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

        return view('livewire.media.media-table', [
            'costCenters' => $costCenters,
        ]);
    }

    private function getQuery(): Builder
    {
        $query = CostCenter::query();

        $query->with([
            'user:id',
        ]);

        $query->join('users', 'media.user_id', '=', 'users.id')
            ->select('media.*');

        if ($term = $this->searchTerm) {
            $query->where(function (Builder $query) use ($term): void {
                $query->where('media.name', 'like', "%$term%")
                    ->orWhere('users.name', 'like', "%$term%")
                    ->orWhere('companies.name', 'like', "%$term%");
            });
        }

        $sortable = [
            'id'          => 'cost_centers.id',
            'name'        => 'cost_centers.name',
            'description' => 'cost_centers.description',
            'is_active'   => 'cost_centers.is_active',
            'company'     => 'companies.name',
        ];

        $column = $sortable[$this->sortColumn] ?? 'cost_centers.name';

        $query->orderBy($column, $this->sortDirection);

        return $query;
    }
}
