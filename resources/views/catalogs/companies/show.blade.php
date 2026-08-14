@extends('adminlte::page')

@section('title_prefix', 'Empresa |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Empresas</a></li>
            <li class="breadcrumb-item active">#{{ $companyId }}</li>
            <li class="breadcrumb-item active">Detalles</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Detalles de empresa</h1>
    <livewire:Catalogs.Companies.CompanyShow :companyId="$companyId" />

    <hr class="mt-1">

    <div class="mb-3">
        <ul class="nav nav-pills" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="pills-users-tab" data-toggle="pill" href="#pills-users" role="tab"
                    aria-controls="pills-users" aria-selected="true">
                    <i class="fas fa-fw fa-users"></i>
                    <span class="d-none d-sm-inline ml-1">Usuarios</span>
                </a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-cost_centers-tab" data-toggle="pill" href="#pills-cost_centers" role="tab"
                    aria-controls="pills-cost_centers" aria-selected="false">
                    <i class="fas fa-fw fa-coins"></i>
                    <span class="d-none d-sm-inline ml-1">Centros de Costo</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-users" role="tabpanel" aria-labelledby="pills-users-tab">
            <livewire:Catalogs.Companies.UsersTable :companyId="$companyId" />
        </div>

        <div class="tab-pane fade" id="pills-cost_centers" role="tabpanel" aria-labelledby="pills-cost_centers-tab">
            <livewire:Catalogs.Companies.CostCentersTable lazy :companyId="$companyId" />
        </div>
    </div>
@endsection
