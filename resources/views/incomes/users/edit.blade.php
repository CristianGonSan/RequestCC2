@extends('adminlte::page')

@section('plugins.InputMask', true)
@section('plugins.Select2', true)

@section('title_prefix', "Editar Ingreso #{$income->id} | ")

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('incomes.index') }}">Ingresos</a></li>
            <li class="breadcrumb-item"><a
                    href="{{ route('incomes.show', $income->id) }}">#{{ $income->id }}</a></li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Editar mi ingreso</h1>
    <livewire:Incomes.Users.IncomeEdit :IncomeId="$income->id" />
@endsection
