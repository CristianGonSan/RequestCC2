<?php

namespace App\Livewire\Requests\Users;

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
        return view('livewire.requests.users.request-show', [
            'requestModel' => $this->requestModel(),
        ]);
    }

    public function delete(): void
    {
        $requestModel = $this->requestModel();

        if (! $requestModel->canDelete()) {
            $this->toastError('No se puede eliminar: la solicitud no esta en pendiente');

            return;
        }

        $requestModel->delete();
        $this->flashToastSuccess('Solicitud eliminada');
        redirect()->route('requests.index');
    }

    private ?RequestModel $requestModel = null;

    private function requestModel(): RequestModel
    {
        return $this->requestModel ??= RequestModel::with([
            'costCenter:id,name', 'type:id,name',
        ])->findOrFail($this->requestModelId);
    }
}
