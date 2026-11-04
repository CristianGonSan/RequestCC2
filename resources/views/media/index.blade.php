@extends('adminlte::page')

@section('title_prefix', 'Archivos |')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Archivos</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
    <livewire:Media.MediaTable />
@endsection
