@extends('adminlte::page')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Permisos</li>
            </ol>
        </nav>
        <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-1"></i>Nuevo Permiso
        </a>
    </div>
    <x-alert></x-alert>
@stop

@section('content')
    <livewire:Admin.Permission.PermissionTable />
@stop
