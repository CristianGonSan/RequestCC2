<?php

namespace App\Livewire\Requests\Accounting;

use App\Http\Controllers\MailManager;
use App\Models\RequestModel;
use App\Models\RequestRecords;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class RequestDetails extends Component
{
    public $requestModel;

    public function mount($requestModel): void
    {
        $this->requestModel = $requestModel;
    }

    public function render(): Application|Factory|View
    {
        return view('livewire.requests.accounting.request-details');
    }

    #[On('updateStatus')]
    public function updateStatus(string $status): void
    {
        $request = $this->requestModel;

        if ($request->isCancelled()) {
            abort(403, 'Request is cancelled. Status update is not allowed.');
        }

        $isInvalidStatus = in_array(
            $status,
            [
                RequestModel::STATUS_ACCEPTED,
                RequestModel::STATUS_REJECTED,
                RequestModel::STATUS_PENDING
            ]
        );

        $isInvalidTransfer = !$request->is_transfer || $isInvalidStatus;

        if ($isInvalidTransfer) {
            abort(403, 'Unauthorized action for this type of request.');
        }

        $oldStatus = $request->getStatusText();
        $request->status = $status;
        $newStatus = $request->getStatusText();
        $request->save();

        RequestRecords::changeStatus(Auth::user(), $request->id, $oldStatus, $newStatus);

        if ($request->is_transfer) {
            MailManager::sendStatusChangeNotification($request);
        }

        $this->dispatch('refreshRecords');
        $this->dispatch('showFeedback');
    }
}
