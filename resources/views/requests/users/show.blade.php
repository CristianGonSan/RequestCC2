@extends('adminlte::page')

@section('title_prefix', "Mi Solicitud #{$requestModel->id} | ")

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('requests.index') }}">Mis Solicitudes</a></li>
            <li class="breadcrumb-item active">#{{ $requestModel->id }}</li>
            <li class="breadcrumb-item active">Detalles</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Detalles de mi solicitud</h1>
    @if ($requestModel->edit_count > 0)
        <div wire:ignore class="alert alert-warning alert-dismissible shadow-sm">
            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>Atención:</strong> Este registro ha sido editado {{ $requestModel->edit_count }}
            {{ $requestModel->edit_count === 1 ? 'vez' : 'veces' }}.
        </div>
    @endif
    <livewire:Requests.Users.RequestShow :requestModelId="$requestModel->id" />

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
            <livewire:Requests.ShowRecords :requestModelId="$requestModel->id" lazy />
        </div>

        <div class="tab-pane fade" id="pills-files" role="tabpanel" aria-labelledby="pills-files-tab">
            <livewire:Requests.ShowFiles :requestModelId="$requestModel->id" lazy />
        </div>

        <div class="tab-pane fade" id="pills-messages" role="tabpanel" aria-labelledby="pills-messages-tab">
            <livewire:Requests.ShowMessages :requestModelId="$requestModel->id" lazy />
        </div>
    </div>
@endsection
