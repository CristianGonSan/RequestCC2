<?php

namespace App\Livewire\Common;

use App\Contracts\Common\HasMessages;
use Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Locked;
use Livewire\Component;
use LogicException;

class ShowMessages extends Component
{
    #[Locked]
    public string $modelClass;

    #[Locked]
    public int $modelId;

    public string $newMessage;

    public function mount(string $modelClass, int $modelId): void
    {
        $this->modelClass = $modelClass;
        $this->modelId    = $modelId;
    }

    public function render(): View
    {
        $messages = $this->messageableModel()->messages()
            ->with('user:id,name')
            ->get();

        return view('livewire.common.show-messages', [
            'messages' => $messages,
        ]);
    }

    public function sendMessage(): void
    {
        $messageable = $this->messageableModel();

        $validated = $this->validate([
            'newMessage' => ['required', 'string', 'max:255'],
        ]);

        $newMessage = $messageable->messages()->create([
            'user_id' => Auth::id(),
            'message' => $validated['newMessage'],
        ]);

        $ownerId = $messageable->getAttribute('user_id');

        if ($ownerId !== null && method_exists($messageable, 'user') && Auth::id() != $ownerId) {
            $newMessage->sendEmail();
        }

        $this->reset('newMessage');
    }

    private function messageableModel(): Model
    {
        if (! is_subclass_of($this->modelClass, Model::class)) {
            throw new LogicException("The [{$this->modelClass}] class must be an Eloquent model.");
        }

        $modelClass = $this->modelClass;
        $model      = $modelClass::findOrFail($this->modelId);

        if (! $model instanceof HasMessages) {
            throw new LogicException("The [{$this->modelClass}] model must implement HasMessages.");
        }

        return $model;
    }
}
