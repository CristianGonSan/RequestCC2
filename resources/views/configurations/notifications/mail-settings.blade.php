@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title_prefix', 'Notificaciones |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Notificaciones</li>
            <li class="breadcrumb-item active">Email</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Notificaciones por correo</h1>
    <livewire:Configurations.Notifications.MailNotifications />
@endsection
