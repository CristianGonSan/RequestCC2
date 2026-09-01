@extends('adminlte::page')

@section('title_prefix', 'Suministrar Material |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Suministrar material</li>
        </ol>
    </nav>
@endsection

@section('content')
    <livewire:MaterialRequests.Fulfillment.MaterialRequestsTable />
@endsection
