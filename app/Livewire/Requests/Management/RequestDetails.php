<?php

namespace App\Livewire\Requests\Management;

use App\Http\Controllers\MailManager;
use App\Models\RequestModel;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\Attributes\On;

class RequestDetails extends Component
{
    /** @var \App\Models\RequestModel $requestModel */
    public $requestModel;

    public function mount(RequestModel $requestModel): void
    {
        $this->requestModel = $requestModel;
    }

    public function render(): View
    {
        return view('livewire.requests.management.request-details');
    }

    #[On('updateStatus')]
    public function updateStatus(string $status): void
    {
        /** @var \App\Models\RequestModel $requestModel */
        $requestModel = $this->requestModel;

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
}
