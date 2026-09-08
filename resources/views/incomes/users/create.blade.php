@extends('adminlte::page')

@section('plugins.InputMask', true)
@section('plugins.Select2', true)

@section('title_prefix', 'Nuevo Ingreso | ')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('incomes.index') }}">Ingresos</a></li>
            <li class="breadcrumb-item active">Nuevo ingreso</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Nuevo ingreso</h1>
    <livewire:Incomes.Users.IncomeCreate :copyFromId="$copyFromId" />
@endsection
