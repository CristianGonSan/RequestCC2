@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title_prefix', 'Editar Centro de Costos |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cost-centers.index') }}">Centros de Costos</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cost-centers.show', $costCenterId) }}">#{{ $costCenterId }}</a></li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Editar centro de costos</h1>
    <livewire:Catalogs.CostCenters.CostCenterEdit :costCenterId="$costCenterId" />
@endsection
