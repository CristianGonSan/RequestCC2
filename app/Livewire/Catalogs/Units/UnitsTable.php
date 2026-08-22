<?php

namespace App\Livewire\Catalogs\Units;

use App\Models\Catalogs\Unit;
use App\Traits\Livewire\Tables\HasLivewireTableBehavior;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Session;
use Livewire\Component;

class UnitsTable extends Component
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
            'column' => 'symbol',
            'label' => 'Símbolo',
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
        $units = $this->getQuery()->paginate($this->perPage);

        return view('livewire.catalogs.units.units-table', [
            'units' => $units,
        ]);
    }

    private function getQuery(): Builder
    {
        $query = Unit::query();

        if ($term = $this->searchTerm) {
            $query->where(function (Builder $query) use ($term): void {
                $query->where('name', 'like', "%$term%")
                    ->orWhere('symbol', 'like', "%$term%");
            });
        }

        $sortable = [
            'id'        => 'id',
            'name'      => 'name',
            'symbol'    => 'symbol',
            'is_active' => 'is_active',
        ];

        $column = $sortable[$this->sortColumn] ?? 'name';

        $query->orderBy($column, $this->sortDirection);

        return $query;
    }
}
