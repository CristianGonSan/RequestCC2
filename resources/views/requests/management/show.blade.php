@extends('adminlte::page')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('management.requests.index') }}">Administrar</a></li>
            <li class="breadcrumb-item active">Solicitud #{{ $requestModel->id }}</li>
        </ol>
    </nav>
    <x-alert></x-alert>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <ul class="nav nav-tabs" style="overflow-x: auto; overflow-y: hidden; flex-wrap: nowrap">
                <li class="nav-item"><a class="nav-link active" href="#details" data-toggle="tab">Detalles</a></li>
                <li class="nav-item"><a class="nav-link" href="#messages" data-toggle="tab">Mensajes</a></li>
                <li class="nav-item"><a class="nav-link" href="#files" data-toggle="tab">Archivos</a></li>
                <li class="nav-item"><a class="nav-link" href="#records" data-toggle="tab">Historial</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="details">
                    <livewire:Requests.Management.RequestDetails :requestModel="$requestModel"/>
                </div>
                <div class="tab-pane" id="messages">
                    <livewire:Requests.ShowMessages :requestModel="$requestModel"/>
                </div>
                <div class="tab-pane" id="files">
                    <livewire:Requests.ShowFiles :requestModel="$requestModel"/>
                </div>
                <div class="tab-pane" id="records">
                    <livewire:requests.ShowRecords :requestModel="$requestModel" />
                </div>
            </div>
        </div>
    </div>
@endsection
