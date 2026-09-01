<?php

namespace App\Livewire\MaterialRequests\Management;

use App\Enums\Requests\MaterialRequestStatus;
use App\Models\MaterialRequests\MaterialRequest;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class MaterialRequestShow extends Component
{
    use FlashToast, Toast;

    #[Locked]
    public int $materialRequestId;

    public function mount(int $materialRequestId): void
    {
        $this->materialRequestId = $materialRequestId;
    }

    public function render(): View
    {
        return view('livewire.material-requests.management.material-request-show', [
            'materialRequest' => $this->materialRequest(),
        ]);
    }

    public function acceptRequest(): void
    {
        $this->transitionStatus(MaterialRequestStatus::Accepted);
    }

    public function rejectRequest(): void
    {
        $this->transitionStatus(MaterialRequestStatus::Rejected);
    }

    public function markAsPending(): void
    {
        $this->transitionStatus(MaterialRequestStatus::Pending);
    }

    public function markAsInProcess(): void
    {
        $this->transitionStatus(MaterialRequestStatus::InProcess);
    }

    public function cancelRequest(): void
    {
        $this->transitionStatus(MaterialRequestStatus::Cancelled);
    }

    private function transitionStatus(MaterialRequestStatus $status): void
    {
        $materialRequest = $this->materialRequest();

        if ($materialRequest->status->isCancelled()) {
            $this->toastError('Acción no permitida.');

            return;
        }

        $materialRequest->status = $status;
        $materialRequest->save();

        $this->toastSuccess("Solicitud marcada como {$status->label()}.");
    }

    private ?MaterialRequest $materialRequest = null;

    private function materialRequest(): MaterialRequest
    {
        return $this->materialRequest ??= MaterialRequest::with([
            'costCenter:id,name',
            'type:id,name',
            'user:id,name',
            'items.material',
            'items.unit',
            'fulfillments.materialRequestItem.material:id,name',
            'fulfillments.user:id,name',
        ])->findOrFail($this->materialRequestId);
    }
}
