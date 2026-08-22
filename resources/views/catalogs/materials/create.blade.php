@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title_prefix', 'Nuevo Material |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('materials.index') }}">Materiales</a></li>
            <li class="breadcrumb-item active">Nuevo material</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Nuevo material</h1>
    <livewire:Catalogs.Materials.MaterialCreate />
@endsection
