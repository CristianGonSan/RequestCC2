<?php

namespace App\Livewire\Requests\Users;

use App\Enums\Requests\RequestStatus;
use App\Exports\ExportRequests;
use App\Models\RequestModel;
use App\Models\Catalogs\Type;
use App\Support\DataBag;
use App\Traits\Livewire\RequestModel\HasRequestModelTable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
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
        'payMethod' => null,
        'minAmount' => null,
        'maxAmount' => null,
        'minDate'   => null,
        'maxDate'   => null,
    ];

    public function mount(): void
    {
        $this->setPage($this->page);
    }

    public function render(): View
    {
        $requests = $this->getQuery()->paginate($this->perPage);

        return view('livewire.requests.users.requests-table', [
            'requests'      => $requests,
            'statusOptions' => RequestStatus::options(),
            'typeOptions'   => Type::options()
        ]);
    }

    private function getQuery(): Builder
    {
        $query      = RequestModel::query();
        $filtersBag = DataBag::make($this->filters);

        $query->with([
            'costCenter:id,name',
            'type:id,name',
        ]);

        $query->join('cost_centers', 'requests.cost_center_id', '=', 'cost_centers.id')
            ->join('types', 'requests.type_id', '=', 'types.id')
            ->select('requests.*');

        $query->where('requests.user_id', Auth::id());

        if ($term = $this->searchTerm) {
            if ($id = $this->getIdFromSearchTerm()) {
                $query->where('requests.id', $id);
            } else {
                $query->where(function (Builder $query) use ($term): void {
                    $query
                        ->where('cost_centers.name', 'like', "%$term%")
                        ->orWhere('requests.payee', 'like', "%$term%")
                        ->orWhere('requests.concept', 'like', "%$term%");
                });
            }
        }

        $query->when($filtersBag->filled('payMethod'),
                fn () => $query->where('requests.is_transfer', $filtersBag->boolean('payMethod'))
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
                ->map(fn(RequestStatus $case) => "WHEN '{$case->value}' THEN '{$case->label()}'")
                ->implode(' ');

            $query->orderByRaw("CASE requests.status $cases END {$this->sortDirection}");

            return $query;
        }

        $sortable = [
            'created_at'    => 'requests.created_at',
            'id'            => 'requests.id',
            'payee'         => 'requests.payee',
            'cost_center'   => 'cost_centers.name',
            'amount'        => 'requests.amount',
            'type'          => 'types.name'
        ];

        $column = $sortable[$this->sortColumn] ?? 'requests.created_at';

        $query->orderBy($column, $this->sortDirection);

        return $query;
    }

    public function deleteRequest(int $id): void
    {
        $requestModel = RequestModel::findOrFail($id);

        if (! $requestModel->canDelete()) {
            $this->toastError('No se puede eliminar: la solicitud no esta en pendiente');

            return;
        }

        $requestModel->delete();
        $this->toastSuccess('Solicitud eliminada correctamente.');
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
