<?php

namespace App\Livewire\MaterialRequests\Fulfillment;

use App\Enums\Requests\MaterialRequestStatus;
use App\Models\MaterialRequests\MaterialRequest;
use App\Models\MaterialRequests\MaterialRequestFulfillment;
use App\Models\MaterialRequests\MaterialRequestItem;
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

    public ?int $fulfillingItemId = null;

    public ?string $fulfillQuantity = null;

    public ?string $fulfillCost = null;

    public function mount(int $materialRequestId): void
    {
        $this->materialRequestId = $materialRequestId;
    }

    public function render(): View
    {
        return view('livewire.material-requests.fulfillment.material-request-show', [
            'materialRequest' => $this->materialRequest(),
            'fulfillingItem'  => $this->fulfillingItem(),
        ]);
    }

    public function markAsInProcess(): void
    {
        $this->transitionStatus(MaterialRequestStatus::InProcess);
    }

    public function markAsCompleted(): void
    {
        $this->transitionStatus(MaterialRequestStatus::Completed);
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

    public function fulfillItem(int $itemId): void
    {
        $this->fulfillingItemId = $itemId;
        $this->reset(['fulfillQuantity', 'fulfillCost']);
        $this->resetValidation();
        $this->dispatch('showModalFulfillItem');
    }

    public function closeFulfillModal(): void
    {
        $this->reset(['fulfillingItemId', 'fulfillQuantity', 'fulfillCost']);
        $this->resetValidation();
        $this->dispatch('hideModalFulfillItem');
    }

    public function submitFulfillment(): void
    {
        $item = $this->fulfillingItem();

        if ($item === null) {
            $this->toastError('Ítem no encontrado.');

            return;
        }

        $this->validate([
            'fulfillQuantity' => ['required', 'numeric', 'min:0', "max:{$item->remaining_quantity}"],
            'fulfillCost'     => ['required', 'numeric', 'gt:0'],
        ]);

        MaterialRequestFulfillment::create([
            'material_request_id'      => $item->material_request_id,
            'material_request_item_id' => $item->id,
            'user_id'                  => auth()->id(),
            'quantity'                 => $this->fulfillQuantity,
            'cost'                     => $this->fulfillCost,
        ]);

        $this->materialRequest = null;
        $this->reset(['fulfillingItemId', 'fulfillQuantity', 'fulfillCost']);
        $this->toastSuccess('Material suplido correctamente.');
        $this->closeFulfillModal();
    }

    public function deleteFulfillment(int $id): void
    {
        $fulfillment = MaterialRequestFulfillment::findOrFail($id);

        if (! $fulfillment->isCurrentUser()) {
            $this->toastError('Acción no autorizada.');

            return;
        }

        $fulfillment->delete();

        $this->toastSuccess('Envío eliminado.');
    }

    public function fulfillingItem(): ?MaterialRequestItem
    {
        if ($this->fulfillingItemId === null) {
            return null;
        }

        return $this->materialRequest()->items()->findOrFail($this->fulfillingItemId);
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
