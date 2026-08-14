<?php

namespace App\Livewire\Requests\Management;

use App\Enums\Requests\RequestStatus;
use App\Services\Mails\MailManager;
use App\Models\RequestModel;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class RequestShow extends Component
{
    use FlashToast, Toast;

    #[Locked]
    public int $requestModelId;

    public function mount(int $requestModelId): void
    {
        $this->requestModelId = $requestModelId;
    }

    public function render(): View
    {
        return view('livewire.requests.management.request-show', [
            'requestModel' => $this->requestModel(),
        ]);
    }

    public function acceptRequest(): void
    {
        $this->transitionStatus(RequestStatus::Accepted);
    }

    public function rejectRequest(): void
    {
        $this->transitionStatus(RequestStatus::Rejected);
    }

    public function markAsPending(): void
    {
        $this->transitionStatus(RequestStatus::Pending);
    }

    public function markAsPaid(): void
    {
        $this->transitionStatus(RequestStatus::Paid, false);
    }

    public function cancelRequest(): void
    {
        $this->transitionStatus(RequestStatus::Cancelled, false);
    }

    private function transitionStatus(RequestStatus $status, bool $allowTransfer = true): void
    {
        $requestModel = $this->requestModel();

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

    private ?RequestModel $requestModel = null;

    private function requestModel(): RequestModel
    {
        return $this->requestModel ??= RequestModel::with(['costCenter:id,name', 'type:id,name'])->findOrFail($this->requestModelId);
    }
}
