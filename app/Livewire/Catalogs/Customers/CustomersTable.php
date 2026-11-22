<?php

namespace App\Livewire\Catalogs\Customers;

use App\Models\Catalogs\Customer;
use App\Traits\Livewire\Tables\HasLivewireTableBehavior;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Session;
use Livewire\Component;

use function in_array;

class CustomersTable extends Component
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
        $customers = $this->getQuery()->paginate($this->perPage);

        return view('livewire.catalogs.customers.customers-table', [
            'customers' => $customers,
        ]);
    }

    private function getQuery(): Builder
    {
        $query = Customer::query();

        if ($term = $this->searchTerm) {
            $query->where('name', 'like', "%$term%");
        }

        $column = in_array($this->sortColumn, ['id', 'name', 'is_active'], true)
            ? $this->sortColumn
            : 'name';

        return $query->orderBy($column, $this->sortDirection);
    }
}
