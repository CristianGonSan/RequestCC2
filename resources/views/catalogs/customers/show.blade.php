@extends('adminlte::page')

@section('title_prefix', 'Cliente |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Clientes</a></li>
            <li class="breadcrumb-item active">#{{ $customerId }}</li>
            <li class="breadcrumb-item active">Detalles</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Detalles de cliente</h1>
    <livewire:Catalogs.Customers.CustomerShow :customerId="$customerId" />
@endsection
