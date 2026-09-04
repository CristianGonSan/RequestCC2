<?php

namespace App\Livewire\MoneyRequests\Management;

use App\Enums\Requests\MoneyRequestStatus;
use App\Services\Mails\MailManager;
use App\Models\MoneyRequests\MoneyRequest;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class RequestShow extends Component
{
    use FlashToast, Toast;

    #[Locked]
    public int $moneyRequestId;

    public function mount(int $moneyRequestId): void
    {
        $this->moneyRequestId = $moneyRequestId;
    }

    public function render(): View
    {
        return view('livewire.money-requests.management.request-show', [
            'moneyRequest' => $this->moneyRequest(),
        ]);
    }

    public function acceptRequest(): void
    {
        $this->transitionStatus(MoneyRequestStatus::Accepted);
    }

    public function rejectRequest(): void
    {
        $this->transitionStatus(MoneyRequestStatus::Rejected);
    }

    public function markAsPending(): void
    {
        $this->transitionStatus(MoneyRequestStatus::Pending);
    }

    public function markAsPaid(): void
    {
        $this->transitionStatus(MoneyRequestStatus::Paid, false);
    }

    public function cancelRequest(): void
    {
        $this->transitionStatus(MoneyRequestStatus::Cancelled, false);
    }

    private function transitionStatus(MoneyRequestStatus $status, bool $allowTransfer = true): void
    {
        $moneyRequest = $this->moneyRequest();

        if ($moneyRequest->status->cannotChangeTo($status)) {
            $this->toastError('Acción no permitida.');

            return;
        }

        if ($moneyRequest->is_transfer && ! $allowTransfer) {
            $this->toastError('Acción no permitida para solicitudes de transferencia.');

            return;
        }

        $moneyRequest->changeStatusWithRecord($status);

        if ($moneyRequest->is_transfer) {
            MailManager::sendStatusChangeNotification($moneyRequest);
        }

        $this->toastSuccess("Solicitud marcada como {$status->label()}.");
    }

    private ?MoneyRequest $moneyRequest = null;

    private function moneyRequest(): MoneyRequest
    {
        return $this->moneyRequest ??= MoneyRequest::with(['costCenter:id,name', 'type:id,name'])->findOrFail($this->moneyRequestId);
    }
}
