@extends('adminlte::page')

@section('title_prefix', 'Material |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('materials.index') }}">Materiales</a></li>
            <li class="breadcrumb-item active">#{{ $materialId }}</li>
            <li class="breadcrumb-item active">Detalles</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Detalles de material</h1>
    <livewire:Catalogs.Materials.MaterialShow :materialId="$materialId" />
@endsection
