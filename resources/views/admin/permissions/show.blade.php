@extends('adminlte::page')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.permissions.index') }}">Permisos</a></li>
            <li class="breadcrumb-item active">{{ $permission->name }}</li>
        </ol>
    </nav>
    <x-alert></x-alert>
@endsection

@section('content')
    <livewire:Admin.Permission.ShowPermission :permission="$permission" />
@endsection
