@extends('adminlte::page')

@section('plugins.Select2', true)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Mis Solicitudes</li>
            </ol>
        </nav>
        <a href="{{ route('requests.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-1"></i>Nueva Solicitud
        </a>
    </div>
    <x-alert></x-alert>
@endsection

@section('content')
    <livewire:requests.user.RequestsTable />
@endsection
