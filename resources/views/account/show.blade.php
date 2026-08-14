@extends('adminlte::page')

@section('title_prefix', 'Mi Cuenta |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Mi cuenta</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Detalles de mi cuenta</h1>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-muted">Nombre</dt>
                        <dd class="col-sm-8 font-weight-bold">{{ $user->name }}</dd>

                        <dt class="col-sm-4 text-muted">Correo electrónico</dt>
                        <dd class="col-sm-8">{{ $user->email }}</dd>

                        <dt class="col-sm-4 text-muted">Roles</dt>
                        <dd class="col-sm-8 mb-0">
                            @forelse ($user->roles()->orderBy('name')->get() as $role)
                                <span class="badge badge-info mr-1">{{ $role->name }}</span>
                            @empty
                                <span class="text-muted">Sin roles asignados</span>
                            @endforelse
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Creado:</span>
                        <span>{{ $user->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Actualizado:</span>
                        <span>{{ $user->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr class="mt-1">

    <div class="d-block mb-3">
        <ul class="nav nav-pills" id="pills-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="pills-sessions-tab" data-toggle="pill" href="#pills-sessions" role="tab"
                    aria-controls="pills-sessions" aria-selected="true">
                    <i class="fas fa-fw fa-user-shield"></i>
                    <span class="d-none d-sm-inline ml-1">Sesiones Activas</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-password-tab" data-toggle="pill" href="#pills-password" role="tab"
                    aria-controls="pills-password" aria-selected="false">
                    <i class="fas fa-fw fa-key"></i>
                    <span class="d-none d-sm-inline ml-1">Cambiar Contraseña</span>
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
        </ul>
    </div>

    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-sessions" role="tabpanel" aria-labelledby="pills-sessions-tab">
            <livewire:Account.ShowSessions />
        </div>

        <div class="tab-pane fade" id="pills-password" role="tabpanel" aria-labelledby="pills-password-tab">
            <livewire:Account.ChangePassword />
        </div>

        <div class="tab-pane fade" id="pills-types" role="tabpanel" aria-labelledby="pills-types-tab">
            <livewire:Account.TypesTable lazy />
        </div>

        <div class="tab-pane fade" id="pills-companies" role="tabpanel" aria-labelledby="pills-companies-tab">
            <livewire:Account.CompaniesTable lazy />
        </div>
    </div>
@endsection
