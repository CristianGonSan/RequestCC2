@extends('adminlte::page')

@section('title_prefix', 'Nuevo Cliente |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Clientes</a></li>
            <li class="breadcrumb-item active">Nuevo cliente</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Nuevo cliente</h1>
    <livewire:Catalogs.Customers.CustomerCreate />
@endsection
