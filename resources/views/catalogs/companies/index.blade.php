@extends('adminlte::page')

@section('title_prefix', 'Empresas |')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Empresas</li>
            </ol>
        </nav>

        <a href="{{ route('companies.create') }}" class="btn btn-outline-primary">
            <i class="fas fa-fw fa-plus mr-1"></i>Nueva empresa
        </a>
    </div>
@endsection

@section('content')
    <livewire:Catalogs.Companies.CompaniesTable />
@endsection
