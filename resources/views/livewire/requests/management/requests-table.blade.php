@php
    /** @var App\Models\RequestModel $requestModel */
@endphp

<div>
    <x-livewire.table.search-pane>
        @include('partials.livewire.requests-model.table.filters')
    </x-livewire.table.search-pane>

    <div class="form-row mt-2">
        @forelse ($requests as $requestModel)
            <div class="col-lg-4 col-md-6 col-sm-12" wire:key="card-{{ $requestModel->id }}">

                <div class="card card-dark">
                    @include('partials.requests.card.card-header')
                    @include('partials.requests.card.card-body')

                    <div class="card-footer">
                        <div class="d-flex">
                            <a href="{{ route('management.requests.show', $requestModel->id) }}"
                                class="btn btn-outline-primary">
                                <i class="fas fa-fw fa-eye mr-1"></i> Ver #{{ number_format($requestModel->id) }}
                            </a>

                            @if (!$requestModel->status->isCancelled())
                                <button type="button" class="btn btn-outline-primary dropdown-toggle ml-auto"
                                    data-toggle="dropdown">
                                    <i class="fas fa-fw fa-ellipsis-vertical mr-1"></i> Acciones
                                </button>
                                <div class="dropdown-menu">
                                    @if (!$requestModel->status->isAccepted())
                                        <button class="dropdown-item"
                                            wire:click="acceptRequest({{ $requestModel->id }})"
                                            wire:swal-confirm="¿Está seguro de aceptar esta solicitud?">
                                            Aceptar
                                        </button>
                                    @endif
                                    @if (!$requestModel->status->isRejected())
                                        <button class="dropdown-item"
                                            wire:click="rejectRequest({{ $requestModel->id }})"
                                            wire:swal-confirm="¿Está seguro de rechazar esta solicitud?">
                                            Rechazar
                                        </button>
                                    @endif
                                    @if (!$requestModel->status->isPending())
                                        <button class="dropdown-item"
                                            wire:click="markAsPending({{ $requestModel->id }})"
                                            wire:swal-confirm="¿Está seguro de pasar a pendiente esta solicitud?">
                                            Pendiente
                                        </button>
                                    @endif
                                    @if (!$requestModel->is_transfer)
                                        <div class="dropdown-divider"></div>
                                        @if (!$requestModel->status->isPaid())
                                            <button class="dropdown-item"
                                                wire:click="markAsPaid({{ $requestModel->id }})"
                                                wire:swal-confirm="¿Está seguro de pagar esta solicitud?">
                                                Pagadar
                                            </button>
                                        @endif
                                        @if (!$requestModel->status->isCancelled())
                                            <button class="dropdown-item"
                                                wire:click="cancelRequest({{ $requestModel->id }})"
                                                wire:swal-confirm="¿Está seguro de cancelar esta solicitud?">
                                                Cancelar
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            @include('partials.requests.table.empty')
        @endforelse
    </div>

    {{ $requests->links() }}
</div>
