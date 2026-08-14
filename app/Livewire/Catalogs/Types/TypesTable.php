<?php

namespace App\Livewire\Catalogs\Types;

use App\Models\Type;
use App\Traits\Livewire\Tables\HasLivewireTableBehavior;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Session;
use Livewire\Component;

class TypesTable extends Component
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
        $types = $this->getQuery()->paginate($this->perPage);

        return view('livewire.catalogs.types.types-table', [
            'types' => $types,
        ]);
    }

    private function getQuery(): Builder
    {
        $query = Type::query();

        if ($term = $this->searchTerm) {
            $query->whereAny(
                ['name'],
                'like',
                "%$term%"
            );
        }

        $query->orderBy($this->sortColumn, $this->sortDirection);

        return $query;
    }
}
