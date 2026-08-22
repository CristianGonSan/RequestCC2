@extends('adminlte::page')

@section('title_prefix', 'Materiales |')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Materiales</li>
            </ol>
        </nav>

        <a href="{{ route('materials.create') }}" class="btn btn-outline-primary">
            <i class="fas fa-fw fa-plus mr-1"></i>Nuevo material
        </a>
    </div>
@endsection

@section('content')
    <livewire:Catalogs.Materials.MaterialsTable />
@endsection
