<?php

namespace App\Livewire\Requests;

use App\Services\Mails\MailManager;
use App\Models\RequestModel;
use Auth;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ShowMessages extends Component
{
    #[Locked]
    public int $requestModelId;

    public string $newMessage;

    public function mount(int $requestModelId): void
    {
        $this->requestModelId = $requestModelId;
    }

    public function render(): View
    {
        $messages = $this->requestModel()->messages()
            ->with('user:id,name')
            ->get();

        return view('livewire.requests.show-messages', [
            'messages' => $messages,
        ]);
    }

    public function sendMessage(): void
    {
        $requestModel = $this->requestModel();

        $validated = $this->validate([
            'newMessage' => ['required', 'string', 'max:255'],
        ]);

        $newMessage = $requestModel->messages()->create([
            'user_id' => Auth::id(),
            'message' => $validated['newMessage'],
        ]);

        if (Auth::id() != $requestModel->user_id) {
            MailManager::sendNewMessageNotification($newMessage);
        }

        $this->reset('newMessage');
    }

    private ?RequestModel $requestModel = null;

    private function requestModel(): RequestModel
    {
        return $this->requestModel ??= RequestModel::findOrFail($this->requestModelId);
    }
}
