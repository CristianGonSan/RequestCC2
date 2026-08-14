@extends('adminlte::page')

@section('title_prefix', 'Nueva Empresa |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Empresas</a></li>
            <li class="breadcrumb-item active">Nueva empresa</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Nueva empresa</h1>
    <livewire:Catalogs.Companies.CompanyCreate />
@endsection
