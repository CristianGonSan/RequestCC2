@use('App\Models\CustomMedia')

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Archivos</h2>

        <div class="card-tools">
            <button wire:click="$refresh" class="btn btn-tool">
                <i wire:loading.class="fa-spin" class="fas fa-fw fa-arrows-rotate"></i>
            </button>
        </div>
    </div>

    <div class="card-body p-0" style="overflow: hidden;">
        @php
            $userId = Auth::id();
        @endphp
        <div class="list-group list-group-flush overflow-auto" style="height: 45vh;">
            @forelse ($media as $item)
                @php
                    /** @var CustomMedia $item*/
                    $extension = $item->extension_support;
                @endphp

                <div class="list-group-item list-group-item-action d-flex align-items-center py-2 px-3"
                    wire:key="media-{{ $item->id }}">
                    <div class="d-flex align-items-center justify-content-center rounded-circle mr-3 flex-shrink-0"
                        style="width: 40px; height: 40px; background-color: {{ $extension->color() }}1A;">
                        <i class="fas fa-fw {{ $extension->icon() }}" style="color: {{ $extension->color() }};"></i>
                    </div>

                    <div class="flex-grow-1" style="min-width: 0;">
                        <div class="text-truncate font-weight-bold" title="{{ $item->file_name }}">
                            {{ $item->file_name }}
                        </div>
                        <div class="d-flex align-items-center text-muted small">
                            <span>{{ $item->human_readable_size }}</span>
                            <span class="mx-2">&bull;</span>
                            <span class="text-truncate" style="max-width: 140px;" title="{{ $item->user?->name }}">
                                {{ $item->user?->name }}
                            </span>
                            <span class="ml-1">{{ $item->created_at?->diffForHumans() }}</span>
                        </div>
                    </div>

                    <div class="ml-2 flex-shrink-0">
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm btn-light" data-toggle="dropdown"
                                aria-label="Opciones del archivo">
                                <i class="fas fa-fw fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right shadow-sm">
                                <a href="{{ $item->getUrl() }}" class="dropdown-item" title="Visualizar" target="_blank">
                                    <i class="fas fa-fw fa-eye mr-2 text-muted"></i> Visualizar
                                </a>
                                <a href="{{ $item->getDownloadUrl() }}" class="dropdown-item" title="Descargar">
                                    <i class="fas fa-fw fa-download mr-2 text-muted"></i> Descargar
                                </a>
                                @if ($item->user_id == $userId)
                                    <div class="dropdown-divider"></div>
                                    <button type="button" class="dropdown-item text-danger" wire:click="delete({{ $item->id }})"
                                        wire:swal-delete="¿Está seguro de eliminar este archivo?">
                                        <i class="fas fa-fw fa-trash-alt mr-2"></i> Eliminar
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="d-flex flex-column align-items-center justify-content-center text-muted py-5">
                    <i class="fas fa-folder-open fa-2x mb-2"></i>
                    No hay archivos aún.
                </div>
            @endforelse
        </div>
    </div>

    <div class="card-footer">
        <form wire:submit.prevent="save">
            <x-livewire.file-upload name="upload" fgroup-class="mb-1"
                accept="{{ '.' . str_replace(',', ', .', $acceptedMimes) }}">
                {{ $fileName }}

                <x-slot name="appendSlot">
                    <x-livewire.loading-button type="submit" label="Subir" theme="outline-primary" icon="upload"
                        wire:target="save" />
                </x-slot>
            </x-livewire.file-upload>
        </form>
        <p class="text-muted small mb-0">
            Admitidos: {{ str_replace(',', ', ', $acceptedMimes) }} | Máximo {{ intdiv($maxSizeKb, 1024) }}MB.
        </p>
    </div>
</div>
