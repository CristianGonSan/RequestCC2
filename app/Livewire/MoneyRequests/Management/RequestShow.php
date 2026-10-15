<?php

namespace App\Livewire\MoneyRequests\Management;

use App\Enums\Requests\MoneyRequestStatus;
use App\Services\Mails\MailManager;
use App\Models\MoneyRequests\MoneyRequest;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Support\Facades\Auth;
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
            'hasFavorited' => Auth::user()->hasFavorited($this->moneyRequest()),
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

    public function toggleFavorite(): void
    {
        $model = $this->moneyRequest();
        $user  = Auth::user();

        $wasFavorited = $user->hasFavorited($model);

        $user->toggleFavorite($model);

        $this->toastSuccess($wasFavorited ? 'Favorito quitado' : 'Favorito añadido');
    }

    private ?MoneyRequest $moneyRequest = null;

    private function moneyRequest(): MoneyRequest
    {
        return $this->moneyRequest ??= MoneyRequest::with([
            'user:id,name,email',
            'costCenter:id,name,description,company_id',
            'costCenter.company:id,name',
            'type:id,name',
        ])->findOrFail($this->moneyRequestId);
    }
}
