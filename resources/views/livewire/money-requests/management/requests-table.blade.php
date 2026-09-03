@php
    /** @var App\Models\MoneyRequests\MoneyRequest $moneyRequest */
@endphp

<div>
    <x-livewire.table.search-pane>
        @include('partials.livewire.money-requests.table.filters')
    </x-livewire.table.search-pane>

    <div class="form-row mt-2">
        @forelse ($requests as $moneyRequest)
            <div class="col-lg-4 col-md-6 col-sm-12" wire:key="card-{{ $moneyRequest->id }}">

                <div class="card card-dark">
                    @include('partials.money-requests.card.card-header')
                    @include('partials.money-requests.card.card-body')

                    <div class="card-footer">
                        <div class="d-flex">
                            <a href="{{ route('management.money-requests.show', $moneyRequest->id) }}"
                                class="btn btn-outline-primary">
                                <i class="fas fa-fw fa-eye mr-1"></i> Ver #{{ number_format($moneyRequest->id) }}
                            </a>

                            @if (!$moneyRequest->status->isCancelled())
                                <button type="button" class="btn btn-outline-primary dropdown-toggle ml-auto"
                                    data-toggle="dropdown">
                                    <i class="fas fa-fw fa-ellipsis-vertical mr-1"></i> Acciones
                                </button>
                                <div class="dropdown-menu">
                                    @if (!$moneyRequest->status->isAccepted())
                                        <button class="dropdown-item" wire:click="acceptRequest({{ $moneyRequest->id }})"
                                            wire:swal-confirm="¿Está seguro de aceptar esta solicitud?">
                                            Aceptar
                                        </button>
                                    @endif
                                    @if (!$moneyRequest->status->isRejected())
                                        <button class="dropdown-item" wire:click="rejectRequest({{ $moneyRequest->id }})"
                                            wire:swal-confirm="¿Está seguro de rechazar esta solicitud?">
                                            Rechazar
                                        </button>
                                    @endif
                                    @if (!$moneyRequest->status->isPending())
                                        <button class="dropdown-item" wire:click="markAsPending({{ $moneyRequest->id }})"
                                            wire:swal-confirm="¿Está seguro de pasar a pendiente esta solicitud?">
                                            Pendiente
                                        </button>
                                    @endif
                                    @if (!$moneyRequest->is_transfer)
                                        <div class="dropdown-divider"></div>
                                        @if (!$moneyRequest->status->isPaid())
                                            <button class="dropdown-item" wire:click="markAsPaid({{ $moneyRequest->id }})"
                                                wire:swal-confirm="¿Está seguro de pagar esta solicitud?">
                                                Pagadar
                                            </button>
                                        @endif
                                        @if (!$moneyRequest->status->isCancelled())
                                            <button class="dropdown-item" wire:click="cancelRequest({{ $moneyRequest->id }})"
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