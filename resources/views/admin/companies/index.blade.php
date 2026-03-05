@extends('adminlte::page')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Empresas</li>
            </ol>
        </nav>
        <a href="{{ route('admin.companies.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-1"></i>Nueva Empresa
        </a>
    </div>
    <x-alert></x-alert>
@stop

@section('content')
    <livewire:Admin.Company.CompanyTable />
@stop
