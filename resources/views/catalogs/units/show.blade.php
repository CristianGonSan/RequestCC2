@extends('adminlte::page')

@section('title_prefix', 'Unidad |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('units.index') }}">Unidades</a></li>
            <li class="breadcrumb-item active">#{{ $unitId }}</li>
            <li class="breadcrumb-item active">Detalles</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Detalles de unidad</h1>
    <livewire:Catalogs.Units.UnitShow :unitId="$unitId" />

    <hr class="mt-1">

    <h2 class="h5">Materiales con esta unidad</h2>
    <livewire:Catalogs.Units.MaterialsTable :unitId="$unitId">
@endsection
