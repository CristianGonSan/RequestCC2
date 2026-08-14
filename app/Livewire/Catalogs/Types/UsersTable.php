<?php

namespace App\Livewire\Catalogs\Types;

use App\Models\User;
use App\Traits\Livewire\Tables\HasLivewireTableBehavior;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class UsersTable extends Component
{
    use HasLivewireTableBehavior;

    #[Locked]
    public int $typeId;

    public string $searchTerm = '';

    public int $perPage = 12;

    public int $page = 1;

    public string $sortColumn = 'id';

    public string $sortDirection = 'desc';

    protected array $theadConfig = [
        [
            'column' => 'id',
            'label' => 'ID',
            'style' => 'width: 1%;',
        ],
        [
            'column' => 'name',
            'label' => 'Nombre',
        ],
        [
            'column' => 'email',
            'label' => 'Email',
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

    public function mount(int $typeId): void
    {
        $this->typeId = $typeId;
        $this->setPage($this->page);
    }

    public function render(): View
    {
        $users = $this->getQuery()->paginate($this->perPage);

        return view('livewire.catalogs.types.users-table', [
            'users' => $users,
        ]);
    }

    private function getQuery(): Builder
    {
        $query = User::query();

        $query->whereHas('types', function ($query) {
            $query->where('type_id', $this->typeId);
        });

        if ($term = $this->searchTerm) {
            $query->whereAny(
                ['name', 'email'],
                'like',
                "%$term%"
            );
        }

        $query->orderBy($this->sortColumn, $this->sortDirection);

        return $query;
    }
}
