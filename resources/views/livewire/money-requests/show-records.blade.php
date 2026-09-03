<div>
    <div wire:ignore.self id="card" class="card mb-0">
        <div class="card-header">
            <h2 class="card-title">Historial</h2>

            <div class="card-tools">
                <button id="maximize" class="btn btn-tool" data-card-widget="maximize">
                    <i class="fas fa-fw fa-expand"></i>
                </button>

                <button wire:click="$refresh" class="btn btn-tool">
                    <i wire:loading.class="fa-spin" class="fas fa-fw fa-arrows-rotate"></i>
                </button>
            </div>
        </div>

        <div wire:ignore.self class="card-body p-0" style="height: 55vh">
            <div class="list-group" style="height: 100%; overflow-y: auto;">
                @forelse ($records as $record)
                    <div class="list-group-item py-3">
                        <div>
                            <h6 class="mb-1">{{ $record->user->name }} - ({{ $record->user->email }})</h6>
                            <p class="mb-1">
                                <span class="badge badge-{{ $record->getActionBSClass() }}">
                                    {{ $record->getActionText() }}
                                </span>
                            </p>
                            <p class="text-muted mb-1">{!! nl2br($record->details) !!}</p>

                            <small class="text-muted">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                {{ $record->registered_at->format('d-m-Y h:i:s a') }} ||
                                {{ $record->registered_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                @empty
                    <div class="d-flex flex-column align-items-center justify-content-center text-muted h-100 py-5">
                        <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                        No hay actividad aún.
                    </div>
                @endforelse
            </div>
        </div>

        <div class="card-footer pb-0">
            {{ $records->links() }}
        </div>
    </div>
</div>
