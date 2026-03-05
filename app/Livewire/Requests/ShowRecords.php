<?php

namespace App\Livewire\Requests;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class ShowRecords extends Component
{
    use WithPagination;

    public $requestModel;

    public function mount($requestModel): void
    {
        $this->requestModel = $requestModel;
    }

    public function render()
    {
        $records = $this->requestModel->records()->orderByDesc('registered_at')->with('user')->paginate();

        return view('livewire.requests.show-records', [
            'records' => $records
        ]);
    }

    #[On('refreshRecords')]
    public function refreshRecords() {
        $this->resetPage();
    }
}
