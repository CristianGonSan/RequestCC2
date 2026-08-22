<?php

namespace App\Livewire\Catalogs\Materials;

use App\Models\Catalogs\Material;
use App\Traits\Livewire\Tables\HasLivewireTableBehavior;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Session;
use Livewire\Component;

class MaterialsTable extends Component
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
            'column' => 'code',
            'label' => 'Código',
        ],
        [
            'column' => 'unit',
            'label' => 'Unidad base',
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

    public function mount(): void
    {
        $this->setPage($this->page);
    }

    public function render(): View
    {
        $materials = $this->getQuery()->paginate($this->perPage);

        return view('livewire.catalogs.materials.materials-table', [
            'materials' => $materials,
        ]);
    }

    private function getQuery(): Builder
    {
        $query = Material::query();

        $query->with([
            'baseUnit:id,name,symbol',
        ]);

        $query->join('units', 'materials.base_unit_id', '=', 'units.id')
            ->select('materials.*');

        if ($term = $this->searchTerm) {
            $query->where(function (Builder $query) use ($term): void {
                $query->where('materials.name', 'like', "%$term%")
                    ->orWhere('materials.code', 'like', "%$term%")
                    ->orWhere('units.name', 'like', "%$term%");
            });
        }

        if ($this->sortColumn === 'code') {
            $query->orderByRaw("materials.code IS NULL")
                ->orderBy('materials.code', $this->sortDirection);

            return $query;
        }

        $sortable = [
            'id' => 'materials.id',
            'name' => 'materials.name',
            'code' => 'materials.code',
            'is_active' => 'materials.is_active',
            'is_external' => 'materials.is_external',
            'unit' => 'units.name',
        ];

        $column = $sortable[$this->sortColumn] ?? 'materials.name';

        $query->orderBy($column, $this->sortDirection);

        return $query;
    }
}
