<?php

namespace App\Livewire\MaterialRequests\Users;

use App\Enums\Requests\MaterialRequestStatus;
use App\Models\Catalogs\Type;
use App\Models\MaterialRequests\MaterialRequest;
use App\Support\DataBag;
use App\Traits\Livewire\MoneyRequests\HasMoneyRequestTable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Session;
use Livewire\Component;

class MaterialRequestsTable extends Component
{
    use HasMoneyRequestTable;

    #[Session]
    public string $searchTerm = '';

    #[Session]
    public int $perPage = 12;

    #[Session]
    public int $page = 1;

    #[Session]
    public string $sortColumn = 'created_at';

    #[Session]
    public string $sortDirection = 'desc';

    #[Session]
    public array $filters = [
        'type'          => null,
        'status'        => null,
        'minTotalSpent' => null,
        'maxTotalSpent' => null,
        'minDate'       => null,
        'maxDate'       => null,
    ];

    public function mount(): void
    {
        $this->setPage($this->page);
    }

    public function render(): View
    {
        $materialRequests = $this->getQuery()->paginate($this->perPage);

        return view('livewire.material-requests.users.material-requests-table', [
            'materialRequests' => $materialRequests,
            'statusOptions'    => MaterialRequestStatus::options(),
            'typeOptions'      => Type::options(),
        ]);
    }

    private function getQuery(): Builder
    {
        $query      = MaterialRequest::query();
        $filtersBag = DataBag::make($this->filters);

        $query->with([
            'costCenter:id,name',
            'type:id,name',
            'user:id,name'
        ])
            ->withCount('items')
            ->withSum('items as items_requested_sum', 'quantity_requested')
            ->withSum('items as items_fulfilled_sum', 'quantity_fulfilled');

        $query->join('cost_centers', 'material_requests.cost_center_id', '=', 'cost_centers.id')
            ->join('types', 'material_requests.type_id', '=', 'types.id')
            ->addSelect('material_requests.*');

        $query->where('material_requests.user_id', Auth::id());

        if ($term = $this->searchTerm) {
            if ($id = $this->getIdFromSearchTerm()) {
                $query->where('material_requests.id', $id);
            } else {
                $query->where(function (Builder $query) use ($term): void {
                    $query
                        ->where('cost_centers.name', 'like', "%$term%")
                        ->orWhere('material_requests.concept', 'like', "%$term%");
                });
            }
        }

        $query->when($filtersBag->filled('type'),
            fn () => $query->where('material_requests.type_id', $filtersBag->string('type'))
        )
            ->when($filtersBag->filled('status'),
                fn () => $query->where('material_requests.status', $filtersBag->string('status'))
            )
            ->when($filtersBag->filled('minTotalSpent'),
                fn () => $query->where('material_requests.total_spent', '>=', $filtersBag->float('minTotalSpent'))
            )
            ->when($filtersBag->filled('maxTotalSpent'),
                fn () => $query->where('material_requests.total_spent', '<=', $filtersBag->float('maxTotalSpent'))
            )
            ->when($filtersBag->filled('minDate'),
                fn () => $query->where('material_requests.created_at', '>=', $filtersBag->string('minDate'))
            )
            ->when($filtersBag->filled('maxDate'),
                fn () => $query->where('material_requests.created_at', '<=', $filtersBag->string('maxDate'))
            );

        if ($this->sortColumn === 'status') {
            $cases = collect(MaterialRequestStatus::cases())
                ->map(fn (MaterialRequestStatus $case) => "WHEN '{$case->value}' THEN '{$case->label()}'")
                ->implode(' ');

            $query->orderByRaw("CASE material_requests.status $cases END {$this->sortDirection}");

            return $query;
        }

        $sortable = [
            'created_at'  => 'material_requests.created_at',
            'id'          => 'material_requests.id',
            'cost_center' => 'cost_centers.name',
            'total_spent' => 'material_requests.total_spent',
            'type'        => 'types.name',
        ];

        $column = $sortable[$this->sortColumn] ?? 'material_requests.created_at';

        $query->orderBy($column, $this->sortDirection);

        return $query;
    }

    public function deleteMaterialRequest(int $id): void
    {
        $materialRequest = MaterialRequest::findOrFail($id);

        if (! $materialRequest->canDelete()) {
            $this->toastError('No se puede eliminar: la solicitud no esta en pendiente');

            return;
        }

        $materialRequest->delete();
        $this->toastSuccess('Solicitud eliminada correctamente.');
    }

    private function getIdFromSearchTerm(): ?int
    {
        if (str_contains($this->searchTerm, ':id=')) {
            $data = explode('=', $this->searchTerm);
            if (! empty($data[1])) {
                return (int) $data[1];
            }
        }

        return null;
    }
}
