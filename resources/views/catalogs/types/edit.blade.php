@extends('adminlte::page')

@section('title_prefix', 'Editar Tipo |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('types.index') }}">Tipos</a></li>
            <li class="breadcrumb-item"><a href="{{ route('types.show', $typeId) }}">#{{ $typeId }}</a></li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Editar tipo</h1>
    <livewire:Catalogs.Types.TypeEdit :typeId="$typeId" />
@endsection
