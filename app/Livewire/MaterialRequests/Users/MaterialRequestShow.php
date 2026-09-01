<?php

namespace App\Livewire\MaterialRequests\Users;

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
        return view('livewire.material-requests.users.material-request-show', [
            'materialRequest' => $this->materialRequest(),
        ]);
    }

    public function deleteMaterialRequest(): void
    {
        $materialRequest = $this->materialRequest();

        if (! $materialRequest->canDelete()) {
            $this->toastError('Acción no autorizada');

            return;
        }

        $materialRequest->delete();
        $this->flashToastSuccess('Solicitud eliminada');
        redirect()->route('material-requests.index');
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
