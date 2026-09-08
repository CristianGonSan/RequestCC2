@extends('adminlte::page')

@section('title_prefix', "Ingreso #{$income->id} | ")

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('incomes.index') }}">Ingresos</a></li>
            <li class="breadcrumb-item active">#{{ $income->id }}</li>
            <li class="breadcrumb-item active">Detalles</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Detalles de mi ingreso</h1>

    <livewire:Incomes.Users.IncomeShow :IncomeId="$income->id" />
@endsection
