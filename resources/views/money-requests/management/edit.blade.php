@extends('adminlte::page')

@section('plugins.InputMask', true)
@section('plugins.Select2', true)

@section('title_prefix', "Ad-Editar Solicitud #{$moneyRequest->id} |")

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cost-centers.index') }}">Administrar</a></li>
            <li class="breadcrumb-item"><a
                    href="{{ route('cost-centers.show', $moneyRequest->id) }}">#{{ $moneyRequest->id }}</a></li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Editar solicitud</h1>
    <livewire:MoneyRequests.Management.RequestEdit :MoneyRequestId="$moneyRequest->id" />
@endsection