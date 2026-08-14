<?php

namespace App\Livewire\Account;

use App\Traits\Livewire\Tables\HasLivewireTableBehavior;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

        return view('livewire.account.types-table', [
            'types' => $types,
        ]);
    }

    private function getQuery(): BelongsToMany
    {
        $query = Auth()->user()->types();

        if ($term = $this->searchTerm) {
            $query->whereAny(
                ['name', 'description'],
                'like',
                "%$term%"
            );
        }

        $query->orderBy($this->sortColumn, $this->sortDirection);

        return $query;
    }
}
