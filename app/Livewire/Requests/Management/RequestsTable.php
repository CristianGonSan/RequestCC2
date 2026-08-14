<?php

namespace App\Livewire\Requests\Management;

use App\Enums\Requests\RequestStatus;
use App\Exports\ExportRequests;
use App\Services\Mails\MailManager;
use App\Models\RequestModel;
use App\Models\Type;
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

        return view('livewire.requests.management.requests-table', [
            'requests'      => $requests,
            'statusOptions' => RequestStatus::options(),
            'typeOptions'   => Type::options()
        ]);
    }

    public function acceptRequest(int $id): void
    {
        $this->transitionStatus($id, RequestStatus::Accepted);
    }

    public function rejectRequest(int $id): void
    {
        $this->transitionStatus($id, RequestStatus::Rejected);
    }

    public function markAsPending(int $id): void
    {
        $this->transitionStatus($id, RequestStatus::Pending);
    }

    public function markAsPaid(int $id): void
    {
        $this->transitionStatus($id, RequestStatus::Paid, false);
    }

    public function cancelRequest(int $id): void
    {
        $this->transitionStatus($id, RequestStatus::Cancelled, false);
    }

    private function transitionStatus(int $id, RequestStatus $status, bool $allowTransfer = true): void
    {
        $requestModel = RequestModel::findOrFail($id);

        if ($requestModel->status->isCancelled()) {
            $this->toastError('Acción no permitida.');

            return;
        }

        if ($requestModel->is_transfer && ! $allowTransfer) {
            $this->toastError('Acción no permitida para solicitudes de transferencia.');

            return;
        }

        $requestModel->changeStatusWithRecord($status);

        if ($requestModel->is_transfer) {
            MailManager::sendStatusChangeNotification($requestModel);
        }

        $this->toastSuccess("Solicitud marcada como {$status->label()}.");
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
            'users'         => 'user.name',
            'amount'        => 'requests.amount',
            'type'          => 'types.name'
        ];

        $column = $sortable[$this->sortColumn] ?? 'requests.created_at';

        $query->orderBy($column, $this->sortDirection);

        return $query;
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
