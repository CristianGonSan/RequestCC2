@use('App\Enums\Files\FileExtensionSupport')

<div class="card mb-0" style="height: 70vh">
    <div class="card-header">
        <h2 class="card-title">Archivos</h2>

        <div class="card-tools">
            <button wire:click="$refresh" class="btn btn-tool">
                <i wire:loading.class="fa-spin" class="fas fa-fw fa-arrows-rotate"></i>
            </button>
        </div>
    </div>

    <div class="card-body p-0" style="overflow: hidden;">
        <div class="list-group list-group-flush overflow-auto h-100" x-init="$el.scrollTop = $el.scrollHeight;
        new MutationObserver(() => {
            $el.scrollTop = $el.scrollHeight;
        }).observe($el, { childList: true });">

            @php
                $userId = Auth::id();
            @endphp

            @forelse($files as $file)
                @php
                    /**
                     * @var FileExtensionSupport $extension
                     */
                    $extension = $file->extension_support;
                @endphp

                <div class="list-group-item list-group-item-action d-flex align-items-center py-2 px-3"
                    wire:key="file-{{ $file->id }}">

                    <div class="d-flex align-items-center justify-content-center rounded-circle mr-3 flex-shrink-0"
                        style="width: 40px; height: 40px; background-color: {{ $extension->color() }}1A;">
                        <i class="fas fa-fw {{ $extension->icon() }}" style="color: {{ $extension->color() }};"></i>
                    </div>

                    <div class="flex-grow-1" style="min-width: 0;">
                        <div class="text-truncate font-weight-bold" title="{{ $file->original_name }}">
                            {{ $file->original_name }}
                        </div>
                        <div class="d-flex align-items-center text-muted small">
                            <span>{{ $file->human_readable_size }}</span>
                            <span class="mx-2">&bull;</span>
                            <span class="text-truncate" style="max-width: 140px;" title="{{ $file->user->name }}">
                                {{ $file->user->name }}
                            </span>
                            <span class="ml-1">{{ $file->created_at?->diffForHumans() }}</span>
                        </div>
                    </div>

                    <div class="ml-2 flex-shrink-0">
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm btn-light" data-toggle="dropdown"
                                aria-label="Opciones del archivo">
                                <i class="fas fa-fw fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right shadow-sm">
                                <a href="{{ route('file.preview', $file->id) }}" class="dropdown-item"
                                    title="Visualizar" target="_blank">
                                    <i class="fas fa-fw fa-eye mr-2 text-muted"></i> Visualizar
                                </a>
                                <button type="button" wire:click="downloadFile({{ $file->id }})"
                                    class="dropdown-item">
                                    <i class="fas fa-fw fa-download mr-2 text-muted"></i> Descargar
                                </button>
                                @if ($file->user_id === $userId)
                                    <div class="dropdown-divider"></div>
                                    <button type="button" class="dropdown-item text-danger"
                                        wire:click="deleteFile({{ $file->id }})"
                                        wire:swal-delete="¿Está seguro de eliminar este archivo?">
                                        <i class="fas fa-fw fa-trash-alt mr-2"></i> Eliminar
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="d-flex flex-column align-items-center justify-content-center text-muted h-100 py-5">
                    <i class="fas fa-folder-open fa-2x mb-2"></i>
                    No hay archivos aún.
                </div>
            @endforelse
        </div>
    </div>

    <div class="card-footer">
        <form wire:submit.prevent="save">
            <x-livewire.file-upload name="newFile" fgroup-class="mb-1"
                accept=".pdf, .jpeg, .png, .jpg, .docx, .doc, .xlsx, .xls">
                {{ $fileName }}

                <x-slot name="appendSlot">
                    <x-livewire.loading-button type="submit" label="Subir" theme="outline-primary" icon="upload"
                        wire:target='save' />
                </x-slot>
            </x-livewire.file-upload>
        </form>
        <p class="text-muted small mb-0">
            Admitidos: pdf, jpeg, png, jpg, docx, doc, xlsx, xls | Máximo 10MB.
        </p>
    </div>
</div>
