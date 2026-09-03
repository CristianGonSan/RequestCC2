<?php

namespace App\Livewire\MoneyRequests;

use App\Services\Mails\MailManager;
use App\Models\MoneyRequests\MoneyRequest;
use Auth;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ShowMessages extends Component
{
    #[Locked]
    public int $moneyRequestId;

    public string $newMessage;

    public function mount(int $moneyRequestId): void
    {
        $this->moneyRequestId = $moneyRequestId;
    }

    public function render(): View
    {
        $messages = $this->moneyRequest()->messages()
            ->with('user:id,name')
            ->get();

        return view('livewire.money-requests.show-messages', [
            'messages' => $messages,
        ]);
    }

    public function sendMessage(): void
    {
        $moneyRequest = $this->moneyRequest();

        $validated = $this->validate([
            'newMessage' => ['required', 'string', 'max:255'],
        ]);

        $newMessage = $moneyRequest->messages()->create([
            'user_id' => Auth::id(),
            'message' => $validated['newMessage'],
        ]);

        if (Auth::id() != $moneyRequest->user_id) {
            MailManager::sendNewMessageNotification($newMessage);
        }

        $this->reset('newMessage');
    }

    private ?MoneyRequest $moneyRequest = null;

    private function moneyRequest(): MoneyRequest
    {
        return $this->moneyRequest ??= MoneyRequest::findOrFail($this->moneyRequestId);
    }
}
