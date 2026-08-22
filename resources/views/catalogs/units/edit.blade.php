@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title_prefix', 'Editar Unidad |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('units.index') }}">Unidades</a></li>
            <li class="breadcrumb-item"><a href="{{ route('units.show', $unitId) }}">#{{ $unitId }}</a></li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Editar unidad</h1>
    <livewire:Catalogs.Units.UnitEdit :unitId="$unitId" />
@endsection
