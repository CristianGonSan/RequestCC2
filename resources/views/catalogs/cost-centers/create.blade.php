@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title_prefix', 'Nuevo Centro de Costos |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cost-centers.index') }}">Centros de Costos</a></li>
            <li class="breadcrumb-item active">Nuevo centro de costos</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Nuevo centro de costos</h1>
    <livewire:Catalogs.CostCenters.CostCenterCreate />
@endsection
