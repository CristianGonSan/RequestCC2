@extends('adminlte::page')

@section('title_prefix', "Su-Solicitud de material #{$materialRequest->id} | ")

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('fulfillment.material-requests.index') }}">Suministrar material</a></li>
            <li class="breadcrumb-item active">#{{ $materialRequest->id }}</li>
            <li class="breadcrumb-item active">Detalles</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Detalles de solicitud de material</h1>

    <livewire:MaterialRequests.Fulfillment.MaterialRequestShow :materialRequestId="$materialRequest->id" />
@endsection
