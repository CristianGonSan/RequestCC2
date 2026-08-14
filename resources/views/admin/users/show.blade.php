@extends('adminlte::page')

@section('title_prefix', 'Usuario |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuarios</a></li>
            <li class="breadcrumb-item active">#{{ $userId }}</li>
            <li class="breadcrumb-item active">Detalles</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Detalles de usuario</h1>
    <livewire:Admin.Users.UserShow :userId="$userId" />

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
                <a class="nav-link" id="pills-types-tab" data-toggle="pill" href="#pills-types" role="tab"
                    aria-controls="pills-types" aria-selected="false">
                    <i class="fas fa-fw fa-clipboard-list"></i>
                    <span class="d-none d-sm-inline ml-1">Tipos</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-companies-tab" data-toggle="pill" href="#pills-companies" role="tab"
                    aria-controls="pills-companies" aria-selected="false">
                    <i class="fas fa-fw fa-building"></i>
                    <span class="d-none d-sm-inline ml-1">Empresas</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-password-tab" data-toggle="pill" href="#pills-password" role="tab"
                    aria-controls="pills-password" aria-selected="false">
                    <i class="fas fa-fw fa-key"></i>
                    <span class="d-none d-sm-inline ml-1">Cambiar Contraseña</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-requests" role="tabpanel" aria-labelledby="pills-requests-tab">
            <livewire:Admin.Users.RequestsTable :userId="$userId" />
        </div>

        <div class="tab-pane fade" id="pills-types" role="tabpanel" aria-labelledby="pills-types-tab">
            <livewire:Admin.Users.TypesEdit :userId="$userId" lazy />
        </div>

        <div class="tab-pane fade" id="pills-companies" role="tabpanel" aria-labelledby="pills-companies-tab">
            <livewire:Admin.Users.CompaniesEdit :userId="$userId" lazy />
        </div>

        <div class="tab-pane fade" id="pills-password" role="tabpanel" aria-labelledby="pills-password-tab">
            <livewire:Admin.Users.ChangePassword :userId="$userId" />
        </div>

    </div>
@endsection
