@extends('adminlte::page')

@section('plugins.iCheck', true)

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Mi Cuenta</li>
        </ol>
    </nav>
    <x-alert></x-alert>
@endsection

@section('title_postfix', '| Mi Cuenta')

@section('content')
    <livewire:Account.ShowAccount />
@endsection
