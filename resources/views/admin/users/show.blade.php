@extends('adminlte::page')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Usuarios</a></li>
            <li class="breadcrumb-item active">{{ $user->email }}</li>
        </ol>
    </nav>
    <x-alert></x-alert>
@endsection

@section('content')
    <livewire:Admin.User.ShowAccount :user="$user" />
@endsection
