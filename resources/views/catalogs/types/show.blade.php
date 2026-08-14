@extends('adminlte::page')

@section('title_prefix', 'Tipo |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('types.index') }}">Tipos</a></li>
            <li class="breadcrumb-item active">#{{ $typeId }}</li>
            <li class="breadcrumb-item active">Detalles</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Detalles de tipo</h1>
    <livewire:Catalogs.Types.TypeShow :typeId="$typeId" />

    <hr class="mt-1">

    <div class="d-block mb-3">
        <ul class="nav nav-pills" id="pills-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="pills-requests-tab" data-toggle="pill" href="#pills-requests" role="tab"
                    aria-controls="pills-requests" aria-selected="true">
                    <i class="fas fa-fw fa-file-lines"></i>
                    <span class="d-none d-sm-inline ml-1">Solicitudes</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-users-tab" data-toggle="pill" href="#pills-users" role="tab"
                    aria-controls="pills-users" aria-selected="false">
                    <i class="fas fa-fw fa-users"></i>
                    <span class="d-none d-sm-inline ml-1">Usuarios</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-requests" role="tabpanel" aria-labelledby="pills-requests-tab">
            <livewire:Catalogs.Types.RequestsTable :typeId="$typeId" />
        </div>

        <div class="tab-pane fade" id="pills-users" role="tabpanel" aria-labelledby="pills-users-tab">
            <livewire:Catalogs.Types.UsersTable lazy :typeId="$typeId" />
        </div>
    </div>
@endsection
