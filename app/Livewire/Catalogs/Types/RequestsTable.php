<?php

namespace App\Livewire\Catalogs\Types;

use App\Enums\Requests\MoneyRequestStatus;
use App\Models\MoneyRequests\MoneyRequest;
use App\Models\Catalogs\Type;
use App\Traits\Livewire\Tables\HasLivewireTableBehavior;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class RequestsTable extends Component
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
            'style' => 'width: 1%',
        ],
        [
            'column' => 'amount',
            'label' => 'Monto MXN',
            'style' => 'width: 1%',
        ],
        [
            'column' => 'status',
            'label' => 'Estado',
        ],
        [
            'label' => 'Concepto',
        ],
        [
            'column' => 'created_at',
            'label' => 'Creado el',
            'style' => 'width: 1%',
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
        $moneyRequests = $this->getQuery()->paginate($this->perPage);

        return view('livewire.catalogs.types.requests-table', [
            'moneyRequests' => $moneyRequests,
        ]);
    }

    private function getQuery(): Builder
    {
        $query = MoneyRequest::query();

        $query->where('type_id', $this->typeId);

        if ($term = $this->searchTerm) {
            $query->whereAny(
                ['concept'],
                'like',
                "%$term%"
            );
        }

        if ($this->sortColumn === 'status') {
            $cases = collect(MoneyRequestStatus::cases())
                ->map(fn(MoneyRequestStatus $case) => "WHEN '{$case->value}' THEN '{$case->label()}'")
                ->implode(' ');

            $query->orderByRaw("CASE status $cases END {$this->sortDirection}");

            return $query;
        }

        $query->orderBy($this->sortColumn, $this->sortDirection);

        return $query;
    }
}
