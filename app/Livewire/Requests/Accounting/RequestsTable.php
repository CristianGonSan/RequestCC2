<?php

namespace App\Livewire\Requests\Accounting;

use App\Enums\Requests\RequestStatus;
use App\Exports\ExportRequests;
use App\Models\RequestModel;
use App\Models\Catalogs\Type;
use App\Support\DataBag;
use App\Traits\Livewire\RequestModel\HasRequestModelTable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Session;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RequestsTable extends Component
{
    use HasRequestModelTable;

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
        'type'      => null,
        'status'    => null,
        'payMethod' => 1,
        'minAmount' => null,
        'maxAmount' => null,
        'minDate'   => null,
        'maxDate'   => null,
    ];

    private const EXCLUDED = [
        RequestStatus::Pending, RequestStatus::Rejected
    ];

    public function mount(): void
    {
        $this->setPage($this->page);
    }

    public function render(): View
    {
        $requests = $this->getQuery()->paginate($this->perPage);

        return view('livewire.requests.accounting.requests-table', [
            'requests'      => $requests,
            'statusOptions' => RequestStatus::exclude(self::EXCLUDED),
            'typeOptions'   => Type::options(),
        ]);
    }

    public function export(): ?BinaryFileResponse
    {
        $items = $this->getQuery()->paginate($this->perPage)->items();

        if (empty($items)) {
            $this->toastWarning('No hay nada para exportar');
            return null;
        }

        $results = collect($items);
        $export = new ExportRequests($results);

        return Excel::download($export, 'Solicitudes.xlsx');
    }

    private function getQuery(): Builder
    {
        $query = RequestModel::query();

        $filtersBag = DataBag::make($this->filters);

        $query->with([
            'user:id,name',
            'costCenter:id,name',
            'type:id,name',
        ]);

        $query->join('cost_centers', 'requests.cost_center_id', '=', 'cost_centers.id')
            ->join('users', 'requests.user_id', '=', 'users.id')
            ->join('types', 'requests.type_id', '=', 'types.id')
            ->select('requests.*');

        $query->whereNotIn('requests.status', self::EXCLUDED);

        if ($term = $this->searchTerm) {
            if ($id = $this->getIdFromSearchTerm()) {
                $query->where('requests.id', $id);
            } else {
                $query->where(function (Builder $query) use ($term): void {
                    $query
                        ->where('users.name', 'like', "%$term%")
                        ->orWhere('requests.payee', 'like', "%$term%")
                        ->orWhere('cost_centers.name', 'like', "%$term%")
                        ->orWhere('requests.concept', 'like', "%$term%");
                });
            }
        }

        $query->when($filtersBag->filled('payMethod'),
                fn () => $query->where('requests.is_transfer', true)
            )
            ->when($filtersBag->filled('type'),
                fn () => $query->where('requests.type_id', $filtersBag->string('type'))
            )
            ->when($filtersBag->filled('status'),
                fn () => $query->where('requests.status', $filtersBag->string('status'))
            )
            ->when($filtersBag->filled('minAmount'),
                fn () => $query->where('requests.amount', '>=', $filtersBag->float('minAmount'))
            )
            ->when($filtersBag->filled('maxAmount'),
                fn () => $query->where('requests.amount', '<=', $filtersBag->float('maxAmount'))
            )
            ->when($filtersBag->filled('minDate'),
                fn () => $query->where('requests.created_at', '>=', $filtersBag->string('minDate'))
            )
            ->when($filtersBag->filled('maxDate'),
                fn () => $query->where('requests.created_at', '<=', $filtersBag->string('maxDate'))
            );

        if ($this->sortColumn === 'status') {
            $cases = collect(RequestStatus::cases())
                ->map(fn (RequestStatus $case) => "WHEN '{$case->value}' THEN '{$case->label()}'")
                ->implode(' ');

            $query->orderByRaw("CASE requests.status $cases END {$this->sortDirection}");

            return $query;
        }

        $sortable = [
            'created_at'    => 'requests.created_at',
            'id'            => 'requests.id',
            'payee'         => 'requests.payee',
            'cost_center'   => 'cost_centers.name',
            'users'         => 'user.name',
            'amount'        => 'requests.amount',
            'type'          => 'types.name'
        ];

        $column = $sortable[$this->sortColumn] ?? 'requests.created_at';

        $query->orderBy($column, $this->sortDirection);

        return $query;
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
