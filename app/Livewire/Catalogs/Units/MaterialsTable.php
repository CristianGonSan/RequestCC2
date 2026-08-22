<?php

namespace App\Livewire\Catalogs\Units;

use App\Models\Catalogs\Material;
use App\Traits\Livewire\Tables\HasLivewireTableBehavior;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Session;
use Livewire\Component;

class MaterialsTable extends Component
{
    use HasLivewireTableBehavior;

    #[Locked]
    public int $unitId;

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
            'column' => 'code',
            'label' => 'Código',
        ],
        [
            'column' => 'is_external',
            'label' => 'Origen',
            'align' => 'center',
            'style' => 'width: 1%;',
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

    public function mount(int $unitId): void
    {
        $this->unitId = $unitId;
        $this->setPage($this->page);
    }

    public function render(): View
    {
        $materials = $this->getQuery()->paginate($this->perPage);

        return view('livewire.catalogs.units.materials-table', [
            'materials' => $materials,
        ]);
    }

    private function getQuery(): Builder
    {
        $query = Material::whereBaseUnitId($this->unitId);

        if ($term = $this->searchTerm) {
            $query->where(function (Builder $query) use ($term): void {
                $query->where('name', 'like', "%$term%")
                    ->orWhere('code', 'like', "%$term%");
            });
        }

        if ($this->sortColumn === 'code') {
            $query->orderByRaw("code IS NULL")
                ->orderBy('code', $this->sortDirection);

            return $query;
        }

        $sortable = [
            'id' => 'id',
            'name' => 'name',
            'code' => 'code',
            'is_active' => 'is_active',
            'is_external' => 'is_external',
        ];

        $column = $sortable[$this->sortColumn] ?? 'name';

        $query->orderBy($column, $this->sortDirection);

        return $query;
    }
}
