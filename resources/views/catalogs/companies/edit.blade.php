@extends('adminlte::page')

@section('title_prefix', 'Editar Empresa |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Empresas</a></li>
            <li class="breadcrumb-item"><a href="{{ route('companies.show', $companyId) }}">#{{ $companyId }}</a></li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Editar empresa</h1>
    <livewire:Catalogs.Companies.CompanyEdit :companyId="$companyId" />
@endsection
