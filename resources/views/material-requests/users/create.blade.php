@extends('adminlte::page')

@section('plugins.InputMask', true)
@section('plugins.Select2', true)

@section('title_prefix', 'Nueva Solicitud |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('requests.index') }}">Mis solicitudes</a></li>
            <li class="breadcrumb-item active">Nueva solicitud</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Nueva solicitud</h1>
    <livewire:MaterialRequests.Users.MaterialRequestCreate />
@endsection
