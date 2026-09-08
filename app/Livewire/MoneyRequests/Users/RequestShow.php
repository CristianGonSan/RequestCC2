<?php

namespace App\Livewire\MoneyRequests\Users;

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
        return view('livewire.money-requests.users.request-show', [
            'moneyRequest' => $this->moneyRequest(),
        ]);
    }

    public function delete(): void
    {
        $moneyRequest = $this->moneyRequest();

        if (! $moneyRequest->canDelete()) {
            $this->toastError('No se puede eliminar: la solicitud no esta en pendiente');

            return;
        }

        $moneyRequest->delete();
        $this->flashToastSuccess('Solicitud eliminada');
        redirect()->route('money-requests.index');
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
