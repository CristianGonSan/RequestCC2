<div class="card mb-0" style="height: 70vh">
    @php
        $userId = Auth::id();
    @endphp
    <div class="card-header">
        <h2 class="card-title">Mensajes</h2>

        <div class="card-tools">
            <button wire:click="$refresh" class="btn btn-tool">
                <i wire:loading.class="fa-spin" class="fas fa-fw fa-arrows-rotate"></i>
            </button>
        </div>
    </div>

    <div class="card-body py-0 d-flex flex-column">
        <div class="flex-grow-1 overflow-auto pt-1" x-init="$el.scrollTop = $el.scrollHeight;
        new MutationObserver(() => {
            $el.scrollTop = $el.scrollHeight;
        }).observe($el, { childList: true });">

            @forelse ($messages as $message)
                @if ($message->user_id === $userId)
                    <div class="d-flex justify-content-end mb-2 mr-1" wire:key="message-{{ $message->id }}">
                        <div class="bg-success text-white rounded-lg p-2" style="max-width: 75%">
                            <div>{{ $message->message }}</div>
                            <small class="d-block text-right mt-1" style="opacity: 0.85">
                                {{ $message->created_at->format('d/m/Y h:i a') }}
                            </small>
                        </div>
                    </div>
                @else
                    <div class="d-flex justify-content-start mb-2" wire:key="message-{{ $message->id }}">
                        <div class="bg-light rounded-lg p-2" style="max-width: 75%">
                            <strong class="d-block text-primary">{{ $message->user->name }}</strong>
                            <div>{{ $message->message }}</div>
                            <small class="d-block text-right text-muted mt-1">
                                {{ $message->created_at->format('d/m/Y h:i a') }}
                            </small>
                        </div>
                    </div>
                @endif
            @empty
                <div class="d-flex flex-column align-items-center justify-content-center text-muted h-100 py-5">
                    <i class="fas fa-comment-alt fa-2x mb-2"></i>
                    No hay mensajes aún.
                </div>
            @endforelse
        </div>
    </div>
    <div class="card-footer">
        <form wire:submit.prevent="sendMessage">
            <x-adminlte-input name="newMessage" wire:model="newMessage" placeholder="Ingrese Mensaje ..."
                fgroup-class="mb-0" maxlength="255" required>
                <x-slot name="appendSlot">
                    <x-livewire.loading-button type="submit" theme="outline-primary" icon="paper-plane"
                        wire:target="sendMessage" />
                </x-slot>
            </x-adminlte-input>
        </form>
    </div>
</div>
