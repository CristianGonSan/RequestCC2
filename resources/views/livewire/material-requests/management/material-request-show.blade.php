@use('App\Enums\Requests\MaterialRequestStatus as Status')

@php
    /** @var App\Models\MaterialRequests\MaterialRequest $materialRequest */
    $status = $materialRequest->status;
@endphp

<div>
    @include('partials.livewire.material-requests.show.details-card-show')

    <div class="my-3">
        @unless ($status->isCancelled())
            <button type="button" class="btn btn-outline-primary dropdown-toggle mr-1" data-toggle="dropdown">
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
            </div>
        @endunless

        <a href="{{ route('management.material-requests.index') }}" class="btn btn-outline-secondary mr-1">
            <i class="fas fa-fw fa-chevron-left mr-1"></i> Volver
        </a>
    </div>

    <hr>

    <div class="d-block mb-3" wire:ignore>
        <ul class="nav nav-pills" id="pills-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="pills-items-tab" data-toggle="pill" href="#pills-items" role="tab"
                    aria-controls="pills-items" aria-selected="false">
                    <i class="fas fa-fw fa-boxes-packing mr-1"></i>
                    <span class="d-none d-sm-inline ml-1">Solicitado</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-fulfillments-tab" data-toggle="pill" href="#pills-fulfillments" role="tab"
                    aria-controls="pills-fulfillments" aria-selected="false">
                    <i class="fas fa-fw fa-truck-loading mr-1"></i>
                    <span class="d-none d-sm-inline ml-1">Suplido</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="tab-content mb-3" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-items" role="tabpanel" aria-labelledby="pills-items-tab"
            wire:ignore.self>
            @include('partials.livewire.material-requests.show.items-card-show')
        </div>

        <div class="tab-pane fade" id="pills-fulfillments" role="tabpanel" aria-labelledby="pills-fulfillments-tab"
            wire:ignore.self>
            @include('partials.livewire.material-requests.show.fulfillments-card-show')
        </div>
    </div>
</div>
