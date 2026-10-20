<?php

namespace App\Livewire\Common;

use App\Contracts\Common\HasMessages;
use App\Jobs\SendEmail;
use App\Mail\Common\NewMessageNotification;
use App\Models\User;
use Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use LogicException;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ShowMessages extends Component
{
    #[Locked]
    public string $modelClass;

    #[Locked]
    public int $modelId;

    #[Locked]
    public ?string $buttonUrl = null;

    public string $newMessage;

    public function mount(string $modelClass, int $modelId, ?string $buttonUrl = null): void
    {
        $this->modelClass = $modelClass;
        $this->modelId    = $modelId;
        $this->buttonUrl  = $buttonUrl;
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
            $owner  = $messageable->getRelationValue('user');
            $sender = Auth::user();

            if (! $owner instanceof User) {
                throw new LogicException('The messageable model must have a user relation.');
            }

            if (! $sender instanceof User) {
                throw new LogicException('The message must have an authenticated user.');
            }

            $createdAt = $newMessage->created_at;

            if ($createdAt === null) {
                throw new LogicException('Cannot send a message notification before the message is persisted.');
            }

            SendEmail::dispatch(
                [$owner->email],
                new NewMessageNotification(
                    user: "{$sender->name} ({$sender->email})",
                    dateTime: $createdAt->format('d/m/Y h:i:s a'),
                    message: $newMessage->message,
                    buttonUrl: $this->buttonUrl,
                ),
            );
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
