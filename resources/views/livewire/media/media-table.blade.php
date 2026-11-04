<div>
    <x-livewire.table.search-pane>
        @include('partials.livewire.media.table.filters')
    </x-livewire.table.search-pane>

    <div class="form-row mt-2">
        @forelse ($media as $item)
            @php
                /** @var App\Models\CustomMedia $item */
                $extension = $item->extension_support;
            @endphp
            <div class="col-xl-3 col-lg-4 col-md-6" wire:key="media-{{ $item->id }}">
                <div class="card card-hover-shadow">
                    <div class="card-header py-2 d-flex align-items-center bg-transparent border-bottom-0">
                        <div class="d-flex align-items-center overflow-hidden">
                            <i class="fa-solid {{ $extension->icon() }} fa-lg mr-2"
                                style="color: {{ $extension->color() }}"></i>
                            <small class="text-muted text-uppercase">{{ $extension->value }}</small>
                        </div>

                        <div class="ml-auto card-tools">
                            <div class="dropdown">
                                <button class="btn btn-tool btn-sm" type="button" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false"
                                    aria-label="Opciones de {{ $item->file_name }}">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="{{ $item->getUrl() }}" target="_blank"
                                        rel="noopener noreferrer">
                                        <i class="fas fa-fw fa-external-link-alt mr-2"></i>Ver archivo
                                    </a>
                                    <a class="dropdown-item" href="{{ $item->getDownloadUrl() }}">
                                        <i class="fas fa-fw fa-download mr-2"></i>Descargar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body py-2">
                        <div class="mb-1 font-weight-bold text-truncate h6" title="{{ $item->file_name }}">
                            {{ $item->file_name }}
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="small text-muted">{{ $item->human_readable_size }}</span>
                        </div>
                    </div>

                    <div class="card-footer py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-truncate mr-2" title="{{ $item->user?->name ?? 'Sin usuario' }}">
                                <small class="text-muted">{{ $item->user?->name ?? 'Sin usuario' }}</small>
                            </div>
                            <div class="text-nowrap">
                                <small class="text-muted" title="{{ $item->created_at?->format('d/m/Y H:i') }}">
                                    {{ $item->created_at?->diffForHumans(null, true) }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            @include('partials.livewire.media.table.empty')
        @endforelse
    </div>

    {{ $media->links() }}
</div>
