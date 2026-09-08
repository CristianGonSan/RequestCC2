@extends('adminlte::page')

@section('plugins.Chartjs', true)

@section('content_header')
    <x-alert></x-alert>
@endsection

@section('content')

    <div class="pt-3">
        <x-reports.company-balance-table />
    </div>

    <img src="{{ asset('img/cc.png') }}" alt="web" class="img-fluid rounded">
@endsection
