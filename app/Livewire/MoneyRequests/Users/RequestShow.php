<?php

namespace App\Livewire\MoneyRequests\Users;

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
        return view('livewire.money-requests.users.request-show', [
            'moneyRequest' => $this->moneyRequest(),
            'hasFavorited' => Auth::user()->hasFavorited($this->moneyRequest()),
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
