<?php

namespace App\Livewire\Incomes\Users;

use App\Models\Incomes\Income;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class IncomeShow extends Component
{
    use FlashToast, Toast;

    #[Locked]
    public int $incomeId;

    public function mount(int $incomeId): void
    {
        $this->incomeId = $incomeId;
    }

    public function render(): View
    {
        return view('livewire.incomes.users.income-show', [
            'income'       => $this->income(),
            'hasFavorited' => Auth::user()->hasFavorited($this->income()),
        ]);
    }

    public function delete(): void
    {
        $income = $this->income();

        $income->delete();
        $this->flashToastSuccess('Ingreso eliminado');
        redirect()->route('incomes.index');
    }

    public function toggleFavorite(): void
    {
        $model = $this->income();
        $user  = Auth::user();

        $wasFavorited = $user->hasFavorited($model);

        $user->toggleFavorite($model);

        $this->toastSuccess($wasFavorited ? 'Favorito quitado' : 'Favorito añadido');
    }

    private ?Income $income = null;

    private function income(): Income
    {
        return $this->income ??= Income::with([
            'costCenter', 'type',
        ])->findOrFail($this->incomeId);
    }
}
