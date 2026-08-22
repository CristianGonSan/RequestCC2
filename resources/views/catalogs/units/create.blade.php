@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title_prefix', 'Nueva Unidad |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('units.index') }}">Unidades</a></li>
            <li class="breadcrumb-item active">Nueva unidad</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Nueva unidad</h1>
    <livewire:Catalogs.Units.UnitCreate />
@endsection
