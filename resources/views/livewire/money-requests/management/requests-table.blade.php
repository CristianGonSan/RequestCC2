@use('App\Enums\Requests\MoneyRequestStatus as Status')

<div>
    <x-livewire.table.search-pane>
        @include('partials.livewire.money-requests.table.filters')
    </x-livewire.table.search-pane>

    <div class="form-row mt-2">
        @forelse ($requests as $moneyRequest)
            @php
                /** @var App\Models\MoneyRequests\MoneyRequest $moneyRequest */
                $status = $moneyRequest->status;
            @endphp

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

                            @unless ($status->isCancelled())
                                <button type="button" class="btn btn-outline-primary dropdown-toggle mr-1"
                                    data-toggle="dropdown">
                                    <i class="fas fa-fw fa-ellipsis-vertical mr-1"></i> Acciones
                                </button>
                                <div class="dropdown-menu">
                                    @if ($status->canChangeTo(Status::Accepted))
                                        <button class="dropdown-item" wire:click="acceptRequest"
                                            wire:swal-confirm="¿Está seguro de aceptar esta solicitud?">
                                            Aceptar
                                        </button>
                                    @endif
                                    @if ($status->canChangeTo(Status::Rejected))
                                        <button class="dropdown-item" wire:click="rejectRequest"
                                            wire:swal-confirm="¿Está seguro de rechazar esta solicitud?">
                                            Rechazar
                                        </button>
                                    @endif
                                    @if ($status->canChangeTo(Status::Pending))
                                        <button class="dropdown-item" wire:click="markAsPending"
                                            wire:swal-confirm="¿Está seguro de pasar a pendiente esta solicitud?">
                                            Pendiente
                                        </button>
                                    @endif
                                    @unless ($moneyRequest->is_transfer)
                                        <div class="dropdown-divider"></div>
                                        @if ($status->canChangeTo(Status::Paid))
                                            <button class="dropdown-item" wire:click="markAsPaid"
                                                wire:swal-confirm="¿Está seguro de pagar esta solicitud?">
                                                Pagadar
                                            </button>
                                        @endif
                                        @if ($status->canChangeTo(Status::Cancelled))
                                            <button class="dropdown-item" wire:click="cancelRequest"
                                                wire:swal-confirm="¿Está seguro de cancelar esta solicitud?">
                                                Cancelar
                                            </button>
                                        @endif
                                    @endunless
                                </div>
                            @endunless
                        </div>
                    </div>
                </div>
                
            </div>
        @empty
            @include('partials.money-requests.table.empty')
        @endforelse
    </div>

    {{ $requests->links() }}
</div>
