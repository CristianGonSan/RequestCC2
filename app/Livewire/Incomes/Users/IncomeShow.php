<?php

namespace App\Livewire\Incomes\Users;

use App\Models\Incomes\Income;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
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
            'income' => $this->income(),
        ]);
    }

    public function delete(): void
    {
        $income = $this->income();

        $income->delete();
        $this->flashToastSuccess('Ingreso eliminado');
        redirect()->route('incomes.index');
    }

    private ?Income $income = null;

    private function income(): Income
    {
        return $this->income ??= Income::with([
            'company:id,name', 'type:id,name',
        ])->findOrFail($this->incomeId);
    }
}
