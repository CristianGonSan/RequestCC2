@extends('adminlte::page')

@section('plugins.InputMask', true)
@section('plugins.Select2', true)

@section('title_prefix', "Editar Mi Solicitud de materiales #{$materialRequest->id} |")

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('material-requests.index') }}">Mis solicitudes de materiales</a></li>
            <li class="breadcrumb-item"><a
                    href="{{ route('material-requests.show', $materialRequest->id) }}">#{{ $materialRequest->id }}</a></li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Editar mi solicitud</h1>
    <livewire:MaterialRequests.Users.MaterialRequestEdit :materialRequestId="$materialRequest->id" />
@endsection
