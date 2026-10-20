@extends('adminlte::page')

@section('title_prefix', "Ad-Solicitud de material #{$materialRequest->id} | ")

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('management.material-requests.index') }}">Administrar material</a>
            </li>
            <li class="breadcrumb-item active">#{{ $materialRequest->id }}</li>
            <li class="breadcrumb-item active">Detalles</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Detalles de solicitud de material</h1>

    <livewire:MaterialRequests.Management.MaterialRequestShow :materialRequestId="$materialRequest->id" />

    <hr class="mt-1">

    <div class="d-block mb-3">
        <ul class="nav nav-pills" id="pills-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="pills-history-tab" data-toggle="pill" href="#pills-history" role="tab"
                    aria-controls="pills-history" aria-selected="true">
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
                    aria-controls="pills-messages" aria-selected="false">
                    <i class="fas fa-fw fa-comments"></i>
                    <span class="d-none d-sm-inline ml-1">Mensajes</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="tab-content mb-3" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-history" role="tabpanel" aria-labelledby="pills-history-tab">
            <livewire:Common.ShowChangeLogs :modelClass="$materialRequest::class" :modelId="$materialRequest->id" lazy />
        </div>

        <div class="tab-pane fade" id="pills-files" role="tabpanel" aria-labelledby="pills-files-tab">
            <livewire:Media.MediaBrowser :modelClass="$materialRequest::class" :modelId="$materialRequest->id" lazy />
        </div>

        <div class="tab-pane fade" id="pills-messages" role="tabpanel" aria-labelledby="pills-messages-tab">
            <livewire:Common.ShowMessages :modelClass="$materialRequest::class" :modelId="$materialRequest->id" lazy />
        </div>

    </div>
@endsection
