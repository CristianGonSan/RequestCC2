@extends('adminlte::page')

@section('title_prefix', 'Administrar Material |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Administrar material</li>
        </ol>
    </nav>
@endsection

@section('content')
    <livewire:MaterialRequests.Management.MaterialRequestsTable />
@endsection
