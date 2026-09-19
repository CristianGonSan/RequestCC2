@extends('adminlte::page')

@section('plugins.Chartjs', true)

@section('title_prefix', 'Balance |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Balance</li>
        </ol>
    </nav>
@endsection


@section('content')
    <h1 class="h4">Blance del periodo</h1>
    <livewire:Reports.PeriodBalanceSummary />
@endsection
