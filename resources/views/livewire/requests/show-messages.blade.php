<div class="card mb-0" style="height: 70vh">
    @php
        $userId = Auth::id();
    @endphp
    <div class="card-header bg-dark d-flex">
        Mensajes
        <div wire:loading class="ml-auto">
            <i class="fas fa-spinner fa-spin"> </i>
        </div>
    </div>
    <div class="card-body">
        <div class="direct-chat-messages h-100">
            @forelse($messages as $message)
                @if ($message->user_id === $userId)
                    <div class="direct-chat-msg right" wire:key="message-{{ $message->id }}">
                        <div class="direct-chat-infos clearfix">
                            <span class="direct-chat-name float-right">{{ $message->user->name }}</span>
                            <span
                                class="direct-chat-timestamp float-left">{{ $message->created_at->format('d-m-Y h:i:s a') }}</span>
                        </div>
                        <img class="direct-chat-img" src="{{ asset('img/user.png') }}" alt="message user image">
                        <div class="direct-chat-text">
                            {{ $message->message }}
                        </div>
                    </div>
                @else
                    <div class="direct-chat-msg" wire:key="message-{{ $message->id }}">
                        <div class="direct-chat-infos clearfix">
                            <span class="direct-chat-name float-left">{{ $message->user->name }}</span>
                            <span
                                class="direct-chat-timestamp float-right">{{ $message->created_at->format('d-m-Y h:i:s a') }}</span>
                        </div>
                        <img class="direct-chat-img" src="{{ asset('img/user.png') }}" alt="message user image">
                        <div class="direct-chat-text">
                            {{ $message->message }}
                        </div>
                    </div>
                @endif
            @empty
                No hay mensajes
            @endforelse
        </div>
    </div>
    <div class="card-footer">
        <form wire:submit.prevent="sendMessage">
            <div class="input-group">
                <input wire:model.defer="newMessage" type="text" wire:model="newMessage"
                    placeholder="Ingrese Mensaje ..." class="form-control" required>
                <span class="input-group-append">
                    <button wire:loading.attr="disabled" type="submit" class="btn btn-outline-primary"><i
                            class="fas fa-paper-plane"></i></button>
                </span>
            </div>
        </form>
    </div>
</div>
