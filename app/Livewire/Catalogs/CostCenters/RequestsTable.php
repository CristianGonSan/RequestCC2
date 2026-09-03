<?php

namespace App\Livewire\Catalogs\CostCenters;

use App\Enums\Requests\MoneyRequestStatus;
use App\Models\MoneyRequests\MoneyRequest;
use App\Traits\Livewire\Tables\HasLivewireTableBehavior;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class RequestsTable extends Component
{
    use HasLivewireTableBehavior;

    #[Locked]
    public int $costCenterId;

    public string $searchTerm = '';

    public int $perPage = 12;

    public int $page = 1;

    public string $sortColumn = 'id';

    public string $sortDirection = 'desc';

    protected array $theadConfig = [
        [
            'column' => 'id',
            'label'  => 'ID',
            'style'  => 'width: 1%',
        ],
        [
            'column' => 'amount',
            'label'  => 'Monto MXN',
            'style'  => 'width: 1%',
        ],
        [
            'column' => 'status',
            'label'  => 'Estado',
        ],
        [
            'label' => 'Concepto',
        ],
        [
            'column' => 'created_at',
            'label'  => 'Creado el',
            'style'  => 'width: 1%',
        ],
        [
            'label' => 'Ver más',
            'align' => 'center',
        ],
    ];

    public function mount(int $costCenterId): void
    {
        $this->costCenterId = $costCenterId;

        $this->setPage($this->page);
    }

    public function render(): View
    {
        $requests = $this->getQuery()->paginate($this->perPage);

        return view('livewire.catalogs.cost-centers.requests-table', [
            'requests' => $requests,
        ]);
    }

    private function getQuery(): Builder
    {
        $query = MoneyRequest::query();

        $query->where('cost_center_id', $this->costCenterId);

        if ($term = $this->searchTerm) {
            $query->whereAny(
                ['concept'],
                'like',
                "%$term%"
            );
        }

        if ($this->sortColumn === 'status') {
            $cases = collect(MoneyRequestStatus::cases())
                ->map(fn (MoneyRequestStatus $case) => "WHEN '{$case->value}' THEN '{$case->label()}'")
                ->implode(' ');

            $query->orderByRaw("CASE status $cases END {$this->sortDirection}");

            return $query;
        }

        $query->orderBy($this->sortColumn, $this->sortDirection);

        return $query;
    }
}
