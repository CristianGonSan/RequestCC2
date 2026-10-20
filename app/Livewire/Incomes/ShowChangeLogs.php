<?php

namespace App\Livewire\Incomes;

use App\Models\Incomes\Income;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

class ShowChangeLogs extends Component
{
    use WithPagination;

    #[Locked]
    public int $incomeId;

    public function mount(int $incomeId): void
    {
        $this->incomeId = $incomeId;
    }

    public function render(): View
    {
        $income     = Income::query()->findOrFail($this->incomeId);
        $changeLogs = $income->changeLogs()
            ->with('user:id,name,email')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate();

        return view('livewire.incomes.show-change-logs', [
            'changeLogs' => $changeLogs,
        ]);
    }

}
