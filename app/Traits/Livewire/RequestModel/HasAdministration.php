<?php

namespace App\Traits\Livewire\RequestModel;

use App\Enums\Requests\RequestStatus;
use App\Services\Mails\MailManager;
use App\Models\RequestModel;

trait HasAdministration
{
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
}
