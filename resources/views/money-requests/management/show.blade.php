@extends('adminlte::page')

@section('title_prefix', "Ad-Solicitud #{$moneyRequest->id} | ")

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('management.money-requests.index') }}">Administrar</a></li>
            <li class="breadcrumb-item active">#{{ $moneyRequest->id }}</li>
            <li class="breadcrumb-item active">Detalles</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Detalles de mi solicitud</h1>
    @if ($moneyRequest->edit_count > 0)
        <div wire:ignore class="alert alert-warning alert-dismissible shadow-sm">
            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>Atención:</strong> Este registro ha sido editado {{ $moneyRequest->edit_count }}
            {{ $moneyRequest->edit_count === 1 ? 'vez' : 'veces' }}.
        </div>
    @endif
    <livewire:MoneyRequests.Management.RequestShow :MoneyRequestId="$moneyRequest->id" />

    <hr class="mt-1">

    <div class="d-block mb-3">
        <ul class="nav nav-pills" id="pills-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="pills-records-tab" data-toggle="pill" href="#pills-records" role="tab"
                    aria-controls="pills-records" aria-selected="false">
                    <i class="fas fa-fw fa-clock-rotate-left"></i>
                    <span class="d-none d-sm-inline ml-1">Historial</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-files-tab" data-toggle="pill" href="#pills-files" role="tab"
                    aria-controls="pills-files" aria-selected="false">
                    <i class="fas fa-fw fa-paperclip"></i>
                    <span class="d-none d-sm-inline ml-1">Archivos</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-messages-tab" data-toggle="pill" href="#pills-messages" role="tab"
                    aria-controls="pills-messages" aria-selected="true">
                    <i class="fas fa-fw fa-comments"></i>
                    <span class="d-none d-sm-inline ml-1">Mensajes</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="tab-content mb-3" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-records" role="tabpanel" aria-labelledby="pills-records-tab">
            <livewire:MoneyRequests.ShowRecords :MoneyRequestId="$moneyRequest->id" lazy />
        </div>

        <div class="tab-pane fade" id="pills-files" role="tabpanel" aria-labelledby="pills-files-tab">
            <livewire:MoneyRequests.ShowFiles :MoneyRequestId="$moneyRequest->id" lazy />
        </div>

        <div class="tab-pane fade" id="pills-messages" role="tabpanel" aria-labelledby="pills-messages-tab">
            <livewire:MoneyRequests.ShowMessages :MoneyRequestId="$moneyRequest->id" lazy />
        </div>
    </div>
@endsection