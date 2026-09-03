@extends('adminlte::page')

@section('plugins.InputMask', true)
@section('plugins.Select2', true)

@section('title_prefix', "Editar Mi Solicitud #{$moneyRequest->id} |")

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('money-requests.index') }}">Mis solicitudes</a></li>
            <li class="breadcrumb-item"><a
                    href="{{ route('money-requests.show', $moneyRequest->id) }}">#{{ $moneyRequest->id }}</a></li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Editar mi solicitud</h1>
    <livewire:MoneyRequests.Users.RequestEdit :MoneyRequestId="$moneyRequest->id" />
@endsection