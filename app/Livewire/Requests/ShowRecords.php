<?php

namespace App\Livewire\Requests;

use App\Models\RequestModel;
use Livewire\Component;
use Livewire\WithPagination;

class ShowRecords extends Component
{
    use WithPagination;

    public $requestModelId;

    public function mount(int $requestModelId): void
    {
        $this->requestModelId = $requestModelId;
    }

    public function render()
    {
        $records = $this->requestModel()->records()
            ->orderByDesc('registered_at')
            ->with('user')->paginate();

        return view('livewire.requests.show-records', [
            'records' => $records,
        ]);
    }

    private ?RequestModel $requestModel = null;

    private function requestModel(): RequestModel
    {
        return $this->requestModel ??= RequestModel::findOrFail($this->requestModelId);
    }
}
