<?php

namespace App\Livewire\Requests\Accounting;

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
        return view('livewire.requests.accounting.request-show', [
            'requestModel' => $this->requestModel(),
        ]);
    }

    public function markAsPaid(): void
    {
        $this->transitionStatus(RequestStatus::Paid);
    }

    public function cancelRequest(): void
    {
        $this->transitionStatus(RequestStatus::Cancelled);
    }

    private function transitionStatus(RequestStatus $status): void
    {
        $requestModel = $this->requestModel();

        if (!$requestModel->is_transfer || $requestModel->status->isCancelled()) {
            $this->toastError('Acción no permitida.');

            return;
        }

        $requestModel->changeStatusWithRecord($status);

        MailManager::sendStatusChangeNotification($requestModel);

        $this->toastSuccess("Solicitud marcada como {$status->label()}.");
    }

    private ?RequestModel $requestModel = null;

    private function requestModel(): RequestModel
    {
        return $this->requestModel ??= RequestModel::with(['costCenter:id,name', 'type:id,name'])->findOrFail($this->requestModelId);
    }
}
