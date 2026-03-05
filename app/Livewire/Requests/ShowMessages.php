<?php

namespace App\Livewire\Requests;

use App\Http\Controllers\MailManager;
use App\Models\Message;
use Auth;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Component;

class ShowMessages extends Component
{
    public $requestModel;
    public $newMessage;

    public $messages;

    public function mount($requestModel): void
    {
        $this->requestModel = $requestModel;
    }

    public function render(): View|Factory|Application
    {
        $this->loadMessages();
        return view('livewire.requests.show-messages');
    }

    public function loadMessages()
    {
        $this->messages = $this->requestModel->messages()
            ->with('user')
            ->get();
    }

    public function sendMessage(): void
    {
        $messageModel = Message::create([
            'request_id' => $this->requestModel->id,
            'user_id' => Auth::id(),
            'message' => $this->newMessage,
        ]);

        if (Auth::id() != $this->requestModel->user->id) {
            MailManager::sendNewMessageNotification($messageModel);
        }

        $this->newMessage = '';
    }
}

