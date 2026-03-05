<?php

namespace App\Livewire\Requests\Management;

use App\Exports\ExportRequests;
use App\Http\Controllers\MailManager;
use App\Models\RequestModel;
use App\Models\Type;
use App\Traits\LivewireTableFiltersHandle;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Maatwebsite\Excel\Facades\Excel;

class RequestsTable extends Component
{
    use WithPagination;
    use LivewireTableFiltersHandle;

    public array $typeOptions = [];
    public array $statusOptions = [];

    public function mount(): void
    {
        $this->loadFilters('managementRT', [
            'search' => null,
            'perPage' => 12,
            'orderBy' => 'created_at',
            'orderDirection' => 'desc',
            'type' => null,
            'status' => null,
            'payMethod' => -1,
            'minAmount' => null,
            'maxAmount' => null,
            'minDate' => null,
            'maxDate' => null,
        ]);

        $this->typeOptions = Type::options();
        $this->statusOptions = RequestModel::STATUSES_TEXT;
    }

    public function render(): View
    {
        $requests = $this->getQuery()->paginate($this->filters['perPage']);

        return view('livewire.requests.management.requests-table', [
            'requests' => $requests
        ]);
    }



    protected function getQuery(): Builder
    {
        $query = RequestModel::query();

        $query->orderBy(
            $this->filters['orderBy'],
            $this->filters['orderDirection']
        );

        if ($this->filters['payMethod'] > -1) {
            $query->where('is_transfer', $this->filters['payMethod']);
        }

        if ($this->filters['type']) {
            $query->where('type', $this->filters['type']);
        }

        if ($this->filters['status']) {
            $query->where('status', $this->filters['status']);
        }

        if ($this->filters['minAmount']) {
            $query->where('amount', '>=', $this->filters['minAmount']);
        }

        if ($this->filters['maxAmount']) {
            $query->where('amount', '<=', $this->filters['maxAmount']);
        }

        if ($this->filters['minDate']) {
            $query->whereDate('created_at', '>=', $this->filters['minDate']);
        }

        if ($this->filters['maxDate']) {
            $query->whereDate('created_at', '<=', $this->filters['maxDate']);
        }

        $query->with('user');
        $query->with('typeModel');

        $search = $this->filters['search'];

        if ($search) {
            if (str_contains($search, ":id=")) {
                $data = explode('=', $search);
                if (!empty($data[1])) {
                    $query->where('id', $data[1]);
                }
            } else {
                $query->where(function ($query) use ($search) {
                    $query->where('concept', 'like', '%' . $search . '%')
                        ->orWhere('cost_center', 'like', '%' . $search . '%')
                        ->orWhere('type', 'like', '%' . $search . '%')
                        ->orWhere('payee', 'like', '%' . $search . '%')
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->where('name', 'like', '%' . $search . '%');
                        });
                });
            }
        }

        return $query;
    }

    #[On('updateStatus')]
    public function updateStatus(int $id, string $status): void
    {
        /** @var \App\Models\RequestModel $requestModel */
        $requestModel = RequestModel::findOrFail($id);

        if ($requestModel->isCancelled()) {
            abort(403, 'Unauthorized action: request is already cancelled.');
        }

        $isTransferWithInvalidStatus = $requestModel->is_transfer && in_array(
            $status,
            [
                RequestModel::STATUS_PAID,
                RequestModel::STATUS_CANCELED
            ]
        );

        if ($isTransferWithInvalidStatus) {
            abort(403, 'Unauthorized action for transfer requests.');
        }

        $requestModel->changeStatusWithRecord($status);

        if ($requestModel->is_transfer) {
            MailManager::sendStatusChangeNotification($requestModel);
        }

        $this->dispatch('refreshRecords');
        $this->dispatch('showFeedback');
    }

    public function export()
    {
        $results = collect($this->getQuery()->paginate($this->filters['perPage'])->items());
        $export = new ExportRequests($results);
        return Excel::download($export, 'Solicitudes.xlsx');
    }
}
