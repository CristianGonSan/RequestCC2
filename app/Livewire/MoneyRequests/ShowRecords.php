<?php

namespace App\Livewire\MoneyRequests;

use App\Models\MoneyRequests\MoneyRequest;
use Livewire\Component;
use Livewire\WithPagination;

class ShowRecords extends Component
{
    use WithPagination;

    public $moneyRequestId;

    public function mount(int $moneyRequestId): void
    {
        $this->moneyRequestId = $moneyRequestId;
    }

    public function render()
    {
        $records = $this->moneyRequest()->records()
            ->orderByDesc('registered_at')
            ->with('user')->paginate();

        return view('livewire.money-requests.show-records', [
            'records' => $records,
        ]);
    }

    private ?MoneyRequest $moneyRequest = null;

    private function moneyRequest(): MoneyRequest
    {
        return $this->moneyRequest ??= MoneyRequest::findOrFail($this->moneyRequestId);
    }
}
